<?php
/**
 * Enhanced Elementor Widget for Real Testimonials
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Base_Page_Builder;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Assets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor widget for Real Testimonials saved templates.
 *
 * @since 4.2.0
 */
class Elementor_Widget extends Widget_Base {

	use Base_Page_Builder;

	/**
	 * Get widget name.
	 *
	 * @since 4.2.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'real_testimonial_pro_saved_template';
	}

	/**
	 * Get widget title.
	 *
	 * @since 4.2.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Real Testimonials Saved Template', 'testimonial-free' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 4.2.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'sp-rt-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 4.2.0
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 4.2.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'testimonial', 'reviews', 'slider', 'carousel' );
	}

	/**
	 * Enqueue scripts for editor preview.
	 *
	 * Taken from the Builder_Assets manifest so the handles cannot drift from what
	 * the class actually registers.
	 *
	 * @since 4.2.0
	 *
	 * @return array Script handles.
	 */
	public function get_script_depends() {
		return array_keys( Builder_Assets::manifest()['scripts'] );
	}

	/**
	 * Enqueue styles for editor preview.
	 *
	 * Taken from the Builder_Assets manifest, as with the scripts above.
	 *
	 * @since 4.2.0
	 *
	 * @return array Style handles.
	 */
	public function get_style_depends() {
		return array_keys( Builder_Assets::manifest()['styles'] );
	}

	/**
	 * Controls register.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Settings', 'testimonial-free' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'template_id',
			array(
				'label'       => __( 'Saved Template', 'testimonial-free' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '0',
				'options'     => $this->get_saved_templates_list(),
			)
		);

		// Edit This Template button.
		$this->add_control(
			'rtp_edit_template',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_edit_template_button(),
				'content_classes' => 'rtp-elementor-template-actions',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	protected function render() {
		$settings    = $this->get_settings_for_display();
		$template_id = isset( $settings['template_id'] ) ? (int) $settings['template_id'] : 0;

		if ( empty( $template_id ) || 0 === $template_id ) {
			echo '<div style="
				text-align: center;
				padding: 20px;
				border: 2px dashed #ccc;
				color: #999;
				font-size: 14px;
			">
				' . esc_html__( 'Please Select a Saved Template', 'testimonial-free' ) . '
			</div>';
			return;
		}

		// Get template post.
		$template_post = get_post( $template_id );
		if ( ! $template_post || 'publish' !== $template_post->post_status ) {
			echo '<div style="
				text-align: center;
				padding: 20px;
				border: 2px dashed #ccc;
				color: #999;
				font-size: 14px;
			">
				' . esc_html__( 'Template not found or not published.', 'testimonial-free' ) . '
			</div>';
			return;
		}

		$content = $template_post->post_content;
		if ( empty( $content ) ) {
			echo '<div style="
				text-align: center;
				padding: 20px;
				border: 2px dashed #ccc;
				color: #999;
				font-size: 14px;
			">
				' . esc_html__( 'Template content is empty.', 'testimonial-free' ) . '
			</div>';
			return;
		}

		// Print the template CSS next to the markup, on the frontend as well as in the editor.
		$this->enqueue_editor_css( $template_id );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			echo '<div class="rtp-elementor-testimonial-wrapper" data-builder-template-id="' . esc_attr( $template_id ) . '">';
			echo do_shortcode( '[sp_real_template id="' . absint( $template_id ) . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
		} else {
			// On frontend, just render the shortcode.
			echo do_shortcode( '[sp_real_template id="' . absint( $template_id ) . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Get edit template and add new template buttons HTML.
	 *
	 * @since 4.2.0
	 *
	 * @return string Buttons HTML.
	 */
	protected function get_edit_template_button() {
		$template_url = admin_url( 'edit.php?post_type=sp_real_template&page=rtp_dashboard#saved_templates' );

		$new_template_url = admin_url( 'post-new.php?post_type=sp_real_template&rtpblock_inserter=true' );

		ob_start();
		?>

		<div class="rtp-elementor-template-buttons" style="margin-top: 20px;">
			<a class="rtp-edit-template-btn" href="<?php echo esc_url( $template_url ); ?>" style="color:#fff; background-color:#3e3e40; padding:12px 24px; border-radius:4px; display:inline-block; font-size: 14px; text-decoration: none;" onmouseover="this.style.backgroundColor='#4b4b4d'" onmouseout="this.style.backgroundColor='#3e3e40'">
				<span style="display:inline-block; transform: rotate(70deg); margin-right: 4px;">✎</span>
				<span><?php echo esc_html__( 'Edit This Template', 'testimonial-free' ); ?></span>
			</a>
			<a href="<?php echo esc_url( $new_template_url ); ?>" class="rtp-add-template-btn" style="color:#fff; background-color:#2271b1; padding: 10px 23px; border-radius:4px; display:inline-block; margin-top: 15px; font-size: 14px; text-decoration: none;" onmouseover="this.style.backgroundColor='#135e96'" onmouseout="this.style.backgroundColor='#2271b1'">
				<span style="display:inline-block; font-size: 18px; margin-right: 4px;">+</span>
				<span><?php echo esc_html__( 'Add New Template', 'testimonial-free' ); ?></span>
			</a>
		</div>
		<?php
		return ob_get_clean();
	}
}
