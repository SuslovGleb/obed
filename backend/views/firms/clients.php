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
<style>
    td,th{
        border: 1px solid black;
    }
</style>
<?php

$firm_num=0;
//var_dump($firms);
?>

<table>

    <?php
foreach ($firms as $firm) {

    $firm_num++;
    $firm_name = $firm['name'];
    $adr_count = 0;
    $tels_count=count($firm['firmsTels']);

    ?>
    <tr>
        <th rowspan="<?= $tels_count + 2; ?>"><?= $firm_num ?></th>
        <th colspan="4"><?= $firm_name ?></th>
    </tr>
    <?
    foreach ($firm['firmAddresses'] as $arAddress) {


        $tels_count = 0;
        $adr_id = (int)$arAddress['id'];
        $adr_name = $arAddress['address'];
        foreach ($firm['firmsTels'] as $arTels) {
            if ((int)$arTels['address_id'] == (int)$adr_id) {
                $tels_count++;
            }
        }
        ?>
        <tr>
        <?php

            ?>
            <td rowspan="<?= $tels_count ?>"><?= $adr_name ?></td>
            <?php

        foreach ($firm['firmsTels'] as $arTels) {
            if ((int)$arTels['address_id'] == (int)$adr_id) {
                ?>
                <td><?= $arTels['telephone'] ?></td>
                <td>Коментарий</td>
                <td>Добавить</td>
                </tr>
                <?php
            }
        }
        ?>
        <?php


    }

?>
    <tr>
        <td colspan="4">Подвал</td>
    </tr>
    <?php

}

?>


    </table>
