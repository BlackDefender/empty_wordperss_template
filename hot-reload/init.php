<?php

if( $_SERVER['SERVER_NAME'] === 'localhost' || filter_var($_SERVER['SERVER_NAME'], FILTER_VALIDATE_IP)){

    function includeHotReloadScripts()
    {
        wp_enqueue_script('hot-reload', Utils::getAssetUrlWithTimestamp('/hot-reload/hot-reload.js'), [], null, true);
    }
    add_action('wp_enqueue_scripts', 'includeHotReloadScripts');

    function includeHotReloadInlineScript()
    {
        echo '
            <script>
            window.addEventListener("load", ()=>{
                hotReload("' . get_template_directory_uri() . '/hot-reload/watcher.php' . '", "' . str_replace('\\', '/', TEMPLATEPATH) . '");
            });
            </script>';
    }
    add_action( 'wp_footer', 'includeHotReloadInlineScript' );

}