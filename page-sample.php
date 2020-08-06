<?php
/*
Template Name: Sample
*/

get_header();
the_post();
$metaData = Utils::getNormalizedMetaData($post->ID);
?>
<main id="page-sample" class="page">
	<section class="section main-section">
		<div class="section-content">
            <?php
            the_content();
            ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
