<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package lc-mjoa2024
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

?>
<footer class="footer">
    <div class="row p-4 pt-2 g-4 mx-0">
        <div class="col-lg-3 text-center text-lg-start">
            <img src="<?=get_stylesheet_directory_uri()?>/img/mjoa-wordmark--wo.png"
                alt="" width=180 height=55>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="h4"></div>
            <?php wp_nav_menu(array('theme_location' => 'footer_menu1')); ?>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="h4"></div>
            <?php wp_nav_menu(array('theme_location' => 'footer_menu2')); ?>
        </div>
        <div class="col-md-4 col-lg-3">
            <ul class="fa-ul">
                <li><span class="fa-li"><i class="fa-solid fa-envelope text-light"></i></span>
                    <?=do_shortcode('[contact_email]')?>
                </li>
                <li><span class="fa-li"><i class="fa-solid fa-phone text-light"></i></span>
                    <strong>Mike:</strong>
                    <?=do_shortcode('[contact_phone_mike]')?>
                </li>
                <li><span class="fa-li"><i class="fa-solid fa-phone text-light"></i></span>
                    <strong>Jenn:</strong>
                    <?=do_shortcode('[contact_phone_jenn]')?>
                </li>
            </ul>
            <div class="social-icons">
                <?=do_shortcode('[social_icons]')?>
            </div>
        </div>
    </div>
    <div class="colophon px-4">
        <div>&copy; <?=date('Y')?>
            MJ Outdoor Adventures</div>
        <div>
            <a href="/privacy-policy/">Privacy</a> &amp; <a href="/cookie-policy/">Cookie Policy</a>
            |
            <span>Site by <a href="https://www.lamcat.co.uk/">Lamcat</a></span>
        </div>
    </div>
</footer>
<?php wp_footer();
if (get_field('gtm_property', 'options')) {
    ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe
        src="https://www.googletagmanager.com/ns.html?id=<?=get_field('gtm_property', 'options')?>"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
}
?>
</body>

</html>