<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
?>
<main id="main" class="padding-top">
    <?php
$bg = wp_get_attachment_image_url(get_field('404_hero', 'options'), 'full');
?>
    <section class="hero hero--hills">
        <img src="<?=$bg?>" class="hero__bg">
    </section>
    <h1 class="hero__title mb-4">
        404 - You are lost!
    </h1>
    <div class="text-center">
        <div class="mb-4">We can't seem to find the page you're looking for.</div>

        <a class="btn btn-primary mb-4" href="/">Return to Homepage</a>
    </div>
</main>
<?php
get_footer();
?>