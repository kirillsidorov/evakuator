<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$page_type = "main";

$title = "ᐈ Евакуатор Харків — Замовлення в 1 клік — Цілодобові послуги Автососа";
$description = "🚍 Евакуатор Харків — 💰 Тариф від " . $settings['price_car'] . " грн ☎️ Дзвоніть: " . $settings['tel_one_view']  . " ⚡ Терміновий Виклик Автососа у Харкові ⭐ Фіксовані тарифи без переплат.";

$custom_h1 = "Евакуатор Харків";
$custom_p  = "Терміновий виклик евакуатора протягом 20-40 хвилин&nbsp;<br> від " . $settings['price_car'] . " грн";

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/h1_block.php';

?>

<section class="mbr-section content4 pt-5 cid-s1LZb2m2De">
    <div class="container">
        <div class="media-container-row">
            <div class="col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Скільки коштують послуги евакуатора?</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mbr-fonts-style display-7">
                        <thead class="thead-dark" style="background-color: #333; color: #fff;">
                            <tr>
                                <th>Послуга</th>
                                <th>Вартість</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Подача евакуатора по Харкову (легкове авто)</td>
                                <td>від <?php echo $settings['price_car']; ?> грн</td>
                            </tr>
                            <tr>
                                <td><strong>Евакуатор ціна за км</strong> (за містом)</td>
                                <td>від <?php echo $settings['price_km']; ?> грн/км</td>
                            </tr>
                            <tr>
                                <td>Евакуація позашляховика / мікроавтобуса</td>
                                <td>від <?php echo $settings['price_jeep']; ?> грн</td>
                            </tr>
                            <tr>
                                <td>Складне завантаження (заблоковані колеса, кювет)</td>
                                <td>від <?= $settings['price_spec'] ?> грн</td>
                            </tr>
                            <tr>
                                <td>Попутний евакуатор по Україні</td>
                                <td>Договірна (знижка до 40%)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted align-center mt-2" style="font-size: 0.9rem;">*Кінцева вартість залежить від складності робіт та габаритів авто. Телефонуйте для точного прорахунку.</p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 pt-4 cid-s1LZb2m2De">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Замовити евакуатор: швидко та недорого</h2>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content11 cid-s1LYxqocoP">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text counter-container col-12 col-md-8 mbr-fonts-style display-7">
                <ul>
                    <li>Евакуатор Харків (Водафон): <strong><?php echo $settings['tel_one_view'] ; ?></strong></li>
                    <li>Евакуатор Харків (Київстар): <strong><?php echo $settings['tel_two_view']; ?></strong></li>
                    <li>Швидка подача евакуатора Харків, Харківська область, Україна.</li>
                    <li>Евакуація легкових автомобілів, позашляховиків, мікроавтобусів, будівельної та іншої колісної техніки</li>
                    <li>Режим роботи 24/7.</li>
                    <li>Подача евакуатора у призначений час на адресу замовника.</li>
                    <li>Обслуговування та ремонт автомобілів та позашляховиків. Власне СТО у центрі міста.</li>
                    <li>Розрахунок вартості транспортування (евакуації) на момент замовлення.</li>
                    <li>Попутна доставка (дозволяє заощаджувати 35-40% коштів).</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 pt-4 cid-s1LZb2m2De">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                 <h2 class="align-center pb-3 mbr-fonts-style display-2">Терміновий виклик евакуатора в Харкові</h2>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-s1LZOKLTIh">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Шукаєте надійний <strong>автоевакуатор</strong> у Харкові? Ми забезпечуємо термінову евакуацію легкових автомобілів, позашляховиків, мікроавтобусів та спецтехніки. Наші машини чергують у всіх районах міста (Салтівка, Олексіївка, Холодна Гора, ХТЗ, Центр), що гарантує швидку подачу від 20 хвилин. Надаємо професійні <strong>послуги евакуатора</strong> цілодобово, без вихідних та свят.</p>
                <p>Ми не просто перевозимо авто — ми вирішуємо вашу проблему на дорозі: від евакуації після ДТП до транспортування авто з заблокованими колесами. Точна <strong>ціна евакуатора</strong> розраховується диспетчером одразу під час вашого дзвінка та залишається фіксованою.</p>
            </div>
        </div>
    </div>
</section>

<section class="header6 mbr-fullscreen mbr-parallax-background mt-5" style="background-image: url('https://evakuator-kharkov.kh.ua/assets/images/2-1000x500.webp');">
    <div class="mbr-overlay" style="opacity: 0.8; background-color: rgb(255, 255, 255);"></div>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="mbr-white col-md-10">
                <h3 class="mbr-section-title align-center mbr-bold pb-3 mbr-fonts-style display-2">Наші евакуатори <div>є в кожному районі </div><div>Харкова</div></h3>
                <p class="mbr-text align-center pb-3 mbr-fonts-style display-5" style="color:#000;">Наша служба здійснює цілодобову евакуацію автомобілів, мотоциклів і спецтехніки за найкращими тарифами в регіоні. Техніка чергує у всіх районах Харкова та області, тому час подачі становить в середньому 20-30 хвилин. 
<br><br>У нашому розпорядженні – нові сучасні автоевакуатори, призначені для перевезення машин з будь-яким дорожнім просвітом і будь-якими несправностями. Гарантуємо безпечне і дбайливе транспортування, в тому числі, авто з заблокованими колесами або після серйозних ДТП.</p>              
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 pt-5">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Чому варто замовити автоевакуатор у нас</h2>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Центр автоевакуації спеціалізується на наданні допомоги харківським автолюбителям і гостям нашого міста. Ми багато років допомагаємо людям у скрутну хвилину, знаємо всі тонкощі евакуації, виконуємо доставку несправного авто з дотриманням норм законодавства та вимог ПДР. Автоевакуатор Харків – це «швидка допомога» навіть у безвихідних ситуаціях, це надійна техніка, сучасне обладнання та висококласні фахівці. Основу нашої команди складають справжні експерти в сфері евакуації несправного транспорту. У нас працюють досвідчені диспетчери, менеджери та водії, які знають і люблять свою роботу.</p>
                
                <div class="align-center pt-4 pb-3">
                    <img src="https://evakuator-kharkov.kh.ua/assets/images/2-1000x500.webp" alt="Евакуатор Харків в роботі - подача 20 хвилин" style="width: 100%; max-width: 100%; height: auto; border-radius: 4px;">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 pt-4">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-5 col-md-6 text-center"><font color="#232323"><strong>Прорахуйте вартість замовлення <br>прямо зараз!</strong></font></div>
        </div>
    </div>
</section>

<section class="mbr-section content8 pb-5">
    <div class="container">
        <div class="media-container-row title">
            <div class="col-12 col-md-8">
                <div class="mbr-section-btn align-center">
                    <a class="btn btn-success display-5" href="tel:<?php echo $settings['tel_one_link'] ?>">
                        <span class="mbri-touch mbr-iconfont mbr-iconfont-btn"></span>Викликати евакуатор
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 pt-4">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Наші послуги евакуатора та спецтехніки</h2>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content11">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text counter-container col-12 col-md-8 mbr-fonts-style display-7">
                <ul>
                    <li>Кваліфікований персонал.</li>
                    <li>Сучасна техніка та обладнання. </li>
                    <li>Надійність, безпека та збереження вашого автомобіля.</li>
                    <li>Мінімальні терміни прибуття на місце виклику та евакуації.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Якщо трапиться неприємність з автомобілем, якщо знадобиться викликати евакуатор в Харкові дешево і швидко, наша служба завжди до ваших послуг. Ми швидко опинимося на місці події, в цілості й схоронності доставимо автомобіль в будь-яке вказане вами місце.</p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 pt-5">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Порядок термінового виклику</h2>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 pb-4">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Ми працюємо з приватними та корпоративними клієнтами, евакуацію аварійних авто нам довіряють організації різних форм власності, ми співпрацюємо з автосервісами та страховими компаніями, до яких входить сервіс евакуатора. Довгострокова співпраця зі службою евакуації вирішує безліч проблем і дозволяє істотно знизити ціну на послуги евакуатора, збільшити кількість спеціальної техніки і гарантувати оперативне обслуговування будь-якої кількості клієнтів. Замовити автоевакуатор дешево і без проблем можна за будь-яким із представлених на сайті телефонів.</p>
            </div>
        </div>
    </div>
</section>

<section class="step2 pb-5">
    <div class="container">
        <h3 class="mbr-section-subtitle pb-5 mbr-fonts-style align-center display-5">Всього 3 кроки для замовлення евакуатора</h3>
        <div class="step-container row justify-content-center">
            <div class="card col-12 col-md-4 separline">
                <div class="step-element">
                    <div class="step-wrapper pb-3">
                        <h3 class="step d-flex align-items-center justify-content-center m-auto"><strong>1</strong></h3>
                    </div>          
                    <div class="step-text-content align-center">
                        <h4 class="mbr-step-title pb-3 mbr-fonts-style display-5">Зателефонуйте нам</h4>
                        <p class="mbr-step-text mbr-fonts-style display-7"><strong><?php echo $settings['tel_one_view'] ; ?><br><?php echo $settings['tel_two_view']; ?></strong><br>або <br>залиште заявку на зворотний дзвінок.</p>
                    </div>
                </div>
            </div>

            <div class="card col-12 separline col-md-4">
                <div class="step-element">
                    <div class="step-wrapper pb-3">
                        <h3 class="step d-flex align-items-center justify-content-center m-auto"><strong>2</strong></h3>
                    </div>          
                    <div class="step-text-content align-center">
                        <h4 class="mbr-step-title pb-3 mbr-fonts-style display-5">Повідомте оператору</h4>
                        <p class="mbr-step-text mbr-fonts-style display-7">
                            Марку та модель автомобіля<br>Місцезнаходження автомобіля<br>Причину виклику<br>Наявність несправностей<br>Кінцевий пункт евакуації
                        </p>
                    </div>
                </div>
            </div>

            <div class="card col-md-4 col-12 separline last-child">
                <div class="step-element">
                    <div class="step-wrapper pb-3">
                        <h3 class="step d-flex align-items-center justify-content-center m-auto"><strong>3</strong></h3>
                    </div>          
                    <div class="step-text-content align-center">
                        <h4 class="mbr-step-title pb-3 mbr-fonts-style display-5">Чекайте евакуатор</h4>
                        <p class="mbr-step-text mbr-fonts-style display-7">
                            Ваше замовлення прийнято. <br>Середній час очікування евакуатора<br>25 хвилин!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="counters6 counters pt-5 pb-5 bg-light">
    <div class="container pt-4 mt-2">
        <h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2"><strong>Чому вибирають нас?</strong></h2>
        
        <div class="mbr-section-subtitle pb-5 align-center mbr-fonts-style display-5">
            Наша компанія надає професійні послуги евакуації по Харкову та Харківській області. Завдяки власному парку техніки та грамотно організованому чергуванню наші тарифи на 15-20% нижчі за середні, а час подачі становить всього 20-30 хвилин. <br>Ми перевозимо легкові авто з будь-яким дорожнім просвітом, позашляховики, мото- та спецтехніку, вантажні машини, автобуси. <br>Швидко евакуюємо як нові, так і несправні транспортні засоби, незалежно від характеру та ступеня поломки.
        </div>
        
        <div>
            <div class="cards-container row">
                <div class="card col-12 col-md-6 col-lg-4 pb-md-4">
                    <div class="panel-item align-center">
                        <div class="card-img pb-3">
                            <span class="mbr-iconfont mbri-clock" style="font-size: 60px; color: #333;"></span>
                        </div>
                        <div class="card-text">
                            <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">Швидка подача від 20 хвилин</h4>
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                Евакуатори в кожному районі міста!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card col-12 col-md-6 col-lg-4 pb-md-4">
                    <div class="panel-item align-center">
                        <div class="card-img pb-3">
                            <span class="mbr-iconfont mbri-setting3" style="font-size: 60px; color: #333;"></span>
                        </div>
                        <div class="card-text">
                            <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">Працюємо 24/7</h4>
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                Ми готові прийти до вас на допомогу в будь-який час доби!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card col-12 col-md-6 col-lg-4 last-child">
                    <div class="panel-item align-center">
                        <div class="card-img pb-3">
                            <span class="mbr-iconfont mbri-cash" style="font-size: 60px; color: #333;"></span>
                        </div>
                        <div class="card-text">
                            <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">Вигідна ціна</h4>
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                Найкращі ціни на евакуацію автомобілів всього від <?php echo $settings['price_car']; ?> грн!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/testimonials.php'; ?>

<section class="toggle1">
    <style>
        .faq-card {
            border: none;
            border-bottom: 1px solid #e0e0e0;
            background: #fff;
            margin-bottom: 0 !important;
            border-radius: 0 !important;
        }
        .faq-header {
            padding: 0;
            background: #fff;
            border: none;
        }
        .faq-btn {
            display: block;
            width: 100%;
            padding: 20px 0;
            text-align: left;
            color: #232323;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none !important;
            position: relative;
            transition: all 0.3s ease;
        }
        .faq-btn:hover {
            color: #d90429;
        }
        /* Стрелочка (+) */
        .faq-btn::after {
            content: '+';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            font-weight: 400;
            color: #777;
        }
        /* Когда открыто (-) */
        .faq-btn[aria-expanded="true"]::after {
            content: '−';
            color: #d90429;
        }
        .faq-btn[aria-expanded="true"] {
            color: #d90429;
        }
        .faq-body {
            border-top: none;
            padding-bottom: 25px;
            color: #555;
            line-height: 1.6;
        }
        .faq-list {
            padding-left: 20px;
            margin-top: 10px;
        }
        .faq-list li {
            margin-bottom: 5px;
        }
    </style>
    <div class="container">
        <div class="media-container-row">
            <div class="col-12 col-md-10 offset-md-1">
                <div class="section-head text-center space30">
                   <div itemscope="" itemtype="https://schema.org/FAQPage" class="faq"> 
                   
                   <h2 class="mbr-section-title pb-5 align-center mbr-fonts-style display-2">
                       Часті запитання (FAQ)
                   </h2>
                
                    <div class="clearfix"></div>
                    <div id="bootstrap-toggle" class="toggle-panel accordionStyles tab-content" role="tablist">
                        
                        <div class="card faq-card" itemscope="" itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <div class="card-header faq-header" role="tab" id="headingOne">
                                <a role="button" class="collapsed faq-btn display-7" data-toggle="collapse" href="#collapse1_204" aria-expanded="false" aria-controls="collapse1_204">
                                    <span itemprop="name">Як викликати евакуатор (Харків та область)?</span>
                                </a>
                            </div>
                            <div id="collapse1_204" class="panel-collapse noScroll collapse" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body faq-body">
                                    <div class="mbr-fonts-style display-7">
                                        <div itemscope="" itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                            <div itemprop="text">
                                                Щоб ми приїхали максимально швидко, повідомте диспетчеру 3 речі:
                                                <ul class="faq-list">
                                                    <li><strong>Марку та модель авто</strong> (щоб підібрати тип платформи).</li>
                                                    <li><strong>Точне місцезнаходження</strong> або орієнтири поруч.</li>
                                                    <li><strong>Кінцевий пункт</strong> доставки.</li>
                                                </ul>
                                                <p class="mt-2">Ми також надаємо послугу "Зворотній дзвінок" — залиште заявку на сайті.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card faq-card" itemscope="" itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <div class="card-header faq-header" role="tab" id="headingTwo">
                                <a role="button" class="collapsed faq-btn display-7" data-toggle="collapse" href="#collapse2_204" aria-expanded="false" aria-controls="collapse2_204">
                                    <span itemprop="name">Скільки коштує виклик евакуатора?</span>
                                </a>
                            </div>
                            <div id="collapse2_204" class="panel-collapse noScroll collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body faq-body">
                                    <div class="mbr-fonts-style display-7">
                                        <div itemscope="" itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                            <div itemprop="text">
                                                Точну ціну диспетчер назве <strong>одразу по телефону</strong>. Вона фіксована і не зміниться в дорозі.
                                                <br><br>
                                                <strong>Що впливає на ціну:</strong>
                                                <ul class="faq-list">
                                                    <li>Тип транспорту (легковик, джип, бус).</li>
                                                    <li>Складність завантаження (заблоковані колеса, авто в кюветі).</li>
                                                    <li>Кілометраж (якщо перевезення за межі міста).</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card faq-card" itemscope="" itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <div class="card-header faq-header" role="tab" id="headingThree">
                                <a role="button" class="collapsed faq-btn display-7" data-toggle="collapse" href="#collapse3_204" aria-expanded="false" aria-controls="collapse3_204">
                                    <span itemprop="name">Які документи потрібні для евакуації?</span>
                                </a>
                            </div>
                            <div id="collapse3_204" class="panel-collapse noScroll collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body faq-body">
                                    <div class="mbr-fonts-style display-7">
                                        <div itemscope="" itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                            <div itemprop="text">
                                                Згідно із законодавством, ми можемо транспортувати авто тільки при наявності документів. Водій повинен мати при собі:
                                                <ul class="faq-list">
                                                    <li><strong>Техпаспорт</strong> (свідоцтво про реєстрацію).</li>
                                                    <li><strong>Посвідчення водія</strong> (права).</li>
                                                    <li>Довіреність (якщо ви не власник).</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card faq-card" itemscope="" itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <div class="card-header faq-header" role="tab" id="headingFour">
                                <a role="button" class="collapsed faq-btn display-7" data-toggle="collapse" href="#collapse4_204" aria-expanded="false" aria-controls="collapse4_204">
                                    <span itemprop="name">Яку техніку ви перевозите?</span>
                                </a>
                            </div>
                            <div id="collapse4_204" class="panel-collapse noScroll collapse" role="tabpanel" aria-labelledby="headingFour">
                                <div class="panel-body faq-body">
                                    <div class="mbr-fonts-style display-7">
                                        <div itemscope="" itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                            <div itemprop="text">
                                                Наш парк дозволяє перевозити практично все:
                                                <ul class="faq-list">
                                                    <li>Легкові авто (седан, хетчбек).</li>
                                                    <li>Позашляховики та кросовери.</li>
                                                    <li>Мікроавтобуси та мінівени.</li>
                                                    <li>Спецтехніку (навантажувачі, котки, с/г техніка до 6 тонн).</li>
                                                </ul>
                                                Також ми перевозимо негабаритні вантажі.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card faq-card" itemscope="" itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <div class="card-header faq-header" role="tab" id="headingFive">
                                <a role="button" class="collapsed faq-btn display-7" data-toggle="collapse" href="#collapse5_204" aria-expanded="false" aria-controls="collapse5_204">
                                    <span itemprop="name">Як довго чекати на евакуатор?</span>
                                </a>
                            </div>
                            <div id="collapse5_204" class="panel-collapse noScroll collapse" role="tabpanel" aria-labelledby="headingFive">
                                <div class="panel-body faq-body">
                                    <div class="mbr-fonts-style display-7">
                                        <div itemscope="" itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                            <div itemprop="text">
                                                Середній час подачі в межах Харкова — <strong>20-30 хвилин</strong>. 
                                                <br>Наші машини чергують у різних районах міста, щоб не стояти в заторах.
                                                <br><br>
                                                <em>Примітка: Якщо ви знаходитесь далеко в області, час очікування буде розраховано диспетчером індивідуально.</em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card faq-card" itemscope="" itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <div class="card-header faq-header" role="tab" id="headingSix">
                                <a role="button" class="collapsed faq-btn display-7" data-toggle="collapse" href="#collapse6_204" aria-expanded="false" aria-controls="collapse6_204">
                                    <span itemprop="name">Що входить у вартість?</span>
                                </a>
                            </div>
                            <div id="collapse6_204" class="panel-collapse noScroll collapse" role="tabpanel" aria-labelledby="headingSix">
                                <div class="panel-body faq-body">
                                    <div class="mbr-fonts-style display-7">
                                        <div itemscope="" itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                            <div itemprop="text">
                                                Наші тарифи прозорі («без сюрпризів»).
                                                <br>
                                                <strong>У базову вартість входить:</strong>
                                                <ul class="faq-list">
                                                    <li>Подача евакуатора.</li>
                                                    <li>Завантаження авто на платформу.</li>
                                                    <li>Розвантаження в кінцевій точці.</li>
                                                </ul>
                                                Додатково оплачується кілометраж (якщо за містом) або складні умови (витягування з кювету).
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>