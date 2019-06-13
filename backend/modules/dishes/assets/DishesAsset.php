<?php

namespace backend\modules\dishes\assets;

use yii\web\AssetBundle;


class DishesAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/dishes/web';
    public $css = [
        'css/dishes.css',
    ];
    public $js = [
        'js/dishes.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
