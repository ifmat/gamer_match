<?php
/*
Template Name: About Us
*/

get_header();
?>

<div class="min-h-screen bg-gray-900 text-white px-4 md:px-10 py-12">

    <!-- عنوان صفحه -->
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-cyan-300 mb-4">درباره ما</h1>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto leading-relaxed">
            در Gamer Match، ما به گیمرها و پروپلیرها این امکان را می‌دهیم تا در پروژه‌های واقعی بازی‌سازی همکاری کنند، مهارت‌های خود را ارتقا دهند و با دیگر حرفه‌ای‌ها شبکه‌سازی کنند. هدف ما ایجاد یک جامعه پویا و حرفه‌ای برای رشد و یادگیری در صنعت بازی است.
        </p>
    </div>

    <!-- بخش تاریخچه و ماموریت -->
    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="bg-gray-800/70 backdrop-blur-md p-6 rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold text-purple-400 mb-3">تاریخچه ما</h2>
            <p class="text-gray-200 leading-relaxed">
                Gamer Match در سال 1401 با هدف ایجاد یک پلتفرم حرفه‌ای برای گیمرها و پروپلیرها تأسیس شد. از ابتدا تمرکز ما روی ارتباط و همکاری بین کاربران حرفه‌ای و علاقه‌مند به بازی‌های ویدیویی بوده است.
            </p>
        </div>
        <div class="bg-gray-800/70 backdrop-blur-md p-6 rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold text-purple-400 mb-3">ماموریت ما</h2>
            <p class="text-gray-200 leading-relaxed">
                ماموریت ما فراهم کردن بستری امن و حرفه‌ای برای همکاری، رقابت سالم و رشد مهارت‌های کاربران در بازی‌ها است. ما معتقدیم یادگیری از طریق تجربه واقعی بهترین روش برای پیشرفت در این صنعت است.
            </p>
        </div>
    </div>

    <!-- تیم ما -->
    <div class="max-w-4xl mx-auto mb-12">
        <h2 class="text-3xl font-bold text-cyan-400 mb-6 text-center">تیم ما</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="bg-gray-800/70 backdrop-blur-md p-6 rounded-2xl shadow-lg">
                <h3 class="text-xl font-bold mb-2">علی رضایی</h3>
                <p class="text-gray-300">مدیر پروژه و بنیان‌گذار</p>
            </div>
            <div class="bg-gray-800/70 backdrop-blur-md p-6 rounded-2xl shadow-lg">
                <h3 class="text-xl font-bold mb-2">سارا محمدی</h3>
                <p class="text-gray-300">طراح رابط کاربری و تجربه کاربری</p>
            </div>
            <div class="bg-gray-800/70 backdrop-blur-md p-6 rounded-2xl shadow-lg">
                <h3 class="text-xl font-bold mb-2">امیر حسینی</h3>
                <p class="text-gray-300">توسعه‌دهنده فرانت‌اند و بک‌اند</p>
            </div>
        </div>
    </div>

    <!-- بخش تماس با ما -->
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-purple-400 mb-4">تماس با ما</h2>
        <p class="text-gray-300 mb-6">
            اگر سوال یا پیشنهادی دارید، می‌توانید از طریق فرم تماس با ما ارتباط برقرار کنید یا به ایمیل info@gamermatch.com پیام دهید.
        </p>
        <a href="<?php echo home_url('/contact'); ?>" class="inline-block px-6 py-3 bg-cyan-500 hover:bg-cyan-600 text-white font-bold rounded-full transition">
            فرم تماس
        </a>
    </div>

</div>

<?php get_footer(); ?>
