<?php
/*
 * $showOnlyTable
 * $orderTables
 *
 */
$ordersIds      =$orderTables['ordersIds'];
//print_r($orderTables);
foreach ($orderTables['data'] as $key => $order) {
    $id  =$order['id'];
    $delivery_time  =$order['delivery_time'];
    $address        =htmlspecialchars($order['address']);
    $address_id     =$order['address_id'];
    $client_id      =$order['client_id'];
    $telephone_id   =$order['telephone_id'];
    $firm_id        =$order['firm_id'];
    $comment        =htmlspecialchars($order['comment']);
    $driver         =$order['driver'];
    $firm           =htmlspecialchars($order['firm']);
    $telephone      =$order['telephone'];
    $table          =$order['content'];
    $total         =$order['total'];

    $orderData=" client-id=\"$client_id\" 
            firm-id=\"$firm_id\" 
            order-id=\"$id\" 
            address-id=\"$address_id\" 
            telephone-id=\"$telephone_id\" 
            firm_name=\"$firm\" 
            address=\"$address\" 
            telephone=\"$telephone\"";
    $ordersIds[]=$order['id'];
    ?>

    <div class="order">
    <?php
    if($deleteBtn) {
        ?>
        <a href="/admin/clientorder/default/print?orderFind=byId&id=<?= $id ?>" onclick="window.open(this.href, '', 'scrollbars=1'); return false;" target="_blank" class="btn btn-primary printDishorder">Распечатать
            <div style="margin-left:20px" class="glyphicon glyphicon-print"></div>
        </a>
        <div class="btn-warning  btn delete APorderBtn" order-id="<?= $id ?>" style="float: right;">Удалить</div>
        <?php
    }
        if(!$forDetailView) {
            ?>
            <div class="date"
                 style="position:<?= !$showOnlyTable ? 'absolute' : '' ?>;right:20px"><?= date('d-m-Y', strtotime($delivery_time)) ?>


                <?php
                if ($showOnlyTable) {
                    echo $address . $comment;
                } ?>
            </div>
            <?php
        }
        if(!$showOnlyTable) {

            $delivery_time = date("H:i",strtotime($delivery_time));
            ?>


            <div class="printOrderInfo">
                Водитель: <?= $driver ?><br>
                Время доставки: <?= $delivery_time ?><br><br><br>
                Адрес: <span style="font-weight: bold;font-size: 14pt;"><?= $address ?></span><?= $comment ?><br>
                Фирма: <span style="font-weight: bold;font-size: 14pt;"><?= $firm ?></span><br>
                Телефон: <span style="font-weight: bold;font-size: 14pt;"><?= $telephone ?></span><br>
            </div>
            <div id="slogan">
                <h2>"Кушать подано"</h2>
                <div id="logoBox">
                    <a href="/dishes"><img src="/images/logo.svg" width="100%" alt="" style="-webkit-filter: drop-shadow(11px 9px 3px rgba(0, 39, 7, 0.5));
                        filter: drop-shadow(11px 9px 6px rgba(0, 39, 7, 0.5));
                        "></a>
                </div>
                <h1>Доставка обедов</h1>
                <h1>8-904-024-61-72</h1>
            </div>
            <?php
        }
        ?>
        <?=$table.$total?>

        <?php
    if(!$showOnlyTable) {
        echo '<div id="commercialBlock" class="print">';
        foreach ($modalCommercial as $commercial)
        {
            echo '<h1>'. $commercial->name,'</h1>';
            echo $commercial->text;
        }
        echo '</div>';
    }

        ?>
    </div>
    <?php


}
echo '<div id="ordersIds" '.$orderData.' ids="'.implode(",", $ordersIds).'"></div>';