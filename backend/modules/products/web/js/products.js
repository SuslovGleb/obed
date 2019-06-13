window.arChecked=[];
$(document).ready(function () {
    changeFloatInput();
    changeInputs();
    applyInputChanges();

});
function changeFloatInput()
{
    $('body').on('change', '.changeOnclick input', function () {
        weight=$(this).val();
        console.log(weight,'weight');
        $(this).val(weight.replace(",","."));

    });

}


function changeInputs()
{
    $('body').on('click', '.changeOnclick', function () {
        if (!$(this).hasClass('wrighting')) {

            $('.changeOnclick.wrighting').each(function(){
                tdValue='';
                if($(this).hasClass('wrighting')&& $(this).attr('changetype')!="checkBoxChange")
                {
                    if($(this).find('input').length)
                    {
                        tdValue = $(this).find('input').val();

                    }
                    else if($(this).find('select').length)
                    {
                        tdValue = $(this).find('select option:selected').val();
                    }

                    $(this).html(tdValue);
                    $(this).removeClass('wrighting');
                }
            });
            if($(this).attr('changetype') != 'checkBoxChange')
            $(this).addClass('wrighting');



            btnOk='<div class="applyDishChanges btn glyphicon glyphicon-ok" style="\n' +
                '    margin-right:  15px;\n' +
                '"></div>';

            if ($(this).attr('changetype') == 'inputChange') {
                value=$(this).text();
                value=value.replace(/\"/g, "&quot;");

                if(($(this).attr('changeattr')=='cost')||($(this).attr('changeattr')=='weight'))
                {
                    inputNum='<input ' +
                        'type="number"' +
                        'class="form-control"' +
                        'style="\n' +
                        '    width:  75px;\n' +
                        '    float:  left;\n' +
                        '"' +
                        'value="'+value+'">';
                    thisInput=inputNum;
                }
                else
                {
                    inputText='<input ' +
                        'class="form-control"' +
                        'style="\n' +
                        '    width:  90%;\n' +
                        '    float:  left;\n' +
                        '"' +
                        'value="'+value+'">';
                    thisInput=inputText;
                }

                $(this).html(thisInput+btnOk);

                $(this).find('input').focus();

            }
            else if ($(this).attr('changetype') == 'selectChange') {
                thisText=$(this).text();
               select_val= $('select[name="DishesSearch[type]"]').parent().html();
                select_val= select_val.replace('<option value=""></option>',"")
                select_val= select_val.replace('<select ','<select style="\n' +
                    '    width: 105px;\n' +
                    '    float:  left;\n' +
                    ' "')
                select_val= select_val.replace('<option value="'+thisText+'">'+thisText+'</option>','<option value="'+thisText+'" selected>'+thisText+'</option>')
                $(this).html(select_val+'<div class="applyDishChanges btn glyphicon glyphicon-ok" style="\n' +
                    '    margin-right:  15px;\n' +
                    '"></div>');

                $(this).find('select').focus();
            }

        }
        // else
        // {
        //     if($(this).attr('changetype')!="checkBoxChange")
        // }
        // $(this).removeClass('wrighting');

    });
}


function applyInputChanges()
{
    $('body').on('click','.applyDishChanges',function()
    {
        BTN=$(this);
        changeattr=BTN.closest('td').attr('changeattr');
        product_id=BTN.closest('tr').attr('data-key');


        if(BTN.siblings().is('input'))
        {
            dVal=BTN.siblings().val();
        }

        if(BTN.siblings().is('select'))
        {
            dVal=BTN.siblings().find('option:selected').val();
        }

        data_post = {
            'changeattr':   changeattr,
            'product_id':      product_id,
            'dVal':         dVal,

        };
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();


        $.post('/admin/products/ajax/update-product', data_post).done(function (data) {
            BTN.closest('td').text(dVal).removeClass('wrighting');
            console.log(data,'data');
        });

    });
}



