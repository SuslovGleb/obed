<?php

namespace backend\modules\disorder\assets;

use yii\web\AssetBundle;


class DisorderAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/disorder/web';
    public $css = [
        'css/disorder.css',

    ];
    public $js = [
        'js/numInputs.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
