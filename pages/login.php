<?php
/*
Template Name: Login
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = sanitize_text_field($_POST['username']);
    $password    = $_POST['password'];

    // اگر ورودی ایمیل بود، نام کاربری مربوطه رو بگیر
    if (is_email($login_input)) {
        $user_obj = get_user_by('email', $login_input);
        if ($user_obj) {
            $login_input = $user_obj->user_login;
        }
    }

    $creds = array(
        'user_login'    => $login_input,
        'user_password' => $password,
        'remember'      => true
    );

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        $error = $user->get_error_message();
    } else {
        wp_redirect(home_url('/dashboard'));
        exit;
    }
}

get_header();
?>

<div class="min-h-screen flex items-center justify-center from-gray-900 via-gray-800 to-black p-6">
    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 text-white max-w-md w-full shadow-xl">
        <h2 class="text-2xl font-bold mb-6 text-center">ورود به حساب</h2>

        <?php if (isset($error)) echo "<p class='text-red-400 mb-4 text-center'>$error</p>"; ?>

        <form method="post" class="space-y-4">
            <input type="text" name="username" placeholder="نام کاربری یا ایمیل" required
                   class="w-full p-3 rounded-lg bg-black/30 border border-white/20 placeholder-gray-300 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400">

            <input type="password" name="password" placeholder="رمز عبور" required
                   class="w-full p-3 rounded-lg bg-black/30 border border-white/20 placeholder-gray-300 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400">

            <button type="submit"
                    class="w-full py-3 rounded-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold shadow-lg hover:from-purple-700 hover:to-pink-600 transform hover:scale-105 transition">
                ورود
            </button>
        </form>
    </div>
</div>

<?php get_footer(); ?>
