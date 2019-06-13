<?php
use yii\helpers\Html;
use common\widgets\DishesWidget;
use common\widgets\DishesTypeWidget;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<?php
//    echo '<pre>';
//        var_dump($dishTypes);
//    echo '</pre>';
?>
<div style='width: 700px;    margin: 0 auto;'>
<?php 
foreach ($dishTypes as $dishType)
{
    echo DishesTypeWidget::widget(['dishType'=>$dishType]);
} 
 ?>
</div>

<div style='width: 730px;    margin: 0 auto;'>
<?php 
foreach ($dishes as $dish)
{
    echo DishesWidget::widget(['dish'=>$dish]) ;
} 
?>
</div>
<?php
exit;