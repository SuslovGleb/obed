window.arChecked=[];
$(document).ready(function () {
    changeDish_product_weight();
    changeDishInputs();
    // AddNewDish();
    changeSelected();
    applySelected();
    undoSelected();
    updateCheckBoxClick();
    applyDishChanges();
    addOneInput();
    addOneSemifinished();
    applyProduct();
    searchProduct();
    beforeDishSubmit();
    weighCalculation();
});
function changeDish_product_weight()
{
    $('body').on('change', 'input.dish_product_weight', function () {
        weight=$(this).val();
        console.log(weight,'weight');
        $(this).val(weight.replace(",","."));

    });

}


function changeDishInputs()
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

function changeSelected()
{
    $('body').on('change',':checkbox[name^=selection]',function()
    {
        window.arChecked=[];
        $(':checkbox[name^=selection]').each(function(i)
        {
            if($(this).prop( "checked"))
            {
                window.arChecked.push($(this).val());


            }

        });
        if(arChecked.length<1)
        {
            $('.selectsBox').addClass('thisNotActive').removeClass('thisActive');

            $('.selectsBox input:checked').prop('checked', false);
            $('.selectsBox input').attr('disabled','disabled');


            $('.selectsBox .applyDayChanges').attr('disabled','disabled');
            $('.selectsBox .undoDishChanges').attr('disabled','disabled');
        }
        else
        {
            $('.selectsBox').addClass('thisActive').removeClass('thisNotActive');
            $('.selectsBox input').removeAttr('disabled');
            $('.selectsBox .applyDayChanges').removeAttr('disabled');

        }
        // console.log(arChecked);

    });
}

function updateCheckBoxClick()
{
    $('body').on('click','td[changetype="checkBoxChange"] .input-check',function () {

        dish_id=$(this).closest('tr').attr('data-key');
        flag=$(this).find('input').prop('checked');
        changeattr=$(this).closest('td').attr('changeattr');

        arDays={};
        arDays[dish_id]={};
        arDays[dish_id][changeattr]=flag;
        postUpdateDays(arDays);

    });
}
function undoSelected() {
    $('body').on('click', '.undoDishChanges', function () {

        $.each( $('.needUpdate'), function( index, value ) {
            $(this).click();
        });

    });
}

function applySelected()
{
    $('body').on('click','.applyDayChanges',function()
    {
        Mon=$('.selectsBox #Mon').prop('checked');
        Tue=$('.selectsBox #Tue').prop('checked');
        Wed=$('.selectsBox #Wed').prop('checked');
        Thu=$('.selectsBox #Thu').prop('checked');
        Fri=$('.selectsBox #Fri').prop('checked');
        active=$('.selectsBox #active').prop('checked');

        arChecks= {
            'Mon': Mon,
            'Tue': Tue,
            'Wed': Wed,
            'Thu': Thu,
            'Fri': Fri,
            'active': active

        }
        arDays={};
        $.each( window.arChecked, function( index, value ) {
            arDays[value]={};
            row= $('.grid-view table tr[data-key="'+value+'"]');
            $.each( arChecks, function( arChecksInd, arChecksValue ) {

                checBox=row.find('td[changeattr="'+arChecksInd+'"] input');

                if(checBox.attr('default-check')==arChecksValue)
                {
                    checBox.removeClass('needUpdate');
                }
                else
                {
                    arDays[value][arChecksInd]={};
                    arDays[value][arChecksInd]=arChecksValue;
                    // arDays[value]['weight']=row.find('td[changeattr=weight]').text();
                    // arDays[value]['cost']=row.find('td[changeattr=cost]').text();
                    checBox.addClass('needUpdate');
                }

                if(checBox.prop('checked')!=arChecksValue)
                {
                    checBox.prop('checked',arChecksValue);
                }

            });


            });
        delete arDays["1"];
        $('.undoDishChanges').removeAttr('disabled');


        postUpdateDays(arDays)
    });


}

function postUpdateDays(arDays)
{
    console.log(arDays,'arDays');
    data_post = {};
    //     'arDays{}': arDays,
    // };
    data_post[yii.getCsrfParam()] = yii.getCsrfToken();
    data_post['arDays[]'] = arDays;

    $.post('/admin/dishes/ajax/update-days', data_post).done(function (data) {
        console.log(data,'data');
    });
}

function applyDishChanges()
{
    $('body').on('click','.applyDishChanges',function()
    {
        BTN=$(this);
        changeattr=BTN.closest('td').attr('changeattr');
        dish_id=BTN.closest('tr').attr('data-key');


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
            'dish_id':      dish_id,
            'dVal':         dVal,

        };
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();


        $.post('/admin/dishes/ajax/update-dish', data_post).done(function (data) {
            BTN.closest('td').text(dVal).removeClass('wrighting');
            console.log(data,'data');
        });

    });
}
function AddNewDish()
{
    $(document).on("beforeSubmit", "#new-dish", function () {
        // send data to actionSave by ajax request.
        // return false; // Cancel form submitting.
    });

    // $('body').on('click','#new_dish .submit',function()
    // {
    //     form=$('form#new_dish');
    //     console.log( form.serialize(),' form.serialize();');
    //     form_data=new FormData(form[0]);
    //     console.log(form_data,'form_data');
    //     dishname=form.find('input[name=name]').val();
    //     dishweight=form.find('input[name=weight]').val();
    //     dishcost=form.find('input[name=cost]').val();
    //     dishtype=form.find('option:selected').val();
    //
    //     image=form.find('input[name=image]')[0].files[0];
    //
    //     data_post = {
    //         'name':         dishname,
    //         'weight':       dishweight,
    //         'cost':         dishcost,
    //         'type':         dishtype,
    //         'image':        image
    //
    //     };
    //     data_post[yii.getCsrfParam()] = yii.getCsrfToken();
    //     console.log(data_post,'data_post');
    //     $.post('/admin/dishes/ajax/upload-image', data_post).done(function (data) {
    //
    //         console.log(data,'data');
    //     });
    //
    // });

}

function beforeDishSubmit()
{
    $('.newDishBox form').on('beforeSubmit', function (e) {
        // if (!confirm("Everything is correct. Submit?")) {
        //     return false;
        // }
        $('.newDishBox form').find('input').prop("disabled", false);
        return true;
    });

}
function searchProduct()
{
    $('body').on("keyup focus",'input.dish_product', function (event) {
        url = '';

        minLen=2;
        $(this).addClass('writing');
            url = '/admin/dishes/ajax/find-product';
            response_val = 'product_name';


        input = $(this);
        searchWord = $(this).val();
        post_data = [];
        post_data = {'searchWord': searchWord};
        post_data[yii.getCsrfParam()] = yii.getCsrfToken();

        aComplite($(this), url, post_data, response_val,minLen)

    });
}

function applyProduct()
{
    $('body').on('click','.applyProduct',function()
    {
        if($(this).hasClass('glyphicon-remove'))
        {

            $(this).closest('.form-group').remove();
        }
        else
        {
            $(this).removeClass('glyphicon-ok').addClass('glyphicon-remove');
            $(this).siblings('input.dish_product').attr('disabled','disabled');
            $('.productsBox').show();
        }

    });
}

function weighCalculation() {
    $('body').on('change','.weighCalculation input,.weighCalculation select',function(){
        if($(this).is('input'))
        {
            select=$(this).siblings('select');
        }
        else
        {
            select=$(this);
        }
        select.closest('.weighCalculation').find('.btnCalculateWeight').remove();
        select.after('<button class="btn btn-primary btnCalculateWeight">Перерасчитать</button>');

    });
    $('body').on('click','.btnCalculateWeight',function()
    {
        weightCo=parseInt($(this).siblings('select').val());
        weight=weightCo*parseInt($(this).siblings('input').val());

       if(!$(this).closest("#semifinished").length)
       {
           dishWeight=parseInt($('#dishcost-weight').val());
           // console.log(weight,'weight');
           // console.log(weightCo,'weightCo');
           // console.log(dishWeight,'dishWeight');

           $('.form-group.addProduct').each(function(){

               product_weight=parseFloat($(this).find('.dish_product_weight').val());
               product_weight=product_weight*weight/dishWeight;

               $(this).find('.dish_product_weight').val(product_weight);
           });
           $(this).siblings('input').val(dishWeight);
           $(this).siblings('select').val('1');
       }
       else
       {
           dishWeight=parseInt($('#semifinished').find('h2').attr('dishWeight'));
           $("#semifinished p.semifinishedproduct").each(function(){

               product_weight=parseFloat($(this).find('span.prodWeight').text());
               product_weight=product_weight*weight/dishWeight;

               $(this).find('span.prodWeight').text(product_weight);
               $('#semifinished').find('h2').attr('dishWeight',weight);
               prodId=$(this).attr('prod-id');
               prodName=$(this).find('span.prodName').text();
               flag=false;
               $('.form-group.addProduct').each(function(){
                    if($(this).attr('product_id')==prodId)
                    {
                        $(this).find('.dish_product_weight').val(parseFloat($(this).find('.dish_product_weight').val())+parseFloat(product_weight));
                        flag=true;
                    }
               });
               if(!flag)
               {
                   console.log(prodId,'prodId');
                   console.log(prodName,'prodName');
                   console.log(product_weight,'product_weight');
                  $('.saveBtnGroup').before('<div class="form-group addProduct" product_id="'+prodId+'">\n' +
                      '                    <label>Продукт: </label><input type="text" value="'+prodName+'" class="form-control dish_product" name="products['+prodId+']" maxlength="150">\n' +
                      '                    <label>Вес: </label> <input value="'+product_weight+'" type="text" class="form-control dish_product_weight" name="product_weight['+prodId+']" maxlength="150">\n' +
                      '                    <div class="divBtn glyphicon applyProduct glyphicon-ok"></div>\n' +
                      '                </div>');
               }

           });
           $('#semifinished').remove();
       }


        $(this).remove();
    });
}

    function addOneInput(){
    $('body').on('click','.addOneInput',function()
    {
        $(this).closest('.form-group').after('<div class="form-group addProduct" >\n' +
            '        <label>Продукт: </label><input type="text" class="form-control dish_product" name="products[]" maxlength="150">\n' +
            '        <label>Вес: </label> <input type="text" class="form-control dish_product_weight" name="product_weight[]" maxlength="150">\n' +
            '        <div class="divBtn glyphicon applyProduct glyphicon-ok"></div>\n' +
            '    </div>');
        $(this).closest('.form-group').next().find('.dish_product').focus();
        $(this).closest('.form-group').hide();
    });


}

function addOneSemifinished(){
    $('body').on('click','.addOneSemifinished',function()
    {
        data_post = {};
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();

        $.post('/admin/dishes/ajax/find-semifinished', data_post, function (request, response) {
        }, 'json').done(function (data) {

            semifinished='';
            $('#semifinished').remove();

            $.each( data, function( index, value ) {
                semifinished+='<li sem-id="'+value.id+'">'+value.name+'</li>'
            });
            $('.dishes-form.newDishBox').after('<div id="semifinished" class="newDishBox"><ul>'+semifinished+'</ul></div>');

        });

    });
    $('body').on('click','#semifinished li',function() {

        id=$(this).attr('sem-id');
        name=$(this).text();
        semifinished='';
        data_post = {
             'id':   id,


        };
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();

        $.post('/admin/dishes/ajax/find-semifinished-products', data_post, function (request, response) {
        }, 'json').done(function (data) {
            weight=data.dish[0].weight;
            $.each( data, function( index, value ) {
                if(index!='dish') {
                    if(parseFloat(weight)<parseFloat(value.weight))
                    {
                        weightSpan='<span class="prodWeight warning">'+value.weight+'</span>';
                    }
                    else
                    {
                        weightSpan='<span class="prodWeight">'+value.weight+'</span>';
                    }
                    // semifinished+='<li prod-id="'+value.product_id+'"><span class="prodName"></span><span class="prodName">'+value.weight+'</span></li>';
                    semifinished += '<p class="semifinishedproduct" prod-id="' + value.product_id + '"><span class="prodName">' + value.product.product_name + '</span>, Вес:'+weightSpan+'г</p>';
                }
            });
            console.log(data,'data');
            calculateTxt='<div class="form-group productsBox weighCalculation">\n' +
                '        <p style="float:  left;">Расчет на </p>\n' +
                '        <input type="number" value="'+weight+'" style="\n' +
                '                            float:  left;\n' +
                '                            margin-left:  8px;\n' +
                '                            width:  100px;">\n' +
                '        <select style="\n' +
                '                float:  left;\n' +
                '                margin:  0;\n' +
                '                height: 28px;">\n' +
                '            <option value="1" selected="">г</option>\n' +
                '            <option value="1000">кг</option>\n' +
                '        </select>\n' +
                '    </div>';
            updateSemiproductHtml='<a href="/admin/dishes/default/update?id='+id+'" data-pjax="0" target="_blank"><span class="glyphicon glyphicon-pencil" title="Update"></span></a>';
            $('#semifinished').html("<h2 dishWeight='"+weight+"'>"+name+updateSemiproductHtml+"</h2>"+calculateTxt+semifinished);

            // console.log(semifinished,'data');
        });
    })
}


function aComplite(input, url, post_data, response_val,minLen) {
    input.autocomplete({
        source: function (request, response) {
            $.post(url, post_data, function (request, response) {
            }, 'json').done(function (data) {

                response(data);
            });
        },
        minLength: minLen,
        select: function (event, ui) {
            item = ui.item;
            input.removeClass('writing');

            input.val(item[response_val]);
            input.attr('name', 'products['+item.id+']');
            input.siblings('input.dish_product_weight').attr('name', 'product_weight['+item.id+']');
            input.siblings('div').click();


            return false;

        },
        focus: function (event, ui) {
            item = ui.item;
            return false;
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        console.log(response_val, 'response_val');
        console.log(item, 'item');

        return $("<li>").attr(response_val + 'id', item.id)
            .append("<div>" + item[response_val] + "</div>")
            .appendTo(ul);


    }
}
