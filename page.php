<?php
get_header();
the_post();
?>
<div class="page">
	<div class="block some-block">
		<div class="content">
            <?php
            the_content();
            ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>