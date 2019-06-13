<?php

namespace backend\modules\complex\assets;

use yii\web\AssetBundle;


class ComplexAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/complex/web';
    public $css = [
        'css/complex.css',
        'css/bootstrapCheckbox.css',
    ];
    public $js = [
        'js/complex.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
