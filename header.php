<?php

global $contactsData, $templateUri;

?>
<!doctype html>
<html lang="">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <title><?= wp_get_document_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" type="image/x-icon" href="<?= $templateUri; ?>/images/favicon.ico" />
    <link rel="icon" type="image/png" href="<?= $templateUri; ?>/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?= $templateUri; ?>/images/favicon-16x16.png" sizes="16x16" />

    <base href="<?= home_url('/'); ?>">

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