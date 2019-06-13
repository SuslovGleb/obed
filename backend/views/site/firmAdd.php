<?php

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \yii\helpers\Url;
use vova07\imperavi\Widget;

$this->title = 'My Yii Application';
?>
<?php
//$form = ActiveForm::begin([
//    'id' => 'form-input-example',
//    'options' => [
//        'class' => 'form-horizontal col-lg-11',
//        'enctype' => 'multipart/form-data',
//        'style'=>
//            [
//                
//                //"float"=>'left',   
//                //"width"=> 'initial',
//            ]
//    ],
//]);
echo '<button id="handle" type="button" class="btn btn-primary btn-lg">Обработать</button>';

echo '<textarea id="firm_text-parse" style="width:100%;height=350px"></textarea>';
//echo \vova07\imperavi\Widget::widget([
//    'name' => 'redactor',
//    'settings' => [        
//        'lang' => 'ru',
//        'minHeight' => 200,
//        'minWidth' => 200,
//        'validatorOptions' => ['maxSize' => 40000],    //Р С�Р В°Р С”РЎРѓ. РЎР‚Р В°Р В·Р С�Р ВµРЎР‚ РЎвЂћР В°Р в„–Р В»Р В°
//        'imageUpload' => Url::to(['/site/image-upload']),
//        'imageManagerJson' => Url::to(['/site/images-get']),
//        'fileUpload' => Url::to(['/site/file-upload']),
//        'fileManagerJson' => Url::to(['/site/files-get']),
//        'plugins' => [
//            'imagemanager',
//            'filemanager',
//            'table',
//            'clips',
//            'fullscreen',
//            'fontfamily',
//            'fontsize',
//            'fontcolor',
//        ],
//        
//    ],
//  
//]);

?>
    <div class="firm_cont"></div>
    <script>
        $('body').on('click', '#handle', function () {


            text = $("#firm_text-parse").val();
            newText = text.split('---------------');

            $.post('/admin/site/add-firm-from-gis', {'newText': newText}, function (response) {
            }, 'json').success(function (response) {

                if (!response['duble']) {
                    alert('Данная фирма уже есть в базе');
                }
                else {
                    $.post('/admin/site/find-firm-by-id', {id: response['firm_id']}).success(function (firm_data) {
                        $('.firm_cont').after(firm_data);
                        $('.selectpicker').selectpicker('refresh');
                    });

                }
            });


//console.log(newText);
//console.log(name);
//console.log(adress);
//console.log(tel);
//console.log(info);
//
//console.log(n_email);
//console.log(site);
//console.log(vk);
//console.log(name[1]);
//console.log(sphere);
//var result = text.match( /<p>(.*)<\/p>/ig );

        });
    </script>
<?php

//echo HTML::textarea($name='emails', $value = '', 
//        $options = 
//        [
//            'style'=>
//            [
//                "height"=>'300px',
//                "width"=>'300px',
//                "folat"=>'left',
//            ]
//        ]);
//
//
//ActiveForm::end();

?>