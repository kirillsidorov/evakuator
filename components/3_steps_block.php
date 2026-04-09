<section class="sec">
    <div class="sec-inner">
        <div class="sec-title align-center" style="text-align: center;">
            <?= ($lang == 'ua') ? "Всього 3 кроки для замовлення евакуатора " . ($loc['in_city'] ?? '') : "Всего 3 шага для заказа эвакуатора " . ($loc['in_city'] ?? '') ?>
        </div>
        
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div>
                    <div class="step-title"><?= ($lang == 'ua') ? "Зателефонуйте нам" : "Позвоните нам" ?></div>
                    <p class="step-text">
                        <strong><?= $settings['tel_one_view'] ?><br><?= $settings['tel_two_view'] ?></strong><br>
                        <?= ($lang == 'ua') ? "або залиште заявку на зворотний дзвінок." : "или оставьте заявку на обратный звонок." ?>
                    </p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-num">2</div>
                <div>
                    <div class="step-title"><?= ($lang == 'ua') ? "Уточніть деталі" : "Уточните детали" ?></div>
                    <p class="step-text">
                        <?= ($lang == 'ua') ? "Повідомте диспетчеру ваше місцезнаходження та тип автомобіля." : "Сообщите диспетчеру ваше местоположение и тип автомобиля." ?>
                    </p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-num">3</div>
                <div>
                    <div class="step-title"><?= ($lang == 'ua') ? "Чекайте евакуатор" : "Ожидайте эвакуатор" ?></div>
                    <p class="step-text">
                        <?= ($lang == 'ua') ? "Ваше замовлення прийнято. <br>Середній час очікування — 25 хвилин!" : "Ваш заказ принят. <br>Среднее время ожидания — 25 минут!" ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="band">
  <div class="band-inner">
    <div class="band-title">
        <?= ($lang == 'ua') ? "Прорахуйте вартість замовлення прямо зараз!" : "Просчитайте стоимость заказа прямо сейчас!" ?>
    </div>
    <a href="tel:<?= $settings['tel_one_link'] ?? '' ?>" class="band-cta">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
      <?= ($lang == 'ua') ? "Викликати евакуатор " . ($loc['in_city'] ?? '') : "Вызвать эвакуатор " . ($loc['in_city'] ?? '') ?>
    </a>
  </div>
</div>