<?php
/*
Template Name: Dashboard
*/

if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

$current_user_id = get_current_user_id();

// ذخیره نقش از URL
if (isset($_GET['role'])) {
    $role = sanitize_text_field($_GET['role']);
    update_user_meta($current_user_id, 'user_role_type', $role);
    wp_redirect(remove_query_arg('role'));
    exit;
}

$saved_role = get_user_meta($current_user_id, 'user_role_type', true);

get_header();

// اگر نقش انتخاب نشده
if (!$saved_role) :
    ?>
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 text-white w-full max-w-md shadow-xl text-center">
            <h2 class="text-2xl font-bold mb-6">نقش خود را انتخاب کنید</h2>
            <div class="flex justify-center gap-6">
                <a href="<?php echo add_query_arg('role', 'gamer'); ?>"
                   class="px-6 py-3 rounded-full bg-cyan-500/80 hover:bg-cyan-600 text-white font-bold shadow-lg transition">
                    🎮 گیمر
                </a>
                <a href="<?php echo add_query_arg('role', 'client'); ?>"
                   class="px-6 py-3 rounded-full bg-pink-500/80 hover:bg-pink-600 text-white font-bold shadow-lg transition">
                    📢 درخواست‌کننده
                </a>
            </div>
        </div>
    </div>
    <?php
    get_footer();
    exit;
endif;

// تعداد کل آگهی‌های کاربر (client_request و gamer_request)
$all_requests_query = new WP_Query([
    'post_type'      => ['client_request', 'gamer_request'],
    'author'         => $current_user_id,
    'posts_per_page' => -1,
    'post_status'    => ['publish', 'draft', 'pending'],
]);
$total_requests = $all_requests_query->found_posts;

// کوئری نمایش درخواست‌ها و پیشنهادها
$user_requests = new WP_Query([
    'post_type'      => ['client_request', 'gamer_request'],
    'author'         => $current_user_id,
    'posts_per_page' => -1,
    'post_status'    => 'publish',
]);

$title = $saved_role === 'gamer' ? "🎮 داشبورد گیمر" : "📢 داشبورد درخواست‌کننده";
$theme_classes = $saved_role === 'gamer' ? "from-cyan-700/40 to-purple-800/40" : "from-pink-700/40 to-red-800/40";
$accent = $saved_role === 'gamer' ? "text-cyan-400" : "text-pink-400";
?>

<div class="min-h-screen p-4 flex justify-center">
    <div class="max-w-6xl w-full">
        <div class="<?php echo $theme_classes; ?> backdrop-blur-xl border border-white/20 rounded-3xl p-10 shadow-2xl">

            <h2 class="text-3xl font-bold text-center mb-8"><?php echo $title; ?></h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php if ($saved_role === 'client') : ?>
                    <!-- ثبت درخواست جدید -->
                    <div class="bg-black/30 backdrop-blur-sm border border-white/10 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center flex flex-col justify-center">
                        <h3 class="text-xl font-bold mb-4">➕ ثبت درخواست جدید</h3>
                        <a href="<?php echo home_url('/request-form'); ?>"
                           class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-white font-semibold transition">ثبت درخواست</a>
                    </div>

                    <!-- تعداد درخواست‌ها -->
                    <div class="bg-black/30 backdrop-blur-sm border border-white/10 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center">
                        <h3 class="text-xl font-bold mb-4">📄 تعداد درخواست‌ها</h3>
                        <p class="text-3xl font-extrabold mb-2"><?php echo $total_requests; ?></p>
                        <p class="text-gray-300">آگهی‌های ثبت شده</p>
                    </div>

                    <!-- نمایش درخواست‌ها و پیشنهادها -->
                    <?php if ($user_requests->have_posts()) : ?>
                        <div class="bg-black/30 backdrop-blur-sm border border-white/10 p-6 rounded-xl shadow-md hover:shadow-lg transition max-h-[500px] overflow-y-auto">
                            <h3 class="text-xl font-bold mb-4">✏️ درخواست‌ها و پیشنهادها</h3>

                            <?php while ($user_requests->have_posts()) : $user_requests->the_post();
                                $request_id = get_the_ID();

                                $offers_query = new WP_Query([
                                    'post_type'   => 'offer',
                                    'meta_key'    => 'request_id',
                                    'meta_value'  => $request_id,
                                    'post_status' => 'publish',
                                    'orderby'     => 'date',
                                    'order'       => 'DESC',
                                ]);
                                $num_offers = $offers_query->found_posts;
                                ?>
                                <div class="mb-4 p-3 bg-gray-800 rounded">
                                    <a href="<?php echo get_edit_post_link($request_id); ?>"
                                       class="<?php echo $accent; ?> hover:underline block text-lg font-semibold mb-1"><?php the_title(); ?></a>
                                    <p class="text-gray-300 mb-1"><?php the_excerpt(); ?></p>
                                    <p class="text-gray-400 mb-2">تعداد پیشنهادها: <?php echo $num_offers; ?></p>

                                    <?php if ($offers_query->have_posts()) : ?>
                                        <div class="ml-2 p-2 bg-gray-700 rounded space-y-2 max-h-64 overflow-y-auto">
                                            <h4 class="text-white font-semibold mb-1">پیشنهادهای ارسال شده:</h4>
                                            <?php while ($offers_query->have_posts()) : $offers_query->the_post();
                                                $offer_price = get_post_meta(get_the_ID(), 'offer_price', true);
                                                $offer_days = get_post_meta(get_the_ID(), 'offer_days', true);
                                                $offer_message = get_the_content();
                                                $offer_author = get_the_author();
                                                ?>
                                                <div class="p-2 bg-gray-800 rounded">
                                                    <p class="text-gray-200 font-bold">گیمر: <?php echo esc_html($offer_author); ?></p>
                                                    <p class="text-gray-300">قیمت پیشنهادی: <?php echo esc_html($offer_price); ?> تومان</p>
                                                    <p class="text-gray-300">روز: <?php echo esc_html($offer_days); ?></p>
                                                    <p class="text-gray-300">توضیحات: <?php echo esc_html($offer_message); ?></p>
                                                </div>
                                            <?php endwhile; wp_reset_postdata(); ?>
                                        </div>
                                    <?php else : ?>
                                        <p class="text-gray-400 mt-1">هنوز هیچ پیشنهادی برای این درخواست ارسال نشده است.</p>
                                    <?php endif; ?>

                                </div>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>

                <!-- داشبورد گیمر -->
                <?php if ($saved_role === 'gamer') : ?>
                    <div class="bg-black/30 backdrop-blur-sm border border-white/10 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center">
                        <h3 class="text-xl font-bold mb-4">✅ درخواست‌های قبول شده</h3>
                        <p class="text-gray-300">در این قسمت درخواست‌هایی که قبول شده‌اند نمایش داده می‌شوند.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
