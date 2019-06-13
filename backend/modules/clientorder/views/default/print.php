<?php

use yii\helpers\Html;
use backend\modules\clientorder\assets\ClientorderAsset;

echo Html::csrfMetaTags();
ClientorderAsset::register($this);

//$orderTables=\backend\modules\clientorder\controllers\DefaultController::orderTableFromModel($modelOrderInfo);
//print_r($test);
if(!$showOnlyTable) {
?>
<div class=" print printContain">
    <a href="#" onclick="window.print();" class="btn btn-primary printDishorder">Распечатать
        <div style="margin-left:20px" class="glyphicon glyphicon-print"></div>
    </a>


    <div class="btn btn-danger markAsPrinted">Отметить как распечатаный</div>
    <?php
    }

    echo $orderTables['tables'];


    ?>
</div>
