<?php
/*
Template Name: Request Form
*/

if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize_text_field($_POST['title']);
    $description = sanitize_textarea_field($_POST['description']);
    $price = sanitize_text_field($_POST['price']); // اضافه شد
    $days = sanitize_text_field($_POST['days']);   // اضافه شد

    $new_request = array(
        'post_title'   => $title,
        'post_content' => $description,
        'post_status'  => 'publish',
        'post_type'    => 'gamer_request',
        'post_author'  => get_current_user_id(),
    );

    $post_id = wp_insert_post($new_request);

    if ($post_id) {
        // ذخیره فیلدهای سفارشی
        update_post_meta($post_id, 'price', $price);
        update_post_meta($post_id, 'days', $days);

        wp_redirect(home_url('/dashboard'));
        exit;
    } else {
        $error = 'ثبت درخواست ناموفق بود.';
    }
}

get_header();
?>

<div class="bg-gray-900 p-6 rounded-lg text-white max-w-md mx-auto">
    <h2 class="text-xl mb-4">ثبت درخواست بازی</h2>
    <?php if (isset($error)) echo "<p class='text-red-400 mb-2'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="title" placeholder="عنوان درخواست" required class="w-full mb-3 p-2 rounded bg-gray-800 text-white">
        <textarea name="description" placeholder="توضیحات" required class="w-full mb-3 p-2 rounded bg-gray-800 text-white"></textarea>
        <input type="text" name="price" placeholder="قیمت" required class="w-full mb-3 p-2 rounded bg-gray-800 text-white">
        <input type="text" name="days" placeholder="تعداد روزها" required class="w-full mb-3 p-2 rounded bg-gray-800 text-white">
        <button type="submit" class="btn w-full">ثبت درخواست</button>
    </form>
</div>

<?php get_footer(); ?>
