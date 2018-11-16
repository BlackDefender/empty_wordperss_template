<?php

get_header();

?>
<div id="front-page" class="page">
	<div class="block some-block">
		<div class="content">
		
		<form action="<?= $templateUri; ?>/ajax/sendmail.php" method="post">
			<?php wp_nonce_field(Utils::getNonceActionName(), 'csrf-token'); ?>
		</form>
		
		</div>
	</div>
	<div class="block another-block">
		<div class="content">
		</div>
	</div>
</div>
<?php get_footer(); ?>