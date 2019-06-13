<?php

namespace backend\modules\dishes\models;

use Yii;
use yii\base\Model;

class UploadImage extends Model
{
    public $image;

    public function rules()
    {
        return [
            [['image'], 'safe'],
            [['image'],'file','extensions'=> ['png','jpg']],

        ];
    }
}