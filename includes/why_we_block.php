<section class="counters6 counters cid-s3YpLB2bHe" id="counters6-co">
    <div class="container pt-4 mt-2">
        <h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2">
            <strong><?= ($lang == 'ua') ? "Чому обирають нас?" : "Почему выбирают нас?" ?></strong>
        </h2>
        
        <h3 class="mbr-section-subtitle pb-5 align-center mbr-fonts-style display-5">
            <?php if ($lang == 'ua'): ?>
                Наша компанія надає професійні послуги евакуації по Харкову та Харківській області. Завдяки власному парку техніки та грамотно організованому чергуванню наші тарифи на 15-20% нижчі за середні, а час подачі становить всього 20-30 хвилин. <br>
                Ми перевозимо легкові авто з будь-яким дорожнім просвітом, позашляховики, мото- та спецтехніку, вантажні машини, автобуси. <br>
                Швидко евакуюємо як нове, так і несправне ТЗ, незалежно від характеру та ступеня поломки.
            <?php else: ?>
                Наша компания предоставляет профессиональные услуги эвакуации по Харькову и Харьковской области. Благодаря собственному парку техники и грамотно организованному дежурству наши тарифы на 15-20% ниже средних, а время подачи составляет всего 20-30 минут. <br>
                Мы перевозим легковые авто с любым дорожным просветом, внедорожники, мото- и спецтехнику, грузовые машины, автобусы. <br>
                Быстро эвакуируем как новое, так и неисправное ТС, независимо от характера и степени поломки.
            <?php endif; ?>
        </h3>

        <div>
            <div class="cards-container">
                <div class="card col-12 col-md-6 col-lg-4 pb-md-4">
                    <div class="panel-item align-center">
                        <div class="card-img pb-3">
                            <h3 class="img-text mbr-fonts-style display-1"><strong>01.</strong></h3>
                        </div>
                        <div class="card-text">
                            <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">
                                <?= ($lang == 'ua') ? "Швидка подача" : "Быстрая подача" ?>
                            </h4>
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                <?= ($lang == 'ua') 
                                    ? "Евакуатор буде у вас через 20-30 хвилин після виклику!" 
                                    : "Эвакуатор будет у вас через 20-30 минут после вызова!" ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card col-12 col-md-6 col-lg-4">
                    <div class="panel-item align-center">
                        <div class="card-img pb-3">
                            <h3 class="img-text mbr-fonts-style display-1"><strong>02.</strong></h3>
                        </div>
                        <div class="card-text">
                            <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">
                                <?= ($lang == 'ua') ? "Працюємо 24/7" : "Работаем 24/7" ?>
                            </h4>
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                <?= ($lang == 'ua') 
                                    ? "Ми готові прийти до вас на допомогу в будь-який час доби!" 
                                    : "Мы готовы прийти к вам на помощь в любое время суток!" ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card col-12 col-md-6 col-lg-4 last-child">
                    <div class="panel-item align-center">
                        <div class="card-img pb-3">
                            <h3 class="img-text mbr-fonts-style display-1"><strong>03.</strong></h3>
                        </div>
                        <div class="card-text">
                            <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">
                                <?= ($lang == 'ua') ? "Вигідна ціна" : "Выгодная цена" ?>
                            </h4>
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                <?= ($lang == 'ua') 
                                    ? "Кращі ціни на евакуацію автомобілів всього від " . $settings['price_car'] . " грн!" 
                                    : "Лучшие цены на эвакуацию автомобилей всего от " . $settings['price_car'] . " грн!" ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
