<?php
wp_enqueue_script('front-page', Utils::getAssetUrlWithTimestamp('/js/min/front-page.js'), ['bundle'], null, true);
wp_enqueue_style('front-page', Utils::getAssetUrlWithTimestamp('/css/front-page.css'), ['bundle'], null);

get_header();

$metaData = Utils::getNormalizedMetaData();
?>
<main id="front-page" class="page">
    <section class="section main-section">
        <div class="section-content">

            <form action="<?= $templateUri; ?>/ajax/sendmail.php" method="post">
                <?php wp_nonce_field(Utils::getNonceActionName(), 'csrf-token'); ?>
            </form>

        </div>
    </section>
</main>
<?php get_footer(); ?>
