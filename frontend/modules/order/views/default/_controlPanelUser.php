<?php

use backend\controllers\Svg;

$svg = new Svg;
?>


<div class="orderInfoBox addingBox client">
    <form role="form" class="form-horizontal">
        <div class="cartClientName cart">
            <div class="orderInfoLabel">Имя:</div>
            <div class="orderInfoValue">
                <div class=" formInputBox inputClientNameBox"
                     divVal="input" inpSelVal="clientName" minLenght="6">
                    <input class="clientName firm form-control"
                           pattern="^[\D\d]{3,}$"
                           type="text" placeholder=""
                           name="clientAddr"
                           data-placement="left"
                           value=""
                           data-content="Имя должно состоять не менее чем из 3 символов"/>
                    <span class="glyphicon form-control-feedback"></span>
                </div>

            </div>
        </div>
        <div class="cartFirm cart">
            <div class="orderInfoLabel">Фирма:</div>
            <div class="orderInfoValue">
                <div class=" formInputBox inputFirmBox"
                     divVal="input" inpSelVal="clientFirm" minLenght="6">
                    <input class="clientFirm firm form-control"
                          pattern="^[\D\d]{6,}$"
                           type="text" placeholder=""
                           name="clientAddr"
                           data-placement="left"
                           value=""

                           data-content="Наименование фирмы должно состоять не менее чем из 6 символов"/>
                    <span class="glyphicon form-control-feedback"></span>
                </div>

            </div>
        </div>
        <div class="cartAddress cart">
            <div class="orderInfoLabel">*Адрес:</div>
            <div class="orderInfoValue">
                <div class=" formInputBox inputAddressBox form-group has-feedback"
                     divVal="input" inpSelVal="clientAddr" minLenght="6">
                    <input class="clientAddr address form-control search toSelect"
                           required="required" pattern="^[\D\d]{6,}$"
                           type="text" placeholder=""
                           name="clientAddr"
                           data-placement="left"
                           value=""

                           data-content="Адрес должен состоять не менее чем из 6 символов"/>
                    <span class="glyphicon form-control-feedback"></span>
                </div>
            </div>
        </div>

        <div class="cartTel cart">
            <div class="orderInfoLabel">*Телефон:</div>
            <div class="orderInfoValue">
                <div class="formInputBox clienTelephone form-group has-feedback"
                     divVal="input" inpSelVal="clienTelephone">
                    <input required="required" class="phone form-control inputMain toSelect"
                           type="text" name="clienTelephone"
                           pattern="(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?"
                           placeholder=""
                           data-content="Введите телефон полностью"
                           data-placement="left"
                           value=""
                    />
                    <span class="glyphicon form-control-feedback"></span>
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


    </form>


</div>
<div class="detailControl">
    <div class="btn btnClearCart">Очистить корзину</div>
    <div class="btn btnOrder">Оформить заказ</div>
</div>