<?php

global $contactsData, $templateUri;

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=<?php bloginfo('charset'); ?>">
    <title><?= wp_get_document_title(); ?></title>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $templateUri; ?>/images/favicon.ico" />
    <link rel="icon" type="image/png" href="<?= $templateUri; ?>/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?= $templateUri; ?>/images/favicon-16x16.png" sizes="16x16" />
	<base href="<?= home_url('/'); ?>">
	
	<meta property="og:image" content="<?= $templateUri; ?>/images/logo.png" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="00" />
    <meta property="og:image:height" content="00" />
    
	<?php wp_head(); ?>
</head>
<body>
<div class="header-container">
	<header>
	    <a href="<?= home_url('/'); ?>" class="logo"></a>
        <?php
        wp_nav_menu(array('theme_location' => 'main-menu', 'menu_class' => 'main-menu', 'container' => false));
        ?>
	</header>
</div>