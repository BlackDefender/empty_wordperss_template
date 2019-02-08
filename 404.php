<?php

global $wp;

$requestedPage = array_reverse(explode('/', $wp->request))[0];

if($requestedPage !== '404'){
    if(function_exists('pll_home_url')){
        $homeUrl = pll_home_url();
    }else{
        $homeUrl = home_url('/');
    }
    header($_SERVER['SERVER_PROTOCOL'].' 302 Moved Temporarily');
    header('Location: '.$homeUrl.'404/');
    exit(0);
}

header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
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