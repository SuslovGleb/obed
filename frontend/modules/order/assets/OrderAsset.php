<?php

namespace frontend\modules\order\assets;

use yii\web\AssetBundle;


class OrderAsset extends AssetBundle
{
    public $sourcePath = '@frontend/modules/order/web';
    public $css = [
        'css/order.css',
    ];
    public $js = [
        'js/order.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
