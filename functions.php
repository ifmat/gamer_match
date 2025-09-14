<?php
// =========================
// Theme Setup
// =========================
function hodcode_theme_setup()
{
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');

    // Navigation menus
    register_nav_menus([
        'header' => 'Header Menu'
    ]);
}

add_action('after_setup_theme', 'hodcode_theme_setup');

// =========================
// Enqueue Styles & Scripts
// =========================
function hodcode_enqueue_assets()
{
    wp_enqueue_style('hodcode-style', get_stylesheet_uri());
    wp_enqueue_style('hodcode-webfont', get_template_directory_uri() . '/assets/fontiran.css');
    wp_enqueue_script('tailwind', 'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4', [], null, true);
}

add_action('wp_enqueue_scripts', 'hodcode_enqueue_assets');

// =========================
// Custom Post Types
// =========================
function create_offer_post_type()
{
    register_post_type('offer', array(
        'labels' => array(
            'name' => 'پیشنهادها',
            'singular_name' => 'پیشنهاد',
        ),
        'public' => false,
        'show_ui' => true,
        'supports' => array('title', 'editor', 'author'),
    ));
}

add_action('init', 'create_offer_post_type');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_offer'])) {
    $request_id = intval($_POST['request_id']);
    $price = intval($_POST['offer_price']);
    $days = intval($_POST['offer_days']);
    $message = sanitize_textarea_field($_POST['offer_message']);

    $offer_post = array(
        'post_title' => 'پیشنهاد برای: ' . get_the_title($request_id),
        'post_content' => $message,
        'post_status' => 'publish',
        'post_type' => 'offer',
        'post_author' => get_current_user_id(),
    );

    $offer_id = wp_insert_post($offer_post);

    if ($offer_id) {
        update_post_meta($offer_id, 'offer_price', $price);
        update_post_meta($offer_id, 'offer_days', $days);
        update_post_meta($offer_id, 'request_id', $request_id);

        wp_redirect(get_permalink($request_id)); // بازگشت به صفحه درخواست
        exit;
    }
}

///       // =========================
// Custom Post Types
// =========================
add_action('init', function () {
    // Client Requests
    register_post_type('client_request', [
        'label' => 'Client Requests',
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'author', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);

    // Gamer Requests
    register_post_type('gamer_request', [
        'label' => 'Gamer Requests',
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'author', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);

    // Products
    register_post_type('product', [
        'public' => true,
        'label' => 'Products',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'show_in_rest' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'products'],
    ]);

    register_taxonomy('product_cat', ['product'], [
        'hierarchical' => true,
        'labels' => [
            'name' => 'Product Categories',
            'singular_name' => 'Product Category'
        ],
        'rewrite' => ['slug' => 'product-category'],
        'show_in_rest' => true,
    ]);
});

// =========================
// Custom Fields Helper
// =========================
function hodcode_add_custom_field($fieldName, $postType, $title)
{
    // Meta box
    add_action('add_meta_boxes', function () use ($fieldName, $postType, $title) {
        add_meta_box(
            $fieldName . '_box',
            $title,
            function ($post) use ($fieldName) {
                $value = get_post_meta($post->ID, $fieldName, true);
                wp_nonce_field($fieldName . '_nonce', $fieldName . '_nonce_field');
                echo '<input type="text" style="width:100%" name="' . esc_attr($fieldName) . '" value="' . esc_attr($value) . '">';
            },
            $postType,
            'normal',
            'default'
        );
    });

    // Save meta
    add_action('save_post', function ($post_id) use ($fieldName) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!isset($_POST[$fieldName . '_nonce_field'])) return;
        if (!wp_verify_nonce($_POST[$fieldName . '_nonce_field'], $fieldName . '_nonce')) return;
        if (!current_user_can('edit_post', $post_id)) return;

        if (isset($_POST[$fieldName])) {
            update_post_meta($post_id, $fieldName, sanitize_text_field($_POST[$fieldName]));
        } else {
            delete_post_meta($post_id, $fieldName);
        }
    });
}

// Add product custom fields
hodcode_add_custom_field("price", "product", "Price (Final)");
hodcode_add_custom_field("old_price", "product", "Price (Before)");
hodcode_add_custom_field("features", "product", "Product Features (comma separated)");

// =========================
// WooCommerce Support
// =========================
add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 350,
        'single_image_width' => 500
    ]);
});

// =========================
// Social Links in Customizer
// =========================
add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_section('hodcode_social_links', [
        'title' => __('Social Media Links', 'hodcode'),
        'priority' => 30,
    ]);

    foreach (['facebook', 'twitter', 'linkedin'] as $platform) {
        $wp_customize->add_setting('hodcode_' . $platform, [
            'default' => '',
            'transport' => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control('hodcode_' . $platform, [
            'label' => ucfirst($platform) . ' URL',
            'section' => 'hodcode_social_links',
            'type' => 'url',
        ]);
    }
});
