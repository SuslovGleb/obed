<div class=" print orderPrintContain">
    <div class="toggleOrders glyphicon glyphicon-chevron-right"></div>
    <?php

    foreach ($orderTable['data'] as $key => $order) {
        $delivery_time=$order['delivery_time'];
        $address=$order['address'];
        $firm=$order['firm'];
        $telephone=$order['telephone'];
        ?>
        <div class="order">
            <div class="date" style="position:absolute;right:20px"><?=date('d-m-Y',strtotime($delivery_time))?></div>
            Адрес: <?=$address?><br>
            Фирма: <?=$firm?><br>
            Телефон: <?=$telephone?><br>
        </div>
        <?php
    }
    ?>
</div>
