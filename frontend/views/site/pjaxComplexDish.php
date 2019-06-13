<?php
use yii\helpers\Html;
use common\widgets\DishesWidget;
use common\widgets\DishesTypeWidget;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>

<?php
    Pjax::begin(['id' => 'pjaxDishes',
        //'enablePushState' => false,
        'linkSelector' => '.dishTypeLink'
    ]);
      
        $dishBoxHeight=(count($dishes)-count($dishes) % 3)/3;
        
        if(count($dishes) % 3)    
            $dishBoxHeight++;
        
        $dishBoxHeight=$dishBoxHeight*290 - 27*($dishBoxHeight-2)
?>
        <div style='width: <?=3*243;?>px; height: <?=$dishBoxHeight;?>px;   margin: 0 auto;'>
<?php 
            foreach ($dishes as $dish)
            {
                echo DishesWidgetNew::widget(['dish'=>$dish]) ;
            } 
?>
        </div>
<?php 
    Pjax::end();
