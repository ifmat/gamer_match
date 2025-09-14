<?php get_header(); ?>

<div class="min-h-screen p-6 max-w-6xl mx-auto">

    <?php
    if (is_user_logged_in()) :

        $current_user_id = get_current_user_id();
        $user_role = get_user_meta($current_user_id, 'user_role_type', true);

        if ($user_role === 'gamer') :

            // --- فیلتر دریافت شده از کاربر ---
            $filter_day = isset($_GET['filter_day']) ? intval($_GET['filter_day']) : '';
            $filter_price = isset($_GET['filter_price']) ? intval($_GET['filter_price']) : '';

            // --- کوئری با متا کوئری بر اساس فیلتر ---
            $meta_query = ['relation' => 'AND'];
            if ($filter_day) {
                $meta_query[] = [
                    'key' => 'days',
                    'value' => $filter_day,
                    'compare' => '<=',
                    'type' => 'NUMERIC'
                ];
            }
            if ($filter_price) {
                $meta_query[] = [
                    'key' => 'price',
                    'value' => $filter_price,
                    'compare' => '<=',
                    'type' => 'NUMERIC'
                ];
            }

            $args = array(
                'post_type' => array('client_request', 'gamer_request'),
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_query' => $meta_query
            );
            $requests = new WP_Query($args); ?>

            <h2 class="text-3xl font-bold text-white mb-8 text-center">📋 لیست درخواست‌ها</h2>

            <!-- فرم فیلتر -->
            <form method="get" class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-center">
                <input type="number" name="filter_day" placeholder="حداکثر روز"
                       value="<?php echo esc_attr($filter_day); ?>" class="p-2 rounded bg-gray-800 text-white">
                <input type="number" name="filter_price" placeholder="حداکثر قیمت"
                       value="<?php echo esc_attr($filter_price); ?>" class="p-2 rounded bg-gray-800 text-white">
                <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded font-bold">اعمال
                    فیلتر
                </button>
                <a href="<?php echo get_permalink(); ?>"
                   class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-bold">پاک‌سازی</a>
            </form>

            <?php if ($requests->have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <?php while ($requests->have_posts()) : $requests->the_post(); ?>
                    <?php $request_id = get_the_ID(); ?>
                    <div class="request-card bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition">
                        <h3 class="text-xl font-bold mb-2 text-white"><?php the_title(); ?></h3>
                        <p class="mb-3 text-gray-300"><?php the_excerpt(); ?></p>
                        <p class="text-sm text-gray-400">ثبت‌شده توسط: <?php the_author(); ?></p>
                        <p class="text-sm text-gray-400">نوع
                            درخواست: <?php echo get_post_type() === 'gamer_request' ? 'گیمر' : 'درخواست‌کننده'; ?></p>

                        <?php
                        $day = get_post_meta($request_id, 'days', true);
                        $price = get_post_meta($request_id, 'price', true);
                        $prerequisite = get_post_meta($request_id, 'request_prerequisite', true);
                        $image_id = get_post_meta($request_id, 'request_image', true);
                        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
                        ?>

                        <?php if ($day) : ?><p class="text-sm text-gray-300">
                            روز: <?php echo esc_html($day); ?></p><?php endif; ?>
                        <?php if ($price) : ?><p class="text-sm text-gray-300">قیمت: <?php echo esc_html($price); ?>
                            تومان</p><?php endif; ?>
                        <?php if ($prerequisite) : ?><p class="text-sm text-gray-300">
                            پیش‌نیاز: <?php echo esc_html($prerequisite); ?></p><?php endif; ?>
                        <?php if ($image_url) : ?>
                            <img src="<?php echo esc_url($image_url); ?>" alt="عکس درخواست"
                                 class="mt-2 rounded-md max-h-48 object-cover">
                        <?php endif; ?>

                        <a href="<?php echo get_permalink(); ?>"
                           class="text-cyan-400 hover:underline mt-2 inline-block">مشاهده جزئیات</a>

                        <?php
                        $form_id = "offer-form-{$request_id}";
                        ?>
                        <button type="button"
                                onclick="document.getElementById('<?php echo $form_id; ?>').classList.toggle('hidden');"
                                class="mt-3 w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 rounded">
                            ثبت پیشنهاد
                        </button>

                        <div id="<?php echo $form_id; ?>"
                             class="hidden mt-3 p-4 bg-gray-700 rounded max-h-96 overflow-y-auto">
                            <form method="post">
                                <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                <input type="text" name="offer_price" placeholder="قیمت پیشنهادی" required
                                       class="w-full mb-2 p-2 rounded bg-gray-800 text-white">
                                <input type="text" name="offer_days" placeholder="تعداد روزها" required
                                       class="w-full mb-2 p-2 rounded bg-gray-800 text-white">
                                <textarea name="offer_message" placeholder="توضیحات"
                                          class="w-full mb-2 p-2 rounded bg-gray-800 text-white"></textarea>
                                <button type="submit" name="submit_offer"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded w-full">
                                    ارسال پیشنهاد
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>

        <?php else : ?>
            <p class="text-center text-white text-lg">هیچ درخواستی با این فیلتر پیدا نشد.</p>
        <?php endif; ?>

        <?php elseif ($user_role === 'client') : ?>
            <div class="text-center p-10">
                <h2 class="text-2xl text-white font-bold mb-4">📢 داشبورد شما</h2>
                <p class="text-gray-300 mb-4">برای مدیریت درخواست‌ها به داشبورد خود مراجعه کنید.</p>
                <a href="<?php echo home_url('/dashboard'); ?>"
                   class="inline-block px-6 py-2 rounded-full bg-cyan-500 hover:bg-cyan-600 text-white font-semibold transition">
                    ورود به داشبورد
                </a>
            </div>

        <?php endif; ?>

    <?php else : ?>
        <!-- کاربر لاگین نکرده: همان صفحه اصلی -->

        <div class="relative w-full min-h-screen overflow-hidden text-white">

            <?php if (!is_user_logged_in()) : ?>

                <!-- باکس اصلی با عنوان و دکمه ثبت‌نام -->
                <div class="flex flex-col items-center justify-center text-center px-4 py-20">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 md:p-10 shadow-xl">
                        <h1 class="text-4xl md:text-5xl text-cyan-300 font-bold drop-shadow-lg mb-4">🎮 Gamer Match</h1>
                        <p class="text-lg text-gray-200 max-w-xl mx-auto">جایی برای مچ‌کردن گیمرها و پروپلیرها، همکاری، رقابت و رشد مهارت‌ها</p>
                        <a href="<?php echo home_url('/register'); ?>"
                           class="mt-6 inline-block px-6 py-2 rounded-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-semibold hover:from-purple-700 hover:to-pink-600 transition transform hover:scale-105 shadow-lg">
                            ثبت‌نام کن
                        </a>
                    </div>
                </div>

                <!-- بخش سه باکس زیر باکس اصلی -->
                <section class="container mx-auto mt-10 px-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-purple-500 transition">
                            <h2 class="text-xl font-bold mb-2 text-purple-400">ثبت درخواست بازی</h2>
                            <p class="text-sm text-gray-300">گیمرها می‌تونن درخواست همکاری ثبت کنن و منتظر پاسخ پروپلیرها باشن.</p>
                        </div>
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-purple-500 transition">
                            <h2 class="text-xl font-bold mb-2 text-purple-400">مشاهده درخواست‌ها</h2>
                            <p class="text-sm text-gray-300">پروپلیرها می‌تونن درخواست‌ها رو ببینن و با گیمرها ارتباط بگیرن.</p>
                        </div>
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-purple-500 transition">
                            <h2 class="text-xl font-bold mb-2 text-purple-400">داشبورد شخصی</h2>
                            <p class="text-sm text-gray-300">هر کاربر یه داشبورد داره برای مدیریت درخواست‌ها و اطلاعات خودش.</p>
                        </div>
                    </div>
                </section>

                <!-- باکس توضیحات کرو سایت -->
                <div class="container mx-auto mt-10 px-4">
                    <div class="bg-gray-900/50 backdrop-blur-sm border border-white/10 rounded-xl p-6 md:p-10 text-center">
                        <h2 class="text-2xl font-bold mb-4 text-cyan-300">🎯 چرا Gamer Match؟</h2>
                        <p class="text-gray-200 max-w-2xl mx-auto text-justify space-y-2">
                            در Gamer Match شما می‌توانید با گیمرها و پروپلیرهای حرفه‌ای همکاری کنید و فرصت‌های جدید برای رشد مهارت‌های بازی خود پیدا کنید.<br>
                            ما فضایی امن و جذاب برای ارتباط بین گیمرها و پروپلیرها فراهم کرده‌ایم تا بتوانید تیم‌های حرفه‌ای تشکیل دهید.<br>
                            با شرکت در پروژه‌های واقعی، تجربه عملی کسب کنید و مهارت‌های استراتژی و هماهنگی تیمی خود را ارتقا دهید.<br>
                            امکان مشاهده و ثبت درخواست‌های بازی و همکاری با دیگر کاربران به صورت مستقیم فراهم شده است.<br>
                            می‌توانید پروژه‌های مختلف را بررسی کنید و با توجه به توانایی و علاقه خود پیشنهاد دهید.<br>
                            داشبورد شخصی به شما این امکان را می‌دهد که تمام درخواست‌ها و پیشنهادهای خود را مدیریت کنید.<br>
                            سیستم پیشنهادات ما باعث می‌شود که شما بهترین فرصت‌ها را بر اساس توانایی‌ها و شرایط خود دریافت کنید.<br>
                            با استفاده از فیلترهای قیمت و مدت زمان، سریع‌تر درخواست‌های مناسب خود را پیدا کنید.<br>
                            شبکه ارتباطی Gamer Match به شما کمک می‌کند تا دوستان جدیدی در دنیای بازی پیدا کنید و تیم‌های قدرتمندی بسازید.<br>
                            با استفاده از این پلتفرم، مسیر حرفه‌ای شما در دنیای بازی‌های آنلاین سریع‌تر و سازمان‌یافته‌تر خواهد بود.
                        </p>
                    </div>
                </div>
                <!-- باکس صفحات مهم -->
                <div class="bg-gray-800/70 backdrop-blur-md border border-white/20 rounded-2xl p-6 mt-8 max-w-4xl mx-auto shadow-lg text-white ">
                    <h2 class="text-2xl font-bold mb-4 text-purple-400">صفحات مهم</h2>
                    <ul class="space-y-2">
                        <li>
                            <a href="<?php echo home_url('/about-us'); ?>" class="text-cyan-400 hover:underline">
                                1️⃣ درباره ما
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo home_url('/contact'); ?>" class="text-cyan-400 hover:underline">
                                2️⃣ تماس با ما
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo home_url('/terms'); ?>" class="text-cyan-400 hover:underline">
                                3️⃣ قوانین و مقررات
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo home_url('/faq'); ?>" class="text-cyan-400 hover:underline">
                                4️⃣ سوالات متداول
                            </a>
                        </li>
                    </ul>
                </div>

            <?php endif; ?>

        </div>

    <?php endif; ?>

</div>

<?php get_footer(); ?>
