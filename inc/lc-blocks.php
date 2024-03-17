<?php
function acf_blocks()
{
    if (function_exists('acf_register_block_type')) {

        acf_register_block_type(array(
            'name'				=> 'lc_hero',
            'title'				=> __('LC Hero'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_hero.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));

        acf_register_block_type(array(
            'name'				=> 'lc_hike-nav',
            'title'				=> __('LC Hike Nav'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_hike-nav.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));

        acf_register_block_type(array(
            'name'				=> 'lc_text_image',
            'title'				=> __('LC Text/Image'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_text_image.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));

        acf_register_block_type(array(
            'name'				=> 'lc_hikes_list',
            'title'				=> __('LC Hikes List'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_hikes_list.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));
        acf_register_block_type(array(
            'name'				=> 'lc_hike_intro',
            'title'				=> __('LC Hike Intro'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_hike_intro.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));
        acf_register_block_type(array(
            'name'				=> 'lc_contact',
            'title'				=> __('LC Contact'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_contact.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));

        acf_register_block_type(array(
            'name'				=> 'lc_testimonials',
            'title'				=> __('LC Testimonial Slider'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_testimonials.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));

        acf_register_block_type(array(
            'name'				=> 'lc_latest_news',
            'title'				=> __('LC Latest News'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_latest_news.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));

        acf_register_block_type(array(
            'name'				=> 'lc_banner',
            'title'				=> __('LC Banner'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_banner.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));
        acf_register_block_type(array(
            'name'				=> 'lc_full_text',
            'title'				=> __('LC Full Text'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_full_text.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));
        acf_register_block_type(array(
            'name'				=> 'lc_faqs',
            'title'				=> __('LC FAQs'),
            'category'			=> 'layout',
            'icon'				=> 'cover-image',
            'render_template'	=> 'page-templates/blocks/lc_faqs.php',
            'mode'	=> 'edit',
            'supports' => array('mode' => false),
        ));

    }
}
add_action('acf/init', 'acf_blocks');

// Gutenburg core modifications

add_filter('register_block_type_args', 'core_image_block_type_args', 10, 3);
function core_image_block_type_args($args, $name)
{
    if ($name == 'core/paragraph') {
        $args['render_callback'] = 'modify_core_add_margin';
    }
    if ($name == 'core/heading') {
        $args['render_callback'] = 'modify_core_add_margin';
    }
    if ($name == 'core/list') {
        $args['render_callback'] = 'modify_core_add_margin';
    }

    return $args;
}

function modify_core_add_margin($attributes, $content)
{
    if (get_post_type() === 'post') {
        return $content;
    }
    if (isset($attributes['className'])) {
        $attributes['className'] .= ' mx-4';
    } else {
        $attributes['className'] = 'mx-4';
    }
    $content_with_class = '<div class="' . $attributes['className'] . '">' . $content . '</div>';
    return $content_with_class;
}
