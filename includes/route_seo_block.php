<?php
// includes/route_seo_block.php

// Получаем доступ к переменным
global $dist_val, $time_val, $price_val, $city_val, $in_city_val, $lang;

// Выводим только если есть данные о дистанции
if ($dist_val && $time_val): 
?>

    <section class="mbr-section article content1 cid-sfh9tj5sqS" style="padding-top: 40px; padding-bottom: 40px; background-color: #f7f7f7; border-top: 1px solid #eee;">
        <div class="container">
            <div class="media-container-row">
                <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-10">
                    <?php if ($lang == 'ua'): ?>
                        <h2 class="align-center pb-3 mbr-fonts-style display-5">
                            Маршрут евакуатора Харків — <?= $city_val ?>
                        </h2>
                        <p>
                            <strong>Скільки коштує та як довго чекати?</strong><br>
                            Багатьох клієнтів цікавить, як швидко приїде спецтехніка та як формується ціна. 
                            Оскільки відстань від Харкова до населеного пункту <strong><?= $city_val ?> складає <?= $dist_val ?> км</strong>, 
                            орієнтовний час подачі евакуатора дорівнює <strong><?= $time_val ?></strong> (час може змінюватися залежно від трафіку та погоди).
                        </p>
                        <p>
                            Ми працюємо цілодобово. Вартість розраховується індивідуально, але стартовий тариф починається <strong>від <?= $price_val ?> грн</strong>. 
                            Телефонуйте диспетчеру для точного розрахунку вартості перевезення вашого авто.
                        </p>
                    <?php else: ?>
                        <h2 class="align-center pb-3 mbr-fonts-style display-5">
                            Маршрут эвакуатора Харьков — <?= $city_val ?>
                        </h2>
                        <p>
                            <strong>Сколько стоит и как долго ждать?</strong><br>
                            Многих клиентов интересует, как быстро приедет спецтехника и как формируется цена. 
                            Так как расстояние от Харькова до населенного пункта <strong><?= $city_val ?> составляет <?= $dist_val ?> км</strong>, 
                            ориентировочное время подачи эвакуатора равно <strong><?= $time_val ?></strong> (время может меняться в зависимости от трафика и погоды).
                        </p>
                        <p>
                            Мы работаем круглосуточно. Стоимость рассчитывается индивидуально, но стартовый тариф начинается <strong>от <?= $price_val ?> грн</strong>. 
                            Звоните диспетчеру для точного расчета стоимости перевозки вашего авто.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    if ($lang == 'ua') {
        $faq_questions = [
            ["q" => "Скільки коштує евакуатор {$in_city_val}?", "a" => "Ціна залежить від складності навантаження, але стартовий тариф починається від {$price_val} грн."],
            ["q" => "Яка відстань від Харкова до населеного пункту {$city_val}?", "a" => "Відстань складає {$dist_val} км. Ми виконуємо перевезення по всьому маршруту."],
            ["q" => "Як довго чекати на подачу евакуатора?", "a" => "Орієнтовний час подачі складає {$time_val}, однак він може змінюватися залежно від дорожньої ситуації."]
        ];
    } else {
        $faq_questions = [
            ["q" => "Сколько стоит эвакуатор {$in_city_val}?", "a" => "Цена зависит от сложности погрузки, но стартовый тариф начинается от {$price_val} грн."],
            ["q" => "Какое расстояние от Харькова до населенного пункта {$city_val}?", "a" => "Расстояние составляет {$dist_val} км. Мы выполняем перевозку по всему маршруту."],
            ["q" => "Как долго ждать подачу эвакуатора?", "a" => "Ориентировочное время подачи составляет {$time_val}, однако оно может меняться в зависимости от дорожной ситуации."]
        ];
    }
    $faq_entities = [];
    foreach ($faq_questions as $item) {
        $faq_entities[] = ["@type" => "Question", "name" => $item['q'], "acceptedAnswer" => ["@type" => "Answer", "text" => $item['a']]];
    }
    ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": <?= json_encode($faq_entities, JSON_UNESCAPED_UNICODE) ?>
    }
    </script>

<?php endif; ?>