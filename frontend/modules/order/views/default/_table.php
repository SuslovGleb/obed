<div id="detailTable" style="display: none;">
    <?php
    if (!$orderEmpty) {
        echo Yii::$app->session->get('order');
    } else {

        ?>

        <table id="order_table"class="order_table">
            <tbody>
            <tr>
                <th>№</th>
                <th style="width: 241px;">Наименование</th>
                <th>Цена,руб</th>
                <th>Количество</th>
                <th>Сумма,руб</th>
            </tr>

            </tbody>
        </table>
    <? } ?>
</div>
<div class="orderTotalSum">Итого: 0 руб</div>