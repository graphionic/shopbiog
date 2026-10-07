<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../wp-load.php';

$ga_opt = get_option('pys_ga');
echo "Current GA options:" . PHP_EOL;
print_r($ga_opt);

// Test updating pys_ga tracking_id to ['G-VP3TGE9JK1']
if (empty($ga_opt['tracking_id'])) {
    $ga_opt['tracking_id'] = ['G-VP3TGE9JK1'];
    update_option('pys_ga', $ga_opt);
    echo "Updated pys_ga tracking_id to G-VP3TGE9JK1" . PHP_EOL;
} else {
    echo "tracking_id already present: " . implode(', ', $ga_opt['tracking_id']) . PHP_EOL;
}
