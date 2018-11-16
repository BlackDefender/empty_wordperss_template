<?php
/*
Template Name: Sample
*/

get_header();
the_post();
$metaData = Utils::getNormalizedMetaData($post->ID);
?>
<main id="page-sample" class="page">
	<div class="block main-block">
		<div class="content">
            <?php
            the_content();
            ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>