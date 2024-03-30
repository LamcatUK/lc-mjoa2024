<section class="contact py-5">
    <div class="container-xl">
        <div class="row g-4 px-2">
            <div class="col-md-6 mb-4" data-aos="fade">
                <h2 class="headline">Contact Us</h2>
                <div class="mb-4">
                    We'd love to hear from you! Email, call us or use the form <span
                        class="d-none d-md-inline">opposite</span><span class="d-md-none">below</span>.
                </div>
                <ul class="fa-ul mb-4">
                    <li class="mb-3"><span class="fa-li"><i class="fa-solid fa-envelope"></i></span>
                        <?=do_shortcode('[contact_email]')?>
                    </li>
                    <li class="mb-3"><span class="fa-li"><i class="fa-solid fa-phone"></i></span>
                        <strong>Mike:</strong>
                        <?=do_shortcode('[contact_phone_mike]')?>
                    </li>
                    <li class="mb-3"><span class="fa-li"><i class="fa-solid fa-phone"></i></span>
                        <strong>Jenn:</strong>
                        <?=do_shortcode('[contact_phone_jenn]')?>
                    </li>
                </ul>
                <h3 class="headline">Connect</h3>
                <div class="mb-4">Find us on social media</div>
                <div class="social-icons">
                    <?=do_shortcode('[social_icons]')?>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade">
                <h3 class="headline">Get in touch</h3>
                <?=do_shortcode('[contact-form-7 id="39258bf" title="Contact form 1"]')?>
            </div>
        </div>
    </div>
</section>