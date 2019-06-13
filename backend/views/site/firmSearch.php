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
$status=[
  '0'=> 'Не звонили',
  '1'=> 'Отказ',
  '2'=> 'Подумает',
  '3'=> 'Согласие',
  '4'=> 'Перезвонить',
  '5'=> 'Занято\не дозвонились',
];
if(isset($arChB))
{
    for($i=0;$i<6;$i++)
    {?>
        <input class="form-check-input" type="checkbox"  name="checkbox" data-cr='<?=$i?>' id="call_result<?=$i?>" value="value" <?=in_array($i, $arChB)?'checked':'';?>>
        <label for="call_result<?=$i?>"><?=$status[$i]?></label>
    <?}

}
else
{ for($i=0;$i<6;$i++)
    {?>
    <input class="form-check-input" type="checkbox"  name="checkbox" data-cr='<?=$i?>' id="call_result<?=$i?>" value="value" checked>
    <label for="call_result<?=$i?>"><?=$status[$i]?></label>
    <?}
}?>
<button  type='button' class="searchAply btn btn-info">Найти</button>
<div>Найдено: <?=count($firms)?> фирм</div>
<?php
if ($one_firm) {
    $firms[0] = $firm;
    unset($firm);
}
//print_r($firms);
foreach ($firms as $firm) {
    $name = '';
    $firm_address = [];
    $firm_emails = [];
    $firm_sites = [];
    $firm_spere = [];

    echo '<div class="firms_box">';
    $firm_emails = $firm['firmEmails'];
    foreach ($firm as $key => $val) {
        if ($key == 'name') {
            $name = $firm['name'];
        }if ($key == 'date') {
            $date = $firm['date'];
        }

        if ($key == 'firmAddresses') {

            foreach ($val as $key => $addresses) {
                $firm_address[$addresses['id']]['address'] = $addresses['address'];
            }
        }
        if ($key == 'firmsTels') {
            foreach ($val as $key => $telephone) {
                if (!empty($telephone['telephone'])) {
                    $firm_address[$telephone['address_id']]['telephone'][$telephone['id']]['tel'] = $telephone['telephone'];
                    // $last_key=count($firm_address[$telephone['address_id']]['telephone'])-1;
                    $firm_address[$telephone['address_id']]['telephone'][$telephone['id']]['status'] = $telephone['status'];
                    $firm_address[$telephone['address_id']]['telephone'][$telephone['id']]['comment'] = $telephone['comment'];
                    foreach ($firm_emails as $emails) {
                        if ($emails['tel_id'] != 0 && $emails['tel_id'] == $telephone['id']) {
                            $firm_address[$telephone['address_id']]['telephone'][$emails['tel_id']]['email'][$emails['id']]['email'] = $emails['email'];
                            $firm_address[$telephone['address_id']]['telephone'][$emails['tel_id']]['email'][$emails['id']]['status'] = $emails['status'];
                        }

                    }
                }
            }
        }
        unset($telephone);
//            if($key=='firmEmails')
//            {
//               foreach($val as $key=>$emails)
//                {       
//                       
//                       $firm_emails[$emails['id']]['email']=$emails['email']; 
//                       $firm_emails[$emails['id']]['status']=$emails['status'];
//                      
//                     
//                }
//            }
        if ($key == 'firmSites') {
            foreach ($val as $key => $sites) {
                $firm_sites[$sites['id']] = $sites['site'];
            }
        }
        if ($key == 'firmsSpheres') {
            foreach ($val as $key => $spere) {
                $firm_spere[$spere['id']] = $spere['sphere'];
            }
        }


    }


    $this_firm['name'] = $name;
    $this_firm['date'] = $date;
    $this_firm['addr'] = $firm_address;
    $this_firm['sph'] = $firm_spere;
    $this_firm['site'] = $firm_sites;
    $this_firm['email'] = $firm_emails;
//print_r($this_firm);
    echo '<H3>' . $this_firm['name'] . '</H3>'.$this_firm['date'];


    foreach ($this_firm['addr'] as $info) {

        if (is_array($info['telephone'])) {
            ?>
            <p><?= $info['address'] ?></p>
            <table class="table-bordered" style=" width:100%;margin-bottom:10px;">
                <tr>
                    <?
                    if ($info['address'] != '')
                    {
                    ?>
                    <thead>
                    <th>

                        <p>Телефон</p>

                    </th>
                    <th>

                        <p>Статус</p>

                    </th>
                    <th>

                        <p>Коментарий</p>

                    </th>
                    <th>

                        <p>E-mail</p>

                    </th>
                    </thead>
                    <?
                    foreach ($info['telephone'] as $tel_id => $telephone) {
                    if ($telephone != '') {
                    ?>
                <tr>
                    <td style="width:30%">
                        <p><?= $telephone['tel'] ?></p>
                    </td>
                    <td>


                        <select status="<?= $telephone['status'] ?>" data-id="<?= $tel_id ?>" class="selectpicker">
                            <option <?= $telephone['status'] == 0 ? 'selected' : ''; ?>
                                    data-content="<button type='button' class='btn btn-default'>Не звонили</button>">Не звонили
                            </option>
                            <option <?= $telephone['status'] == 1 ? 'selected' : ''; ?>
                                    data-content="<button type='button' class='btn btn-danger'>Отказ</button>">Отказ
                            </option>
                            <option <?= $telephone['status'] == 2 ? 'selected' : ''; ?>
                                    data-content="<button type='button' class='btn btn-warning'>Подумает</button>">Подумает
                            </option>
                            <option <?= $telephone['status'] == 3 ? 'selected' : ''; ?>
                                    data-content="<button type='button' class='btn btn-success'>Согласие</button>">Согласие
                            </option>
                            <option <?= $telephone['status'] == 4 ? 'selected' : ''; ?>
                                    data-content="<button type='button' class='btn btn-warning'>Перезвонить</button>">Перезвонить
                            </option>
                            <option <?= $telephone['status'] == 5 ? 'selected' : ''; ?>
                                    data-content="<button type='button' class='btn btn-warning'>Занято\не дозвонились</button>">
                                Занято\не дозвонились
                            </option>
                        </select>
                    </td>
                    <td style="width:30%"><textarea class="form-control" id="commenttext<?= $tel_id ?>" style="width:100%;"
                                                    placeholder="Коментарий"><?= $telephone['comment'] ?></textarea>
                        <button type="button" class="btn btn-info" data-id="<?= $tel_id ?>" id="commentbtn<?= $tel_id ?>">Изменить</button>
                    </td>
                    <td style="width:30%">
                        <form data-firmid="<?= $firm['id'] ?>" data-telid="<?= $tel_id ?>">
                            <input  class="form-control" type="email" class="form-control" id="addEmail<?= $tel_id ?>">
                            <button type="submit" class="addEmail btn btn-info">Добавить</button>
                        </form>
                        <?
                        if ($telephone['email']) {
                            ?>
                            <table style="    width: 100%;"><?
                                foreach ($telephone['email'] as $email_id => $email) {
                                    $ar_buttonColor = [
                                        '0' => 'btn btn-warning',
                                        '1' => 'btn btn-success',
                                    ];
                                    $ar_buttonText = [
                                        '0' => 'Отправить почту',
                                        '1' => 'Почта отправлена',
                                    ];
                                    ?>
                                    <tr>
                                        <td style="    width: 70%;">
                                            <div style="float:left;"><?= $email['email'] ?></div>
                                        </td>
                                        <td>
                                            <button type="button" data-email="<?= $email['email'] ?>" data-emailid="<?= $email_id ?>"
                                                    class=' send_email btn <?= $ar_buttonColor[$email['status']] ?>'><?= $ar_buttonText[$email['status']] ?></button>
                                        </td>
                                    </tr>
                                    <?
                                } ?>
                            </table>
                        <? } ?>

                    </td>
                </tr>

                <?
                }
                } ?>
                </td>

                </tr>
                <?
                } ?>
            </table>
            <?
        } else {
            $addressWT[] = $info['address'];
        }

    }
    if ($addressWT) {
        echo '<div  class="address_block"><ul>';
        foreach ($addressWT as $adrWT) {

            echo '<li>' . $adrWT . '</li>';

        }
        echo '</ul></div>';
    }
    unset($addressWT);


//        echo '<div style="
//                float: left;
//                margin-right: 20px;
//                margin-left: 20px;
//                margin-bottom: 20px;
//            ">';
//        echo "<h4>Сфера деятельности:</h4>"; 
//        foreach ($this_firm['sph'] as $sph)
//        {
//            if($sph!='')
//            echo '<p>'.$sph.'</p>'; 
//        }
//        echo '</div>';

    echo '<div style="
                float: left;
                margin-right: 20px;
                margin-left: 20px;
                margin-bottom: 20px;
            ">';

    if ($this_firm['site'])
        echo "<h4>Сайт</h4>";
    foreach ($this_firm['site'] as $site) {
        if ($site != '')
            echo '<p><a href="http://' . $site . '">' . $site . '</a></p>';
    }
    echo '</div>';

    echo '<div style="
                float: left;
                margin-right: 20px;
                margin-left: 20px;
                margin-bottom: 20px;
            ">';

    echo "<h4>Email:</h4>";
    // print_r($this_firm['email']);
    ?>

    <!--          <form data-firmid="<?= $firm['id'] ?>" >
            <input type="email" class="form-control" id="addEmail<?= $firm['id'] ?>" >
            <button type="submit"  class="addEmail btn-default">Добавить</button >
          </form>-->
    <?
    foreach ($this_firm['email'] as $email_key => $email) {
        if ($email != '') {
            $ar_buttonColor = [
                '0' => 'btn-warning',
                '1' => 'btn-success',
            ];
            $ar_buttonText = [
                '0' => 'Отправить почту',
                '1' => 'Почта отправлена',
            ];
            ?>
            <div>
                <div style="float:left;"><?= $email['email'] ?></div>
                <button type="button" clas="send_email" data-email="<?= $email['email'] ?>" data-emailid="<?= $email_key ?>"
                        class='btn <?= $ar_buttonColor[$email['status']] ?>'><?= $ar_buttonText[$email['status']] ?></button>
            </div>


            <?
        }
        echo '';
    }
    echo '</div>';


    echo '</div>';


}


?>
<script>
    $('form').on('submit', function () {
        id = $(this).data('firmid');
        tel_id = $(this).data('telid');
        email = $('#addEmail' + tel_id).val();
        form = $(this);
        console.log(email);
        console.log(id);
        console.log(tel_id);

        $.post('/admin/site/add-firm-email', {email: email, id: id, tel_id: tel_id}).success(function (data) {
            $('#addEmail' + tel_id).val('');
            form.after('<div>\
                         <div style="float:left;">' + email + '</div>\
                         <button type="button" data-email="' + email + '" data-emailid="' + data + '"class=" send_email btn btn-warning">Отпарвить почту</button>\
                     </div> ');
        });
        return false;

    });

    $('body').on('click', 'button[id^=commentbtn]', function () {
        id = $(this).data('id');
        text = $('#commenttext' + id).val();
        if (text) {
            $.post('/admin/site/add-comment', {id: id, text: text}).success(function (response) {
                console.log(text);
            });
        }

    });

    $('body').on('click', '.searchAply', function () {

        arChB=[];
        $('[id ^= call_result]').each(function( index, element ) {
            console.log($(element).data('cr'),'$(element).data(\'CR\')');
            console.log($(element).is(':checked') ,'$(element).attr("checked")');
            if($(element).is(':checked'))
            {
                arChB.push($(element).data('cr'));
            }




    });
        $.post('/admin/site/all-firms-search', {arChB: arChB}).success(function (firm_data) {

            $('.wrap>.container').html(firm_data);
            ///$('.firm_cont').after(firm_data);
            $('.selectpicker').selectpicker('refresh');
        });
        console.log(arChB,'arChB');
    });


    $('body').on('click', '.send_email', function () {
            email = $(this).data('email');
            email_id = $(this).data('emailid');
            console.log(email);
            $.post('/admin/site/send-mail-to-firm', {email: email, id: email_id}).success(function (response) {
                console.log(email);
            });
        });

    $('.selectpicker').on('changed.bs.select', function (e) {

        data = [];
        data['id'] = $(this).data('id');
        data['status'] = e.currentTarget.selectedIndex;
        console.log(data);
        $.post('/admin/site/change-tel-status', {
            id: $(this).data('id'),
            status: e.currentTarget.selectedIndex
        }).success(function (response) {
            console.log(response);
        });


    });

</script>