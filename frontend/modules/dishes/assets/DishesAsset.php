<?php

namespace frontend\modules\dishes\assets;

use yii\web\AssetBundle;


class DishesAsset extends AssetBundle
{
    public $sourcePath = '@frontend/modules/dishes/web';
    public $css = [
        'css/dishes.css',
        'css/bootstrapCheckbox.css',
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
