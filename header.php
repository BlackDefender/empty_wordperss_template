<?php

global $contactsData, $templateUri;

?>
<!doctype html>
<html lang="">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <title><?= wp_get_document_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?= $templateUri; ?>/images/favicons/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?= $templateUri; ?>/images/favicons/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?= $templateUri; ?>/images/favicons/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?= $templateUri; ?>/images/favicons/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?= $templateUri; ?>/images/favicons/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?= $templateUri; ?>/images/favicons/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?= $templateUri; ?>/images/favicons/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?= $templateUri; ?>/images/favicons/apple-touch-icon-152x152.png" />
    <link rel="icon" type="image/png" href="<?= $templateUri; ?>/images/favicons/favicon-196x196.png" sizes="196x196" />
    <link rel="icon" type="image/png" href="<?= $templateUri; ?>/images/favicons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="<?= $templateUri; ?>/images/favicons/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?= $templateUri; ?>/images/favicons/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="<?= $templateUri; ?>/images/favicons/favicon-128.png" sizes="128x128" />
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="<?= $templateUri; ?>/images/favicons/mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="<?= $templateUri; ?>/images/favicons/mstile-70x70.png" />
    <meta name="msapplication-square150x150logo" content="<?= $templateUri; ?>/images/favicons/mstile-150x150.png" />
    <meta name="msapplication-wide310x150logo" content="<?= $templateUri; ?>/images/favicons/mstile-310x150.png" />
    <meta name="msapplication-square310x310logo" content="<?= $templateUri; ?>/images/favicons/mstile-310x310.png" />

    <base href="<?= home_url('/'); ?>">

    <script src="<?= $templateUri; ?>/js/min/lazysizes.min.js" async></script>

    <meta name="theme-color" content="#ffffff"/>
    <link rel="manifest" href="<?= Utils::getAssetUrlWithTimestamp('manifest.json'); ?>">
	<?php wp_head(); ?>
</head>
<body>
<div class="header-container">
	<header>
	    <a href="<?= home_url('/'); ?>" class="logo" aria-label="Logo"></a>
        <?php
        wp_nav_menu(array('theme_location' => 'main-menu', 'menu_class' => 'main-menu', 'container' => false));
        ?>
	</header>
</div>
