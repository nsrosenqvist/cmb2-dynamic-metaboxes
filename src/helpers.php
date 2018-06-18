<?php

if (! function_exists('get_metabox_state')) {
    function get_metabox_state($post, $id)
    {
        if ($post instanceof \WP_Post) {
            $post = $post->ID;
        }

        $states = get_post_meta($post, 'metabox-states', true);

        // If the key refers to a dynamic metabox then we return it's status
        if (isset($states[$id])) {
            return $states[$id];
        }

        return null;
    }
}
