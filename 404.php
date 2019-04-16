<?php

global $wp;

$requestedPage = array_reverse(explode('/', $wp->request))[0];

if($requestedPage !== '404'){
    if(function_exists('pll_home_url')){
        $homeUrl = pll_home_url();
    }else{
        $homeUrl = home_url('/');
    }
    header($_SERVER['SERVER_PROTOCOL'].' 301 Moved Permanently');
    header('Location: '.$homeUrl.'404/');
    exit(0);
}

header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

wp_enqueue_style('page-404', Utils::getAssetUrlWithTimestamp('/css/page-404.css'), ['bundle'], null);

get_header();
?>
<main id="page-404" class="page">
    <div class="block ">
        <div class="content">
            <h1>404</h1>
        </div>
    </div>
</main>
<?php get_footer(); ?>
