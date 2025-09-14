<?php
/*
Template Name: Contact
*/
get_header();
?>

    <section class="bg-gray-900 text-white py-12 px-6">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl font-bold text-purple-400 mb-4 drop-shadow-lg">📬 تماس با Gamer Match</h1>
            <p class="text-gray-300 mb-8">اگه سوالی داری، یا می‌خوای با تیم ما در ارتباط باشی، فرم زیر رو پر کن یا از شبکه‌های اجتماعی استفاده کن.</p>
        </div>

        <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-xl shadow-lg">
            <form method="post" action="#" class="space-y-4">
                <input type="text" name="name" placeholder="نام شما" required
                       class="w-full p-4 rounded bg-gray-700 text-white">

                <input type="email" name="email" placeholder="ایمیل" required
                       class="w-full p-4 rounded bg-gray-700 text-white">

                <textarea name="message" placeholder="پیام شما" rows="5" required
                          class="w-full p-4 rounded bg-gray-700 text-white"></textarea>

                <button type="submit"
                        class="w-full px-4 py-2 rounded-full bg-gradient-to-r from-purple-600 to-pink-500 text-white hover:from-purple-700 hover:to-pink-600 transition shadow-lg">
                    ارسال پیام
                </button>
            </form>
        </div>

        <div class="mt-10 text-center text-gray-400">
            <p>📱 اینستاگرام: <a href="#" class="text-purple-150 underline">instagram.com/gamermatch</a></p>
            <br>
            <p>💬 تلگرام: <a href="#" class="text-purple-400 underline">t.me/gamermatch</a></p>
            <br>

            <p>📧 ایمیل: <a href="mailto:info@gamermatch.ir" class="text-purple-400 underline">info@gamermatch.ir</a></p>
        </div>
    </section>

<?php get_footer(); ?>