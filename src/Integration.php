<?php namespace NSRosenqvist\CMB2\DynamicMetaboxes;

use stdClass;

class Integration
{
	static $init = false;

	static function init()
	{
		if (self::$init) {
			return;
		}

		$init = true;

		// Register assets
        add_action('admin_enqueue_scripts', function() {
			wp_register_style('cmb2_dynamic_metabox', self::plugins_url('cmb2-dynamic-metaboxes', '/assets/cmb2-dynamic-metabox.css', __FILE__, 1), false, '1.0.0');
			wp_register_script('jquery.bind-first', self::plugins_url('cmb2-dynamic-metaboxes', '/assets/jquery.bind-first.js', __FILE__, 1), ['jquery'], '0.2.3');
			wp_register_script('cmb2_dynamic_metabox', self::plugins_url('cmb2-dynamic-metaboxes', '/assets/cmb2-dynamic-metabox.js', __FILE__, 1), ['jquery.bind-first'], '1.0.0');

            wp_enqueue_style('cmb2_dynamic_metabox');
			wp_enqueue_script('cmb2_dynamic_metabox');
        });

        // Save metabox states if the post variable is set
        add_action('save_post', function($post_id) {
            if (isset($_POST['metabox-states'])) {
                $states = json_decode(stripslashes($_POST['metabox-states']), true);

                if (! add_post_meta($post_id, 'metabox-states', $states, true)) {
                   update_post_meta($post_id, 'metabox-states', $states);
                }
            }
        });

        add_action('delete_post', function($post_id) {
            delete_post_meta($post_id, 'metabox-states');
        });

		// Add global js variables
        add_action('admin_head', function() {
			$post = current_post();

			if (is_valid($post)) {
				$states = post_meta('metabox-states', $post) ?? new stdClass();

	            echo PHP_EOL.'<script type="text/javascript">'.PHP_EOL;
				echo 'var metaboxStates = '.json_encode($states).';'.PHP_EOL;
	            echo '</script>'.PHP_EOL;
			}
        });

        // cmb2_show_on is the only filter we can hook into to do something
        // with our additional box properties
        add_filter('cmb2_show_on', function($show, $metabox, $cmb2) {
            // If "dynamic" is set we add the appropriate class to it
            if (isset($metabox['dynamic']) && $metabox['dynamic']) {
                // Add the dynamic-metabox so that the JS knows what metaboxes to handle
                if (! is_null($post = current_post())) {
                    add_filter('postbox_classes_'.$post->post_type.'_'.$cmb2->cmb_id, function($classes) {
                        if (! empty($classes)) {
                            if (! is_array($classes)) {
                                $classes = [$classes];
                            }

                            $classes[] = 'dynamic-metabox';
                        }
                        else {
                            $classes = ['dynamic-metabox'];
                        }

                        return $classes;
                    }, 10, 1);
                }
            }

            // Don't modify show
            return $show;
        }, 10, 3);

		// Make it retrievable by property from a Dynamis Post object
		add_filter('post_properties', function($value, $key, $post) {
			if (is_null($value)) {
				return get_metabox_state($post->getId(), $key);
			}

			return $value;
		}, 1, 3);
    }

	static function plugins_url($name, $file, $__FILE__, $depth = 0)
	{
		// Traverse up to root
		$dir = dirname($__FILE__);

		for ($i = 0; $i < $depth; $i++) {
			$dir = dirname($dir);
		}

		$root = $dir;
		$plugins = dirname($root);

		// Compare plugin directory with our found root
		if ($plugins !== WP_PLUGIN_DIR || $plugins !== WPMU_PLUGIN_DIR) {
			// Must be a symlink, guess location based on default directory name
			$resource = $name.'/'.$file;
			$url = false;

			if (file_exists(WPMU_PLUGIN_DIR.'/'.$resource)) {
				$url = WPMU_PLUGIN_URL.'/'.$resource;
			}
			elseif (file_exists(WP_PLUGIN_DIR.'/'.$resource)) {
				$url = WP_PLUGIN_URL.'/'.$resource;
			}

			if ($url) {
				if (is_ssl() && substr($url, 0, 7) !== 'https://') {
					$url = str_replace('http://', 'https://', $url);
				}

				return $url;
			}
		}

		return plugins_url($file, $root);
	}
}
