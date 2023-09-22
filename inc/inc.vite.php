<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// enqueue hook
add_action('wp_enqueue_scripts', function () {

    if ($_ENV['IS_VITE_DEVELOPMENT'] === true) {

        // insert hmr into head for live reload
        function vite_head_module_hook()
        {
            echo '<script type="module" crossorigin src="' . $_ENV['VITE_SERVER'] . $_ENV['VITE_ENTRY_POINT'] . '"></script>';
        }
        add_action('wp_head', 'vite_head_module_hook');
    } else {

        // production version, 'npm run build' must be executed in order to generate assets
        // ----------

        // read manifest.json to figure out what to enqueue
        $manifest = json_decode(file_get_contents(get_template_directory() . '/' . $_ENV['DIST_DIR'] . '/manifest.json'), true);

        // is ok
        if (is_array($manifest)) {

            // get first key, by default is 'main.js' but it can change
            $manifest_key = array_keys($manifest);
            if (isset($manifest_key[0])) {

                // enqueue CSS files
                foreach (@$manifest[$manifest_key[0]]['css'] as $css_file) {
                    wp_enqueue_style('main', get_template_directory_uri() . '/' . $_ENV['DIST_DIR'] . '/' . $css_file);
                }

                // enqueue main JS file
                $js_file = @$manifest[$manifest_key[0]]['file'];
                if (!empty($js_file)) {
                    wp_enqueue_script('main', get_template_directory_uri() . '/' . $_ENV['DIST_DIR'] . '/' . $js_file, $_ENV['JS_DEPENDENCY'], '', $_ENV['JS_LOAD_IN_FOOTER']);
                }
            }
        }
    }
});
