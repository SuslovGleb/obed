<?php

namespace backend\modules\products\assets;

use yii\web\AssetBundle;


class ProductsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/products/web';
    public $css = [
        'css/products.css',
    ];
    public $js = [
        'js/products.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
