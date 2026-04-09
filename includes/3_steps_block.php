<section class="step2 cid-s3YpLwauCE">
    <div class="container">
        <h3 class="mbr-section-subtitle pb-5 mbr-fonts-style align-center display-5">
            <?php if ($lang == 'ua'): ?>
                Всього 3 кроки для замовлення евакуатора <?= $loc['in_city'] ?>
            <?php else: ?>
                Всего 3 шага для заказа эвакуатора <?= $loc['in_city'] ?>
            <?php endif; ?>
        </h3>
        <div class="step-container row justify-content-center">
            
            <div class="card col-12 col-md-4 separline">
                <div class="step-element">
                    <div class="step-wrapper pb-3">
                        <h3 class="step d-flex align-items-center justify-content-center m-auto"><strong>1</strong></h3>
                    </div>          
                    <div class="step-text-content align-center">
                        <h4 class="mbr-step-title pb-3 mbr-fonts-style display-5">
                            <?= ($lang == 'ua') ? "Зателефонуйте нам" : "Позвоните нам" ?>
                        </h4>
                        <p class="mbr-step-text mbr-fonts-style display-7">
                            <strong><?= $settings['tel_one_view']  ?><br><?= $settings['tel_two_view'] ?></strong><br>
                            <?= ($lang == 'ua') ? "або <br>залиште заявку на зворотний дзвінок." : "или <br>оставьте заявку на обратный звонок." ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card col-12 separline col-md-4">
                <div class="step-element">
                    <div class="step-wrapper pb-3">
                        <h3 class="step d-flex align-items-center justify-content-center m-auto"><strong>2</strong></h3>
                    </div>          
                    <div class="step-text-content align-center">
                        <h4 class="mbr-step-title pb-3 mbr-fonts-style display-5">
                            <?= ($lang == 'ua') ? "Уточніть деталі" : "Уточните детали" ?>
                        </h4>
                        <p class="mbr-step-text mbr-fonts-style display-7">
                            <?= ($lang == 'ua') 
                                ? "Повідомте диспетчеру ваше місцезнаходження та тип автомобіля." 
                                : "Сообщите диспетчеру ваше местоположение и тип автомобиля." ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card col-12 col-md-4 separline last-child">
                <div class="step-element">
                    <div class="step-wrapper pb-3">
                        <h3 class="step d-flex align-items-center justify-content-center m-auto"><strong>3</strong></h3>
                    </div>          
                    <div class="step-text-content align-center">
                        <h4 class="mbr-step-title pb-3 mbr-fonts-style display-5">
                            <?= ($lang == 'ua') ? "Чекайте евакуатор" : "Ожидайте эвакуатор" ?>
                        </h4>
                        <p class="mbr-step-text mbr-fonts-style display-7">
                            <?= ($lang == 'ua') 
                                ? "Ваше замовлення прийнято. <br>Середній час очікування евакуатора<br>25 хвилин!" 
                                : "Ваш заказ принят. <br>Среднее время ожидания эвакуатора<br>25 минут!" ?>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-s3YpLz7GSW">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-5 col-md-6">
                <font color="#232323"><strong>
                    <?= ($lang == 'ua') 
                        ? "Прорахуйте вартість замовлення <br>прямо зараз!" 
                        : "Просчитайте стоимость заказа <br>прямо сейчас!" ?>
                </strong></font>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content8 cid-s3YpLA4DPN">
    <div class="container">
        <div class="media-container-row title">
            <div class="col-12 col-md-8">
                <div class="mbr-section-btn align-center">
                    <a class="btn btn-success display-5" href="tel:<?= $settings['tel_one_link'] ?>">
                        <span class="mbri-touch mbr-iconfont mbr-iconfont-btn"></span>
                        <?= ($lang == 'ua') ? "Викликати евакуатор " . $loc['in_city'] : "Вызвать эвакуатор " . $loc['in_city'] ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>