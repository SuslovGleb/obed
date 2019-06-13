$(document).ready(function () {
    addComplex();
    chooseDish();
    showDishes();
    modalHide();
});






function addComplex(){
    $('body').on('click','.btnAddComplex',function() {
        if(!$(this).hasClass('inactive')) {
            arComplex = [];
            arComplexDishes = [];

            ingridients = $(this).closest(".complex-box").find('.ingridient');

            complex_box=$(this).closest('.complex-box');
            complex_id =  complex_box.attr('complex_id');
            complex_name =complex_box.attr('complex_name');
            complex_cost =complex_box.attr('complex_cost');

            arComplex['name'] = complex_name;
            arComplex['id'] = complex_id;
            arComplex['cost'] = complex_cost;


            ingridients.each(function (index) {

                dish_name = $(this).attr('dish_name');
                dish_type_name = $(this).attr('dish_type_name');
                dish_id = $(this).attr('dish_id');
                dish_type_id = $(this).attr('dish_type');
                dish_weight = $(this).attr('dish_weight')
                arName = [];


                arName['dish_name'] = dish_name;
                arName['dish_id'] = dish_id;

                if(!$(this).hasClass('hasReplaced')) {
                    arComplexDishes['dish' + dish_type_id] = arName;
                }
                $(this).find('.dish_image').find('img').remove();
                $(this).find('.dish_image').find('svg').show();
                $(this).find('.weight').html('<div class="choose">Вес: ' + dish_weight + 'г</div>');
                $(this).find('.dish_name').text(dish_type_name);

                $(this).attr('dish_id', '');
                $(this).css('background-color', '');
                $(this).find('.dish_name').css('margin-bottom', '');
                // $(element).css('color','');
                $(this).find('.dish_image').css('margin-left', '');
                $(this).removeClass('choosen');
                $(this).removeClass('hasReplaced');
                $(this).removeAttr('replacedby');
                $(this).removeAttr('dish_replacement');
                // console.log(element);
            });
            $(this).addClass('inactive');
            arComplex['dishes'] = arComplexDishes;
            complex_name = arComplex['name'];
            complex_id = arComplex['id'];
            complex_cost = arComplex['cost'];
            attr = 'complex_attr="';
            dishesSumName = '';


            attr_val='';
            for (var value in arComplex['dishes'])
            {
                if(value) {
                    attr_val += arComplex['dishes'][value]['dish_id'] +',';
                    //attr += value + '="' + arComplex['dishes'][value]['dish_id'] + '" ';
                    dishesSumName += arComplex['dishes'][value]['dish_name'] + ' + ';
                }
            }

            dishesSumName=dishesSumName.substring(0, dishesSumName.length - 3);
            final_name=complex_name+' ('+dishesSumName+')';

            attr_val+=complex_id;
            attr+=attr_val+'"';



            num= $('#order_table tr').length;
            count=complex_box.find('.dishInputNum').val()
            // count=1;
            sum= parseInt(count) * parseInt(complex_cost);
            can_add_row=1;
            final_cost_text=complex_cost;
            final_cost_val=complex_cost;
            is_false=0;
            $('tr:not(:first)', $('#order_table')).each(function(column,tr)
            {
                console.log(attr_val,'attr_val');
                console.log($(this).attr('complex_attr'),'$(this).attr(\'complex_attr\')');

                if(attr_val==$(this).attr('complex_attr'))
                {
                    addDishToExist(tr);

                }

            });

            AddRowToDetailCart(can_add_row, attr, num, final_name, final_cost_text, count, final_cost_val);


            complexBox.find('.complexChooseText').show();
            complexBox.find('.complexBuyCount ').hide();


        }
    });
}

function chooseDish(){
    $('body').on('click','.dish_box .btnAddDish',function() {

        ingridient=             $('.ingridient.active');
        dish_type=              ingridient.attr('dish_type');
        ingridient_box=         ingridient.closest('.complex-ingridients');
        dish_box=               $(this).closest('.dish_box');
        dish_name=              dish_box.attr('value');
        dish_id=                dish_box.attr('dish_id');
        dish_weight=            dish_box.find('.dish_weight').text();
        dish_image=             dish_box.find('.dish_image').attr('value');
        dish_replacement=       dish_box.attr('replacement');


        complexIngridients  =ingridient.closest('.complex-ingridients');
        complexBox          =ingridient.closest('.complex-box');
        complexIngridients.attr('data-type'+dish_type,dish_id);


        if(dish_replacement>0)
        {                //complexIngridients.attr('data-type'+dish_replacement,'');
            // console.log(dish_replacement);
            ingridient.siblings('.ingridient[dish_type='+dish_replacement+']').addClass('hasReplaced');
            ingridient.siblings('.ingridient[dish_type='+dish_replacement+']').attr('replacedBy',dish_type);
            ingridient.attr('dish_replacement',dish_replacement);
        }
        else
        {
            //console.log(ingridient_box.find('.ingridient'));
            ingridient_box.find('.hasReplaced').each(function()
            {
                if(dish_type==$(this).attr('replacedBy'))
                {
                    $(this).removeClass('hasReplaced');
                    typeId=$(this).attr('dish_type');
                    replaced_dish_id=$(this).attr('dish_id');
                    complexIngridients.attr('data-type'+typeId,replaced_dish_id);
                }

            });
        }

        ingridient.find('.dish_name').text(dish_name);
        ingridient.attr('dish_name',dish_name);
        lineHeight=parseInt(ingridient.find('.dish_name').css('line-height'),10);
        blockHeight=ingridient.find('.dish_name').height();
        lineCount=Math.round(blockHeight/lineHeight);

        marginTop=lineHeight*(lineCount-1)+15;
        ingridient.find('.weight').html('<div class="choose">'+dish_weight + '</div>');


        if(dish_image)
        {
            ingridient.find('.dish_image').find('svg').hide();
            ingridient.find('.dish_image').find('img').remove();
            ingridient.find('.dish_image').append('<img ' +
                'style="\n' +
                '    width: 100%;\n' +
                '    margin:  0;\n' +
                '    padding:  0;\n' +
                '    margin-left: -20px;\n' +
                // '    margin-top: -'+ marginTop +'px;\n' +
                // '    margin-bottom: -28px;\n' +
                '"src="/images/uploads/dishes/'+dish_image+'">');
            ingridient.find('.dish_name').css('margin-bottom','');
            ingridient.find('.dish_image').css('margin-left',0);
        }
        else
        {
            ingridient.find('.dish_name').css('margin-bottom','-15px');
            ingridient.find('.dish_image').find('svg').show();
            ingridient.find('.dish_image').find('img').remove();
            ingridient.find('.dish_image').css('margin-left','');
        }
        ingridient.attr('dish_id',dish_id);

        ingridient.addClass('choosen');



        ingTotal=complexIngridients.find('.ingridient').length;
        ingChosen=complexIngridients.find('.ingridient.choosen').length;
        ingRepl=complexIngridients.find('.ingridient.hasReplaced').length;



        countChose=ingTotal-ingChosen-ingRepl;
        if(countChose<=0)
        {
            complexBox.find('.btnAddComplex').removeClass('inactive');
            // console.log(countChose,'ingTotal-ingChosen+ingRepl');
            complexBox.find('.complexChooseText').hide();
            complexBox.find('.complexBuyCount ').css('display','table');
            complexBox.find('.complexBuyCount').find('input').val('1');
        }
        else
        {
            complexBox.find('.complexChooseText').show();
            complexBox.find('.complexBuyCount ').hide();
            complexBox.find('.btnAddComplex').removeClass('inactive');
            complexBox.find('.btnAddComplex').addClass('inactive');
            // console.log(countChose,'2222');
        }
        ingridient.removeClass('active');
        $('.ingridient').removeClass('alreadyModal');
        //ingridient.css('background-color','rgb(221, 255, 222)');
        // ingridient.css('color','rgb(1, 93, 11)');
        // $('#myModal').modal('hide');
    });
}
function modalHide() {
    $('[id^="complexModal"]').on('hidden.bs.modal', function () {
        $('.ingridient').removeClass('active'); 
        console.log('sdfsdfsdfsdf');
    });
}
function showDishes() {
    $('body').on('click', '.ingridient', function () {

        // if ( !$(this).hasClass('alreadyModal') && !$(this).hasClass('hasReplaced') ) {
        //     $('.ingridient').addClass('alreadyModal');
        //     $('.ingridient').removeClass('active');
            $(this).addClass('active');
            // ingridient = $(this);

            // if ( !ingridient.hasClass('active') ) {
            //     ingridient.addClass('active');
            //     complex_id   = $(this).attr('complex_id');
            //     dish_type_id = $(this).attr('dish_type');
            //     dish_type_name    = $(this).attr('dish_type_name');
                //
                // post_data                     = [];
                // post_data                     = {
                //     'complex_id'    : complex_id,
                //     'dish_type_id'     : dish_type_id,
                //     'dish_type_name'       : dish_type_name,
                //     'additionalDish'       : 0,
                // };
                // post_data[yii.getCsrfParam()] = yii.getCsrfToken();
                //
                // //console.log(dish_type_id);
                // $.post('/complex/ajax/one-dish-type', post_data).done(function (data) {
                //     $('#myModal').remove();
                //     $('.container').append(data);
                //     $('#myModal').modal('show');
                //     $('#myModal').on('hidden.bs.modal', function () {
                //         ingridient.removeClass('active');
                //     });
                //     // console.log(data);
                // });
            // }
        // }
    });
}



// $('body').on('click','.complexes_block,.complexes_line',function()
// {
//     if(!$(this).hasClass('active'))
//     {
//         $('.complex-box').toggleClass('block');
//         $('.complex-box').toggleClass('line');
//         $('.complexes_line').toggleClass('active');
//         $('.complexes_block').toggleClass('active');
//         view=$(this).attr('view');
//         blocks('.complex-box');
//         $.post( '/complex/ajax/session-complex',{'view':view,'<?= Yii::$app->request->csrfParam ?>' : '<?= Yii::$app->request->getCsrfToken()?>'}).done(function (data) {
//             console.log(data);
//         });
//
//     }
// });