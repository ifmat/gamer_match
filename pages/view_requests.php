<?php
/*
Template Name: View Requests
*/

get_header();
?>

<div class="min-h-screen p-6 max-w-6xl mx-auto">

    <h2 class="text-3xl font-bold text-white mb-8 text-center">📋 لیست درخواست‌ها</h2>

    <?php
    $args = array(
        'post_type' => array('client_request', 'gamer_request'),
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $requests = new WP_Query($args);

    // پردازش ارسال پیشنهاد
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_offer']) && is_user_logged_in()) {
        $request_id = intval($_POST['request_id']);
        $price = sanitize_text_field($_POST['offer_price']);
        $days = sanitize_text_field($_POST['offer_days']);
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
            echo '<p class="text-green-400 mb-4">پیشنهاد شما با موفقیت ارسال شد!</p>';
        } else {
            echo '<p class="text-red-400 mb-4">ارسال پیشنهاد ناموفق بود.</p>';
        }
    }
    ?>

    <?php if ($requests->have_posts()) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($requests->have_posts()) : $requests->the_post(); ?>
                <?php $request_id = get_the_ID(); ?>
                <div class="request-card bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition">
                    <h3 class="text-xl font-bold mb-2 text-white"><?php the_title(); ?></h3>
                    <p class="mb-3 text-gray-300"><?php the_excerpt(); ?></p>

                    <p class="text-sm text-gray-400">ثبت‌شده توسط: <?php the_author(); ?></p>
                    <p class="text-sm text-gray-400">نوع درخواست: <?php echo get_post_type() === 'gamer_request' ? 'گیمر' : 'درخواست‌کننده'; ?></p>

                    <?php
                    $day = get_post_meta($request_id, 'days', true);
                    $price = get_post_meta($request_id, 'price', true);
                    $prerequisite = get_post_meta($request_id, 'request_prerequisite', true);
                    $image_id = get_post_meta($request_id, 'request_image', true);
                    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
                    ?>

                    <?php if($day) : ?><p class="text-sm text-gray-300">روز: <?php echo esc_html($day); ?></p><?php endif; ?>
                    <?php if($price) : ?><p class="text-sm text-gray-300">قیمت: <?php echo esc_html($price); ?> تومان</p><?php endif; ?>
                    <?php if($prerequisite) : ?><p class="text-sm text-gray-300">پیش‌نیاز: <?php echo esc_html($prerequisite); ?></p><?php endif; ?>
                    <?php if($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="عکس درخواست" class="mt-2 rounded-md max-h-48 object-cover">
                    <?php endif; ?>

                    <a href="<?php echo get_permalink(); ?>" class="text-cyan-400 hover:underline mt-2 inline-block">مشاهده جزئیات</a>

                    <?php
                    $user_role = get_user_meta(get_current_user_id(), 'user_role_type', true);
                    // فرم پیشنهاد برای همه درخواست‌ها اگر کاربر گیمر است
                    if (is_user_logged_in() && $user_role === 'gamer') :
                        $form_id = "offer-form-{$request_id}";
                        ?>
                        <button type="button" onclick="document.getElementById('<?php echo $form_id; ?>').classList.toggle('hidden');"
                                class="mt-3 w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 rounded">
                            ثبت پیشنهاد
                        </button>

                        <div id="<?php echo $form_id; ?>" class="hidden mt-3 p-4 bg-gray-700 rounded max-h-96 overflow-y-auto">
                            <form method="post">
                                <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                <input type="text" name="offer_price" placeholder="قیمت پیشنهادی" required class="w-full mb-2 p-2 rounded bg-gray-800 text-white">
                                <input type="text" name="offer_days" placeholder="تعداد روزها" required class="w-full mb-2 p-2 rounded bg-gray-800 text-white">
                                <textarea name="offer_message" placeholder="توضیحات" class="w-full mb-2 p-2 rounded bg-gray-800 text-white"></textarea>
                                <button type="submit" name="submit_offer" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded w-full">ارسال پیشنهاد</button>
                            </form>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php else : ?>
        <p class="text-center text-white text-lg">هیچ درخواستی ثبت نشده است.</p>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
