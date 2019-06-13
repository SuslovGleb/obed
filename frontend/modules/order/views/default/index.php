<?php

use common\models\User;
use yii\bootstrap\Modal;
//var_dump($drivers);
$header = $this->render(
    '_header', [
    'modal'      => $modal,
    'orderEmpty' => $orderEmpty,
]);

$table = $this->render(
    '_table', [
        'orderEmpty' => $orderEmpty,
    ]);
if (User::isAdmin()||User::isOperator()) {
    $controlPanel = $this->render('_controlPanelAdmin',
        [
            'modelOrders'=>$modelOrders,
            'this'=>$this,
            'session'=>$session,
            'drivers'=>Yii::$app->session->get('drivers'),
        ]
    );
}
else
{
    $controlPanel = $this->render('_controlPanelUser',
        [
            'this'=>$this,
            'session'=>$session,
        ]
    );
}
//$cartClass = '';
//if (!$orderEmpty) $cartClass = 'active';

$modalOptions = [//    'complexImage'=>$complexImage,
                 'id'            => 'basket_detail',
                 //                 'options'       => ['class' => $cartClass],
                 'clientOptions' => ['show' => false,],
                 'size'          => 'modal-lg',
                 'closeButton'   => false,

                 'header' => $header,
                 'footer' => $controlPanel,
];
if ($modal) {
    Modal::begin($modalOptions);
    echo $table;

    Modal::end();
} else {
    echo $header;
    echo $table;
    echo $controlPanel;

}