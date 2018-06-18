<?php namespace NSRosenqvist\CMB2\DynamicMetaboxes\Providers;

use NSRosenqvist\CMB2\DynamicMetaboxes\Integration;

class DynamisProvider extends \Dynamis\ServiceProvider
{
    public function boot()
    {
        // Add integration
        Integration::init();

        // Remove plugin from WP Plugins list if we enable it through a provider
        add_filter('all_plugins', function($plugins) {
            foreach ($plugins as $key => $details) {
                if ($details['Name'] == 'CMB2 Dynamic Metaboxes') {
                    unset($plugins[$key]);
                    break;
                }
            }

            return $plugins;
        }, 10, 1);
    }
}
