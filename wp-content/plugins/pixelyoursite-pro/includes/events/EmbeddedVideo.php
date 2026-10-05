<?php

namespace PixelYourSite;
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class EmbeddedVideo {

	private static $_instance;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static array $youtube_patterns = array(
		'/<iframe.*?src="(.*?youtube.com.*?)".*?>.*?<\/iframe>/i',
		'/<object.*?>.*?<embed.*?src="(https?:\/\/(?:www\.)?youtube\.com\/v\/[a-zA-Z0-9_-]{11}).*?".*?<\/object>/is',
		'/<embed.*?src="(.*?youtube.com.*?)".*?>/i'
	);

	public static array $vimeo_patterns = array(
		'/<iframe.*?src="(.*?vimeo.com.*?)".*?>.*?<\/iframe>/i',
		'/<object[^>]*data="(https:\/\/player\.vimeo\.com\/video\/\d+.*?)"[^>]*>.*?<\/object>/s',
		'/<embed.*?src="(.*?vimeo.com.*?)".*?>/i'
	);

	public static array $youtube_url_patterns = array(
		'/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i',
	);

	public static array $vimeo_url_patterns = array(
		'/video\/(\d+)/',
	);

	public function __construct() {

		add_action( 'wp_ajax_pys_scan_video', array(
			$this,
			'scan_video_handler'
		) );

	}

	/**
	 * @hook wp_ajax_pys_scan_video
	 * @return void
	 */
	public function scan_video_handler() {
		// Очищаем любой предыдущий вывод
		if (ob_get_level()) {
			ob_clean();
		}

		if ( ! current_user_can( 'manage_pys' ) ) {
			wp_send_json_error( 'Unauthorized', 403 );
		}

		$nonce = $_REQUEST[ '_wpnonce' ] ?? null;
		if ( ! wp_verify_nonce( $nonce, 'pys_update_event' ) && ! wp_verify_nonce( $nonce, 'pys_save_settings' ) ) {
			wp_send_json_error( 'Invalid nonce', 403 );
		}

		if ( !empty( $_POST[ 'urls' ] ) ) {
			$videos = $this->scan_video( $_POST[ 'urls' ] );
			wp_send_json_success( $videos, 200 );
		} else {
			wp_send_json_error( 'No URLs provided', 400 );
		}
	}

	/**
	 * Scan video on pages
	 * @param $urls
	 * @return array
	 */
	public function scan_video( $urls ) {

		$videos = array();
		if ( !empty( $urls ) ) {
			foreach ( $urls as $url ) {
				$response = wp_safe_remote_get(  $url , array(
					'timeout'    => 20,
                    'sslverify' => false,
				) );

				if ( is_wp_error( $response ) ) {
					continue;
				}

				$page_html = wp_remote_retrieve_body( $response );

				if ( $page_html ) {
					foreach ( self::$youtube_patterns as $pattern ) {
						if ( preg_match_all( $pattern, $page_html, $matches, PREG_SET_ORDER ) ) {
							foreach ( $matches as $match ) {
								$youtube_url = $match[ 1 ];
								foreach ( self::$youtube_url_patterns as $url_pattern ) {

									if ( preg_match( $url_pattern, $youtube_url, $matches_url ) ) {
										$videos[] = array(
											'type'  => 'youtube',
											'url'   => $youtube_url,
											'title' => $this->get_youtube_video_title( $matches_url[ 1 ] ),
											'id'    => $matches_url[ 1 ],
										);
									}
								}
							}
						}
					}

					foreach ( self::$vimeo_patterns as $pattern ) {
						if ( preg_match_all( $pattern, $page_html, $matches, PREG_SET_ORDER ) ) {
							foreach ( $matches as $match ) {
								$vimeo_url = $match[ 1 ];
								foreach ( self::$vimeo_url_patterns as $url_pattern ) {

									if ( preg_match( $url_pattern, $vimeo_url, $matches_url ) ) {
										$videos[] = array(
											'type'  => 'vimeo',
											'url'   => $vimeo_url,
											'title' => $this->get_vimeo_video_title( $matches_url[ 1 ] ),
											'id'    => $matches_url[ 1 ],
										);
									}
								}
							}
						}
					}

					// DOM-based scanning: HTML5 <video> tags and Elementor widgets
				$dom = new \DOMDocument;
				libxml_use_internal_errors( true );
				$dom->loadHTML( $page_html );
				libxml_clear_errors();
				$finder = new \DomXPath( $dom );

				// HTML5 <video> elements
				$video_nodes = $finder->query( '//video' );
				foreach ( $video_nodes as $video_node ) {
					$src = $video_node->getAttribute( 'src' );
					$vid = $video_node->getAttribute( 'id' );
					if ( empty( $src ) ) {
						$sources = $finder->query( 'source', $video_node );
						foreach ( $sources as $source_node ) {
							$s = $source_node->getAttribute( 'src' );
							if ( !empty( $s ) ) { $src = $s; break; }
						}
					}
					if ( !empty( $src ) ) {
						$video_id = !empty( $vid ) ? $vid : $src;
						$title    = basename( parse_url( $src, PHP_URL_PATH ) ) ?: $src;
						$videos[] = array( 'type' => 'html5', 'url' => $src, 'title' => $title, 'id' => $video_id );
					}
				}

				if ( isElementorActive() ) {
					$nodes = $finder->query( "//div[contains(@class, 'elementor-widget-video')]" );
					foreach ( $nodes as $node ) {
						$data_settings = json_decode( htmlspecialchars_decode( $node->getAttribute( 'data-settings' ) ), true );

						if ( isset( $data_settings[ 'youtube_url' ] ) ) {
							foreach ( self::$youtube_url_patterns as $url_pattern ) {
								if ( preg_match( $url_pattern, $data_settings[ 'youtube_url' ], $matches_url ) ) {
									$videos[] = array(
										'type'  => 'youtube',
										'url'   => $data_settings[ 'youtube_url' ],
										'title' => $this->get_youtube_video_title( $matches_url[ 1 ] ),
										'id'    => $matches_url[ 1 ],
									);
								}
							}
						}

						if ( isset( $data_settings[ 'vimeo_url' ] ) ) {
							foreach ( self::$vimeo_url_patterns as $url_pattern ) {
								if ( preg_match( $url_pattern, $data_settings[ 'vimeo_url' ], $matches_url ) ) {
									$videos[] = array(
										'type'  => 'vimeo',
										'url'   => $data_settings[ 'vimeo_url' ],
										'title' => $this->get_vimeo_video_title( $matches_url[ 1 ] ),
										'id'    => $matches_url[ 1 ],
									);
								}
							}
						}
					}
				}
				}
			}
		}

		return $videos;
	}

	/**
	 * Get youtube video title
	 * @param $video_id
	 * @return array|string|string[]|null
	 */
	private function get_youtube_video_title( $video_id ) {
		$url = "https://www.youtube.com/watch?v={$video_id}";
		$response = wp_safe_remote_get( $url, array( 'timeout' => 5, 'sslverify' => false ) );

		if ( is_wp_error( $response ) ) {
			return null;
		}

		$html = wp_remote_retrieve_body( $response );

		if ( $html && preg_match( '/<title>(.*?)<\/title>/i', $html, $matches ) ) {
			return str_replace( ' - YouTube', '', $matches[ 1 ] );
		} else {
			return null;
		}
	}

	/**
	 * Get vimeo video title
	 * @param $video_id
	 * @return mixed|null
	 */
	private function get_vimeo_video_title( $video_id ) {
		$apiUrl = "https://vimeo.com/api/v2/video/$video_id.json";

		$response = wp_safe_remote_get( $apiUrl, array( 'timeout' => 5 ) );

		if ( is_wp_error( $response ) ) {
			return null;
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		return $data[ 0 ][ 'title' ] ?? null;
	}
}

/**
 * @return EmbeddedVideo
 */
function EmbeddedVideo() {
	return EmbeddedVideo::instance();
}

EmbeddedVideo();
