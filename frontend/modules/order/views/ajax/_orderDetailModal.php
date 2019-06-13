<?php

use yii\bootstrap\Modal;
$header = $this->render('_header');

$modalOptions = [//    'complexImage'=>$complexImage,
                 'id'            => 'order_infoModal',
                 //                 'options'       => ['class' => $cartClass],
                 'clientOptions' => ['show' => true,],
                 'size'          => 'modal-lg',
                 'closeButton'   => false,

                 'header' => $header,
//                 'footer' => $controlPanel,
];

Modal::begin($modalOptions);
echo $orderTable['tables'];
?>
    <div style="display: none"><?php
        print_r($orderTable);
   ?> </div>
<?
foreach ($drivers as $key => $driver) {
    $selected=$orderTable['data']['0']['driver_id']==$driver['id']?"selected":'';
    $select_drivers.='<option data-id="' . $driver['id'] . '" '.$selected.'>' . $driver['worker_name'] . '</option>';
}

echo '<div class="btn btn-warning editOrder" >Изменить заказ</div>';
echo '<div class="cartTime cart">
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
                       value="'.date('H:i',strtotime($orderTable['data']['0']['delivery_time'])).'"
                />
                <span class="glyphicon form-control-feedback"></span>
            </div>
        </div>
    </div>';

echo '<div class="cartDriver cart">
        <div class="orderInfoLabel">Водитель</div>
        <div class="input-group orderInfoValue">

            <select class="form-control">'.

    $select_drivers


            .'</select>
        </div>
    </div>';
Modal::end();