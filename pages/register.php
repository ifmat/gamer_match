<?php
/* Template Name: Register */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // امنیت: بررسی nonce
    if ( ! isset($_POST['register_nonce']) || ! wp_verify_nonce($_POST['register_nonce'], 'register_action') ) {
        $error = 'فرم معتبر نیست. لطفاً دوباره تلاش کنید.';
    } else {
        // گرفتن ورودی‌ها (با trim)
        $raw_username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password     = isset($_POST['password']) ? $_POST['password'] : '';
        $email        = isset($_POST['email']) ? trim($_POST['email']) : '';

        // بررسی‌های پایه
        if ( empty($email) || ! is_email($email) ) {
            $error = 'لطفاً یک ایمیل معتبر وارد کنید.';
        } elseif ( empty($password) || strlen( $password ) < 6 ) {
            $error = 'رمز عبور باید حداقل ۶ کاراکتر باشد.';
        } else {

            // تلاش برای نرمال‌سازی نام کاربری (ممکنه خالی برگرده اگر کاربر حروف غیرلاتین وارد کرده)
            $username = sanitize_user( $raw_username, true );

            // fallback: اگر sanitize_user خالی شد، از پیشوند ایمیل استفاده کن
            if ( empty( $username ) ) {
                $parts = explode( '@', $email );
                $prefix = isset($parts[0]) ? $parts[0] : '';
                // فقط کاراکترهای لاتین / اعداد / . _ - نگه دار
                $prefix = preg_replace('/[^A-Za-z0-9._-]/', '', $prefix);
                $username = sanitize_user( $prefix, true );

                // اگر باز هم خالی بود، یک نام کاربری تصادفی بساز
                if ( empty( $username ) ) {
                    $username = 'user' . wp_generate_password(6, false, false);
                }
            }

            // اگر نام کاربری تکراریه، عدد اضافه کن تا یکتا بشه
            $base_username = $username;
            $i = 1;
            while ( username_exists( $username ) ) {
                $username = $base_username . $i;
                $i++;
            }

            // بررسی ایمیل از قبل ثبت شده
            if ( email_exists( $email ) ) {
                $error = 'این ایمیل قبلاً ثبت شده است.';
            } else {
                // ایجاد کاربر
                $user_id = wp_create_user( $username, $password, $email );

                if ( ! is_wp_error( $user_id ) ) {
                    // در صورت موفقیت، هدایت به صفحه ورود (یا هر صفحهٔ دلخواه)
                    wp_safe_redirect( home_url( '/login' ) );
                    exit;
                } else {
                    // پیام خطای وردپرس را جمع‌آوری کن تا نشان دهی
                    $errs = $user_id->get_error_messages();
                    $error = implode(' | ', $errs);
                }
            }
        }
    }
}

get_header();
?>

<div class="min-h-screen flex items-center justify-center from-gray-900 via-gray-800 to-black p-6">

    <!-- فرم شیشه‌ای -->
    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 text-white max-w-md w-full shadow-xl">
        <h2 class="text-2xl font-bold mb-6 text-center">ثبت‌نام</h2>

        <?php if (isset($error) && $error): ?>
            <p class="text-red-400 mb-4 text-center"><?php echo esc_html($error); ?></p>
        <?php endif; ?>

        <form method="post" class="space-y-4" novalidate>
            <?php wp_nonce_field('register_action','register_nonce'); ?>

            <input type="text" name="username" placeholder="نام کاربری (لاتین یا خالی برای استفاده از ایمیل)"
                   value="<?php echo isset($_POST['username']) ? esc_attr($_POST['username']) : ''; ?>"
                   required
                   class="w-full p-3 rounded-lg bg-black/30 border border-white/20 placeholder-gray-300 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400">

            <input type="email" name="email" placeholder="ایمیل" required
                   value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>"
                   class="w-full p-3 rounded-lg bg-black/30 border border-white/20 placeholder-gray-300 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400">

            <input type="password" name="password" placeholder="رمز عبور" required
                   class="w-full p-3 rounded-lg bg-black/30 border border-white/20 placeholder-gray-300 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400">

            <button type="submit"
                    class="w-full py-3 rounded-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold shadow-lg hover:from-purple-700 hover:to-pink-600 transform hover:scale-105 transition">
                ثبت‌نام
            </button>
        </form>
    </div>
</div>

<?php get_footer(); ?>
