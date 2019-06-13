<?php
use backend\controllers\Svg;

$svg = new Svg;

?>
<div class="detailInfo">

        <div class="chooseClientBox">
            <div class="input-group">
                <input class="search inputMain form-control" type="text" name="clientChoose"
                       placeholder="Выберите клиента">
                <span class="glyphicon  glyphicon-search"></span>
            </div>
            <div class="btn btn-success findClients">Клиенты</div>
<!--            <div class="clienAddIco btn btn-info">Добавить клиента</div>-->


        </div>
    <?php
//    echo $this->render('_addClientBox',[]);
    ?>

    <?php
    if (Yii::$app->session->has('client_id')) {
        $orderInfoBoxClass = 'orderInfoBox addingBox';
    } else {
        $orderInfoBoxClass = 'orderInfoBox addingBox inactive';
    }
    ?>

</div>
<div class="findClientBox">

</div>
<div class="<?= $orderInfoBoxClass ?>"
     telephone_id="<?= Yii::$app->session->get('telephone_id') ?>"
     address_id="<?= Yii::$app->session->get('address_id') ?>"
     firm_id="<?= Yii::$app->session->get('firm_id') ?>">


    <div class="cartFirm cart">
        <div class="orderInfoLabel">Фирма:</div>
        <div class="orderInfoValue">
            <?php
            if (Yii::$app->session->has('client_id')) {

                echo Yii::$app->session->get('client_firm_name');
            } ?>
        </div>
    </div>
    <div class="cartAddress cart">
        <div class="orderInfoLabel">*Адрес:</div>
        <div class="orderInfoValue">
            <div class=" formSelectBox selectAddressBox  form-group has-feedback"
                 divVal="select" inpSelVal="clientAddr">
                <select class="addressSelect orderSelect form-control">

                    <?php
                    if (Yii::$app->session->has('client_id')) {


                        foreach (Yii::$app->session->get('client_address') as $key => $arAddress) {

                            if ($key == 0) {
                                $inputVal = $arAddress['address']['address'];
                            }
                            $selected = $arAddress['selected_by_default'] == '1' ? 'selected' : '';
                            if ($selected == 'selected') {
                                $inputVal = $arAddress['address']['address'];
                            }
                            echo '<option data-id="' . $arAddress['id'] . '" ' . $selected . '>' . $arAddress['address']['address'] . '</option>';
                        }
                    }
                    ?>

                </select>
                <div class="divBtn glyphicon glyphicon-plus inpToSelectBtn"></div>
                <span class="glyphicon form-control-feedback"></span>
            </div>
            <div class=" formInputBox inputAddressBox form-group has-feedback inactive"
                 divVal="input" inpSelVal="clientAddr" minLenght="6">
                <input class="clientAddr address form-control search toSelect"
                       required="required" pattern="^[\D\d]{6,}$"
                       type="text" placeholder="Адрес"
                       name="clientAddr"
                       data-placement="left"
                       value="<?= $inputVal ?>"

                       data-content="Адрес должен состоять не менее чем из 6 символов"/>

                <div class="divBtn glyphicon inpToSelectBtn addressInput glyphicon-ok"></div>
            </div>
        </div>
    </div>

    <div class="cartTel cart">
        <div class="orderInfoLabel">*Телефон:</div>
        <div class="orderInfoValue">
            <div class="formSelectBox clienTelephone  form-group has-feedback"
                 divVal="select" inpSelVal="clienTelephone">
                <select class="telephoneSelect orderSelect form-control">
                    <?php
                    if (Yii::$app->session->has('client_id')) {

                        foreach (Yii::$app->session->get('client_telephone') as $key => $arTelephone) {
//                                                            $telephone = strrev($arTelephone['client_telephone']);


                            if ($key == 0) {
                                $inputVal = $arTelephone['client_telephone'];
                            }
                            $selected = $arTelephone['selected_by_default'] == '1' ? 'selected' : '';
                            if ($selected == 'selected') {
                                $inputVal = $arTelephone['client_telephone'];
                            }
//                                                            $telephone = '+' . substr($telephone, 0, 1) . '(' . substr($telephone, 1, 3) . ') ' . substr($telephone, 4, 3) . '-' . substr($telephone, 7, 2) . '-' . substr($telephone, 9, 2);
                            echo '<option data-id="' . $arTelephone['id'] . '" ' . $selected . '>' . $arTelephone['client_telephone'] . '</option>';
                        }
                    }
                    ?>


                </select>
                <div class="divBtn glyphicon glyphicon-plus inpToSelectBtn"></div>
                <span class="glyphicon form-control-feedback"></span>
            </div>
            <div class="formInputBox clienTelephone form-group has-feedback inactive"
                 divVal="input" inpSelVal="clienTelephone">
                <input required="required" class="phone form-control inputMain toSelect"
                       type="text" name="clienTelephone"
                       pattern="(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?"
                       placeholder="Телефон клиента"
                       data-content="Введите телефон полностью"
                       data-placement="left"
                       value="<?= $inputVal ?>"
                />
                <div class="divBtn glyphicon inpToSelectBtn addressInput glyphicon-ok"></div>
            </div>
        </div>
    </div>
    <div class="cartComent cart">
        <div class="orderInfoLabel">Коментарий:</div>
        <div class="orderInfoValue">
            <div class="formInputBox clienComent"
                 divVal="input" inpSelVal="clienComent">
                <input  class="form-control inputMain toSelect"
                        type="text" name="clienComent"
                        placeholder=""
                        data-content=""
                        data-placement="left"
                        value=""
                />
            </div>
        </div>
    </div>
    <div class="cartTime cart">
        <div class="orderInfoLabel ">*Желаемое время доставки:</div>
        <div class="orderInfoValue">
            <div class="formInputBox time form-group has-feedback"
                 divVal="input" inpSelVal="time">
                <input required="required" class="time form-control inputMain toSelect"
                       type="text" name="time"
                       pattern="([\d]{2} : [\d]{2})?"
                       placeholder=""
                       data-content="Введите желаемое время"
                       data-placement="left"
                       value=""
                />
                <span class="glyphicon form-control-feedback"></span>
            </div>
        </div>
    </div>


    <div class="cartDriver cart">
        <div class="orderInfoLabel">Водитель</div>
        <div class="input-group orderInfoValue">

            <select class="form-control">
                <?php
                foreach ($drivers as $key => $driver) {
                    echo '<option data-id="' . $driver['id'] . '">' . $driver['worker_name'] . '</option>';
                }

                ?>

            </select>
        </div>
    </div>


</div>
<div class="detailControl">
    <div class="btn-warning btn btnClearCart">Очистить корзину</div>
    <div class="btn-success btn btnOrder ">Оформить заказ</div>
</div>