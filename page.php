<?php
get_header();
the_post();
?>
<main class="page">
	<div class="block some-block">
		<div class="content">
            <?php
            the_content();
            ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>