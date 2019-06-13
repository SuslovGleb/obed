<?php
use yii\helpers\Html;
use common\widgets\ComplexesWidget;
use yii\widgets\Pjax;

//    ComplexModal::end();
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<?php //yii\jui\DatePicker::widget(['name' => 'attributeName', 'clientOptions' => ['defaultDate' => '2014-01-01']]) ?>
<?php
//$script = <<< JS
//$(document).ready(function() {
//    setInterval(function(){ console.log('asd'); }, 500);
//});
//JS;
//$this->registerJs($script);


foreach ($complexes as $complex)
{
    echo  Html::a(ComplexesWidget::widget(['complex'=>$complex]),
         [
           'dishes',
           //'dishType' =>1,
           'complexId'=>$complex['id'],
           'complexName'=>$complex['name'],
           'complexImage'=>$complex['image'],
         ],
         [
             'class' => 'pjaxComplexDish',
             'name' => 'pjaxComplexDish',
         ]
         );
}

//    echo '<pre>';
//         var_dump($dishes);
//    echo '</pre>';

            Pjax::begin(['id' => 'pjaxComplexDish',
                'enablePushState' => false,
                'linkSelector' => '.pjaxComplexDish'
            ]);
            Pjax::end();
?>