window.arChecked = [];
$(document).ready(function () {
    changeComplexInputs();
    updateCheckBoxActive();
    applyDish();
    searchDish();
    // AddNewDish();
    // changeSelected();
    // applySelected();
    // undoSelected();
    // updateCheckBoxClick();
    applyComplexChanges();
    addOneInput();
    // applyProduct();
    // searchProduct();
    beforeDishSubmit();
    addDishToComplexFromTabs();
    materialSwitch();
    changeComplexTypeWeight();
});


function changeComplexInputs() {

    $('body').on('click', '.changeOnclick', function () {
        if ( !$(this).hasClass('wrighting') ) {

            $('.changeOnclick.wrighting').each(function () {
                tdValue = '';
                if ( $(this).hasClass('wrighting') && $(this).attr('changetype') != "checkBoxChange" ) {
                    if ( $(this).find('input').length ) {
                        tdValue = $(this).find('input').val();

                    }
                    else if ( $(this).find('select').length ) {
                        tdValue = $(this).find('select option:selected').val();
                    }

                    $(this).html(tdValue);
                    $(this).removeClass('wrighting');
                }
            });
            if ( $(this).attr('changetype') != 'checkBoxChange' )
                $(this).addClass('wrighting');


            btnOk = '<div class="applyComplexChanges btn glyphicon glyphicon-ok" style="\n' +
                '    margin-right:  15px;\n' +
                '"></div>';

            if ( $(this).attr('changetype') == 'inputChange' ) {
                value = $(this).text();
                value = value.replace(/\"/g, "&quot;");

                if ( ($(this).attr('changeattr') == 'price') || ($(this).attr('changeattr') == 'cost') || ($(this).attr('changeattr') == 'weight') ) {
                    inputNum  = '<input ' +
                        'type="number"' +
                        'class="form-control"' +
                        'style="\n' +
                        '    width:  75px;\n' +
                        '    float:  left;\n' +
                        '"' +
                        'value="' + value + '">';
                    thisInput = inputNum;
                }
                else {
                    inputText = '<input ' +
                        'class="form-control"' +
                        'style="\n' +
                        '    width:  90%;\n' +
                        '    float:  left;\n' +
                        '"' +
                        'value="' + value + '">';
                    thisInput = inputText;
                }

                $(this).html(thisInput + btnOk);

                $(this).find('input').focus();

            }
            else if ( $(this).attr('changetype') == 'selectChange' ) {
                thisText   = $(this).text();
                select_val = $('select[name="DishesSearch[type]"]').parent().html();
                select_val = select_val.replace('<option value=""></option>', "")
                select_val = select_val.replace('<select ', '<select style="\n' +
                    '    width: 105px;\n' +
                    '    float:  left;\n' +
                    ' "')
                select_val = select_val.replace('<option value="' + thisText + '">' + thisText + '</option>', '<option value="' + thisText + '" selected>' + thisText + '</option>')
                $(this).html(select_val + '<div class="applyComplexChanges btn glyphicon glyphicon-ok" style="\n' +
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

function addDishToComplexFromTabs() {

    $('body').on('click', '.addDishToComplexFromTabs input', function (event) {
        event.preventDefault();
        event.stopPropagation();

    });

    $('body').on('click', '.addDishToComplexFromTabs', function () {
        if ( !$(this).closest('.complexType').hasClass('disabled') ) {
            dish_id   = $(this).attr('dish_id');
            compl_id  = $(this).attr('complex_id');
            dish_name = $(this).text();

            $('.addDishToComplexFromTabs[dish_id="' + dish_id + '"] input').each(function (i) {
                $(this).prop("checked", !$(this).prop("checked"));

            });
            dish_flag                     = $(this).find('input').prop("checked");
            data_post                     = {
                'changeattr': 'dish',
                'compl_id'  : compl_id,
                'dish_flag' : dish_flag,
                'dish_id'   : dish_id,

            };
            data_post[yii.getCsrfParam()] = yii.getCsrfToken();
            console.log(data_post, 'data_post');

            $.post('/admin/complex/ajax/update-complex', data_post).done(function (data) {

                console.log(data, 'data');
            });
        }
    });

}

function changeComplexTypeWeight() {
    $('body').on('change', '.DishesByDay input.input-number', function () {
        complexType = $(this).closest('.complexType');
       weight= $(this).val();
        type_id     = complexType.attr('type_id');
        complexId     = complexType.attr('complexId');

        data_post                     = {
            'changeattr': 'weight',
            'compl_id'  : complexId,
            'weight' : weight,
            'type_id'   : type_id,

        };
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();
        console.log(data_post, 'data_post');

        $.post('/admin/complex/ajax/update-complex', data_post).done(function (data) {

            console.log(data, 'data');
        });
    });
}
function materialSwitch() {
    $('body').on('click', '.DishesByDay .complexType .material-switch input', function () {
        complexType = $(this).closest('.complexType');
        daynum      = complexType.attr('daynum');
        type_id     = complexType.attr('type_id');
        complexId     = complexType.attr('complexId');
        checked     = $(this).prop("checked");

        data_post                     = {
            'changeattr': 'type',
            'compl_id'  : complexId,
            'type_flag' : checked,
            'type_id'   : type_id,

        };
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();
        console.log(data_post, 'data_post');

        $.post('/admin/complex/ajax/update-complex', data_post).done(function (data) {

            console.log(data, 'data');
        });

        if ( complexType.hasClass('disabled') ) {
            for (i = 0; i <= 4; i++) {
                if ( daynum != i ) {
                    $otherComplexType=$('.complexType[daynum='+i+'][type_id='+type_id+']');
                    $otherComplexType.find('.material-switch input').prop("checked", checked);
                    $otherComplexType.removeClass('disabled');
                }
            }
            complexType.removeClass('disabled');
        }
        else {
            for (i = 0; i <= 4; i++) {
                if ( daynum != i ) {
                    $otherComplexType=$('.complexType[daynum='+i+'][type_id='+type_id+']');
                    $otherComplexType.find('.material-switch input').prop("checked", checked);
                    $otherComplexType.addClass('disabled');
                }
            }
            complexType.addClass('disabled');
        }
    });

}

function applyComplexChanges() {
    $('body').on('click', '.applyComplexChanges', function () {
        BTN        = $(this);
        changeattr = BTN.closest('td').attr('changeattr');
        compl_id   = BTN.closest('tr').attr('data-key');


        if ( BTN.siblings().is('input') ) {
            dVal = BTN.siblings().val();
        }

        if ( BTN.siblings().is('select') ) {
            dVal = BTN.siblings().find('option:selected').val();
        }

        data_post                     = {
            'changeattr': changeattr,
            'compl_id'  : compl_id,
            'dVal'      : dVal,

        };
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();


        $.post('/admin/complex/ajax/update-complex', data_post).done(function (data) {
            BTN.closest('td').text(dVal).removeClass('wrighting');
            console.log(data, 'data');
        });

    });
}


function updateCheckBoxActive() {
    $('body').on('click', 'td[changetype="checkBoxChange"] .input-check', function () {

        compl_id = $(this).closest('tr').attr('data-key');
        flag     = $(this).find('input').prop('checked');

        data_post = {
            'flag'    : flag,
            'compl_id': compl_id,
        };
        //     'arDays{}': arDays,
        // };
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();


        $.post('/admin/complex/ajax/update-active', data_post).done(function (data) {
            console.log(data, 'data');
        });


    });
}

// function changeSelected()
// {
//     $('body').on('change',':checkbox[name^=selection]',function()
//     {
//         window.arChecked=[];
//         $(':checkbox[name^=selection]').each(function(i)
//         {
//             if($(this).prop( "checked"))
//             {
//                 window.arChecked.push($(this).val());
//
//
//             }
//
//         });
//         if(arChecked.length<1)
//         {
//             $('.selectsBox').addClass('thisNotActive').removeClass('thisActive');
//
//             $('.selectsBox input:checked').prop('checked', false);
//             $('.selectsBox input').attr('disabled','disabled');
//
//
//             $('.selectsBox .applyDayChanges').attr('disabled','disabled');
//             $('.selectsBox .undoDishChanges').attr('disabled','disabled');
//         }
//         else
//         {
//             $('.selectsBox').addClass('thisActive').removeClass('thisNotActive');
//             $('.selectsBox input').removeAttr('disabled');
//             $('.selectsBox .applyDayChanges').removeAttr('disabled');
//
//         }
//         // console.log(arChecked);
//
//     });
// }
//
// function updateCheckBoxClick()
// {
//     $('body').on('click','td[changetype="checkBoxChange"] .input-check',function () {
//
//         compl_id=$(this).closest('tr').attr('data-key');
//         flag=$(this).find('input').prop('checked');
//         changeattr=$(this).closest('td').attr('changeattr');
//
//         arDays={};
//         arDays[compl_id]={};
//         arDays[compl_id][changeattr]=flag;
//         postUpdateDays(arDays);
//
//     });
// }


// function undoSelected() {
//     $('body').on('click', '.undoDishChanges', function () {
//
//         $.each( $('.needUpdate'), function( index, value ) {
//             $(this).click();
//         });
//
//     });
// }
//
// function applySelected()
// {
//     $('body').on('click','.applyDayChanges',function()
//     {
//         Mon=$('.selectsBox #Mon').prop('checked');
//         Tue=$('.selectsBox #Tue').prop('checked');
//         Wed=$('.selectsBox #Wed').prop('checked');
//         Thu=$('.selectsBox #Thu').prop('checked');
//         Fri=$('.selectsBox #Fri').prop('checked');
//         active=$('.selectsBox #active').prop('checked');
//
//         arChecks= {
//             'Mon': Mon,
//             'Tue': Tue,
//             'Wed': Wed,
//             'Thu': Thu,
//             'Fri': Fri,
//             'active': active
//
//         }
//         arDays={};
//         $.each( window.arChecked, function( index, value ) {
//             arDays[value]={};
//             row= $('.grid-view table tr[data-key="'+value+'"]');
//             $.each( arChecks, function( arChecksInd, arChecksValue ) {
//
//                 checBox=row.find('td[changeattr="'+arChecksInd+'"] input');
//
//                 if(checBox.attr('default-check')==arChecksValue)
//                 {
//                     checBox.removeClass('needUpdate');
//                 }
//                 else
//                 {
//                     arDays[value][arChecksInd]={};
//                     arDays[value][arChecksInd]=arChecksValue;
//                     // arDays[value]['weight']=row.find('td[changeattr=weight]').text();
//                     // arDays[value]['cost']=row.find('td[changeattr=cost]').text();
//                     checBox.addClass('needUpdate');
//                 }
//
//                 if(checBox.prop('checked')!=arChecksValue)
//                 {
//                     checBox.prop('checked',arChecksValue);
//                 }
//
//             });
//
//
//             });
//         delete arDays["1"];
//         $('.undoDishChanges').removeAttr('disabled');
//
//
//         postUpdateDays(arDays)
//     });
//
//
// }

// function postUpdateDays(arDays)
// {
//     console.log(arDays,'arDays');
//     data_post = {};
//     //     'arDays{}': arDays,
//     // };
//     data_post[yii.getCsrfParam()] = yii.getCsrfToken();
//     data_post['arDays[]'] = arDays;
//
//     $.post('/admin/dishes/ajax/update-days', data_post).done(function (data) {
//         console.log(data,'data');
//     });
// }


// function AddNewDish()
// {
//     $(document).on("beforeSubmit", "#new-dish", function () {
//         // send data to actionSave by ajax request.
//         // return false; // Cancel form submitting.
//     });
//
//     // $('body').on('click','#new_dish .submit',function()
//     // {
//     //     form=$('form#new_dish');
//     //     console.log( form.serialize(),' form.serialize();');
//     //     form_data=new FormData(form[0]);
//     //     console.log(form_data,'form_data');
//     //     dishname=form.find('input[name=name]').val();
//     //     dishweight=form.find('input[name=weight]').val();
//     //     dishcost=form.find('input[name=cost]').val();
//     //     dishtype=form.find('option:selected').val();
//     //
//     //     image=form.find('input[name=image]')[0].files[0];
//     //
//     //     data_post = {
//     //         'name':         dishname,
//     //         'weight':       dishweight,
//     //         'cost':         dishcost,
//     //         'type':         dishtype,
//     //         'image':        image
//     //
//     //     };
//     //     data_post[yii.getCsrfParam()] = yii.getCsrfToken();
//     //     console.log(data_post,'data_post');
//     //     $.post('/admin/dishes/ajax/upload-image', data_post).done(function (data) {
//     //
//     //         console.log(data,'data');
//     //     });
//     //
//     // });
//
// }
//
function beforeDishSubmit() {
    $('.ComplexBox form').on('beforeSubmit', function (e) {
        // if (!confirm("Everything is correct. Submit?")) {
        //     return false;
        // }
        $('.ComplexBox form').find('input').prop("disabled", false);
        return true;
    });

}

function searchDish() {
    $('body').on("keyup focus", 'input.dish', function (event) {
        url = '';

        minLen = 2;
        $(this).addClass('writing');
        url          = '/admin/complex/ajax/find-dishes';
        response_val = 'name';


        input                         = $(this);
        searchWord                    = $(this).val();
        post_data                     = [];
        post_data                     = {'searchWord': searchWord};
        post_data[yii.getCsrfParam()] = yii.getCsrfToken();

        aComplite($(this), url, post_data, response_val, minLen)

    });
}

//
function applyDish() {
    $('body').on('click', '.applyDish', function () {
        if ( $(this).hasClass('glyphicon-remove') ) {

            $(this).closest('.form-group').remove();
        }
        else {
            $(this).removeClass('glyphicon-ok').addClass('glyphicon-remove');
            $(this).siblings('input.dish').attr('disabled', 'disabled');
            $('.productsBox').show();
        }
        if ( $('.ComplexBox').height() < $('.DishesBox').height() ) {
            $('.ComplexBox').css('margin-bottom', $('.DishesBox').height());
        }

    });
}

//
function addOneInput() {
    $('body').on('click', '.addOneInput', function () {
        $(this).closest('.form-group').before('<div class="form-group addDish" >\n' +
            '        <label>Блюдо: </label><input type="text" class="form-control dish" name="dishes[]" maxlength="150">\n' +
            '        <div class="divBtn glyphicon applyDish glyphicon-ok"></div>\n' +
            '    </div>');
        $(this).closest('.form-group').hide();
    });


}


function aComplite(input, url, post_data, response_val, minLen) {
    input.autocomplete({
        appendTo : ".DishesBox",
        source   : function (request, response) {
            $.post(url, post_data, function (request, response) {
            }, 'json').done(function (data) {

                response(data);


            });
        },
        minLength: minLen,
        select   : function (event, ui) {
            item = ui.item;
            input.removeClass('writing');

            input.val(item[response_val]);
            input.attr('name', 'dishes[' + item.id + ']');
            input.siblings('div').click();
            if ( $('.ComplexBox').height() < $('.DishesBox').height() ) {
                $('.ComplexBox').css('margin-bottom', $('.DishesBox').height());
            }

            return false;

        },
        focus    : function (event, ui) {
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
