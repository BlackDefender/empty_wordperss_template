<?php
get_header();
the_post();
?>
<main class="page">
	<section class="section main-section">
		<div class="section-content">
            <?php
            the_content();
            ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
