<?php

use common\widgets\DishInputNum;


?>
    <div class="disorderActions">
        <div class="btn btn-primary flagReturn" flagReturn="">Разнорядка</div>
        <div class="btn btn-primary flagReturn" flagReturn="1">Возвраты</div>
        <a href="/admin/disorder/default/dishorder-print" class="btn btn-primary printDishorder">Распечатать</a>
    </div>
    <table class="table table-striped disorderTable <?=$tableClass?>" flagReturn="<?=$flagReturn?>">
        <thead class="thead-striped">
        <th style="    width: 10px;">№</th>
        <th style="
    min-width: 356px;
">Наименование</th>
        <th class="alignCenter">Вес</th>
        <?php
        foreach ($arBuffets as $bufet) {
            echo ' <th class="alignCenter">
                <a 
                href="/admin/disorder/?tableType=3&date=' . $date . '&bufet=' . $bufet['id'] . '"
                onclick="window.open(this.href,\'\', \'scrollbars=1\'); return false;"
                >' . $bufet['bufet_name'] . '</a>
            </th>';
        }
        ?>

        </thead>
        <tbody>

        <?php
        $i=1;
        //print_r($arbufets);
        foreach($arDishes as $typeName=>$dishes)
        {?>
            <tr>
                <td class="dishType" colspan="<?=3+count($arBuffets);?>"><?=$typeName?></td>
            </tr>
            <?php
            foreach ($dishes as $dishKey => $dish)
            {
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=$dish['name']?></td>
                    <td class="alignCenter"><?=$dish['dishCost']['weight']?></td>
                    <?php

                    foreach ($arBuffets as $bufet)
                    {
                        $findCount=0;
                        if($flagReturn)
                        {
                            $foreachArr=$bufet['buffetsReturn'];

                        }
                        else
                        {
                            $foreachArr=$bufet['buffetsOrders'];
                        }
                        foreach ($foreachArr as $order)
                        {
                            if($order['dish_id']==$dish['id'])
                            {
                                $findCount=1;
                                $count=$order['count'];

                            }
                        }

                        $quantId=$bufet['id'].$dish['id'];
                        $inputOptions='flagReturn="'.$flagReturn.'" bufet_id="'.$bufet['id'].'" dish_id="'.$dish['id'].'" date="'.$date.'"';

                        if( $findCount)
                        {
                            $value=$count;
                        }
                        else
                        {
                            $value=0;
                        }
                        $InputWidget= DishInputNum::widget(['inputOptions'=>$inputOptions,'value'=>$value,'quantId'=>$quantId]);
                        echo  '<td>
                                '.$InputWidget.'
                        </td>';

                    }?>

                </tr>
                <?php
                $i++;
            }

        }?>

        </tbody>
    </table>