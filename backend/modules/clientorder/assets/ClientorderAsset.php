<?php

namespace backend\modules\clientorder\assets;

use yii\web\AssetBundle;


class ClientorderAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/clientorder/web';
    public $css = [
        'css/fonts.css',
        'css/clientorder.css',
        'css/bootstrapCheckbox.css',
    ];
    public $js = [
        'js/clientorder.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
