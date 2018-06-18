<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
Plugin Name: CMB2 Dynamic Metaboxes
Description: Make metaboxes toggleable on and off
Version: 1.0.0
Author: Niklas Rosenqvist
Author URI: https://www.nsrosenqvist.com/
*/

if (! class_exists('CMB2_DynamicMetaboxes')) {
    class CMB2_DynamicMetaboxes
    {
        static function init()
        {
            // Include files
            if (! class_exists('CMB2')) {
                return;
            }

            // Include files
            require_once __DIR__.'/src/Integration.php';
            require_once __DIR__.'/src/helpers.php';

            // Initialize plugin
            \NSRosenqvist\CMB2\DynamisMetaboxes\Integration::init();
        }
    }
}
add_action('init', [CMB2_DynamicMetaboxes::class, 'init']);
