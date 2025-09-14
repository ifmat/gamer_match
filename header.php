<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title(); ?></title>
    <?php wp_head(); ?>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/style.css">
</head>
<body <?php body_class(); ?>>

<header class="bg-gray-900 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">🎮 Gamer Match</h1>
        <nav class="flex space-x-4 rtl:space-x-reverse">
            <a href="<?php echo home_url('/'); ?>" class="px-3 hover:text-cyan-400 transition">خانه</a>
            <a href="<?php echo home_url('/view-requests'); ?>" class="px-3 hover:text-cyan-400 transition">درخواست‌ها</a>

            <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo admin_url('dashboard'); ?>" class="px-3 hover:text-cyan-400 transition">داشبورد</a>
                <a href="<?php echo wp_logout_url( home_url() ); ?>" class="px-3 hover:text-red-400 transition">خروج</a>
            <?php else : ?>
                <a href="<?php echo home_url('/login'); ?>" class="px-3 hover:text-cyan-400 transition">ورود</a>
                <a href="<?php echo home_url('/register'); ?>" class="px-3 hover:text-cyan-400 transition">ثبت‌نام</a>
            <?php endif; ?>
        </nav>
    </div>
</header>


<main class="container mx-auto mt-6">