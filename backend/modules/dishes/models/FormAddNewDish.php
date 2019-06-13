<?php
namespace backend\modules\dishes\models;

use yii\base\Model;
use yii\web\UploadedFile;
use yii;

class FormAddNewDish extends Model
{
    /**
     * @var UploadedFile
     */
    public $image;
    public $name;
    public $weight;
    public $cost;
    public $type;
    public $path;

    public function rules()
    {
        return [
            [['name','weight','cost','type'], 'required'],
            [['image'], 'file',/* 'skipOnEmpty' => false,*/ 'extensions' => 'png, jpg'],
            [['name','type'], 'string'],
            [['weight','cost'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'imageFile' => 'Изображение',
            'weight' => 'Вес',
            'cost' => 'Цена',
            'type' => 'Тип',

        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->image->saveAs( Yii::$app->basePath . '/uploads/' . $this->image->baseName . '.' . $this->image->extension);
            return true;
        } else {
            return false;
        }
    }
}