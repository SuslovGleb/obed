<?php
$allSum = $orderToday['allSum'];
$quantityOrders = count($orderToday['data']);

//if(count($modelOrderInfo)) {

?>
    <div class=" print orderPrintContain driveRight">
        <div class="toggleOrders glyphicon glyphicon-chevron-left"></div>
        <div class="sumByDay">Кол-во заказов: <?= $quantityOrders ?>,средний
            чек: <?= round($allSum / $quantityOrders) ?>р., ИТОГО ЗА ДЕНЬ: <?= $allSum ?>р.
        </div>
        <?php
        if (count($orderActive['data'])) {
            ?>
            <a class="btn-primary btn printAll"
               target="_blank"
               href="/admin/clientorder/default/print?orderFind=allButCall"
               onclick="window.open(this.href, '', 'scrollbars=1'); return false;">Распечатать все</a>
            <?php
        }
        ?>
        <a class="btn-info btn printAll"
           target="_blank"
           href="/admin/clientorder/?date=<?= date('Y-m-d') ?>"
           onclick="window.open(this.href, '', 'scrollbars=1'); return false;">Посмотреть все заказы на сегодня</a>

        <div class="ordersPrintBox">
            <?php

            foreach ($orderActive['data'] as $key => $order) {
                $address = $order['address'];
                $comment = $order['comment'];
                $firm = $order['firm'];
                $telephone = $order['telephone'];
                $id = $order['id'];
                $delivery_time = $order['delivery_time'];
                ?>
                <div class="order">
                    <div class="date"><?= date('d-m-Y', strtotime($delivery_time)) ?></div>

                    Адрес: <?= $address . $comment ?><br>
                    <?= $firm? 'Фирма: '. $firm .'<br>':''; ?>
                    Телефон: <?= $telephone ?><br>

                    <div class="btn-info btn view orderDetails" order-id="<?= $id ?>">Детали</div>
                    <?php
                    if ($order['statusId'] == 1) {
                        ?>
                        <div class="btn-warning  btn accept APorderBtn" order-id="<?= $id ?>">Одобрить</div>

                    <?
                    } else {
                        ?>
                        <a class="btn-primary btn print APorderBtn"
                           target="_blank"
                           href="/admin/clientorder/default/print?orderFind=byId&id=<?= $id ?>"
                           onclick="window.open(this.href, '', 'scrollbars=1'); return false;">Распечатать</a>
                        <div class="btn-warning  btn delete APorderBtn" order-id="<?= $id ?>">Удалить</div>
                    <?
                    } ?>

                </div>
                <?php
            }
            ?>
        </div>
    </div>
<?php
//}
?>