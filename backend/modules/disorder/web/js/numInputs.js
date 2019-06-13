$(document).ready(function () {
    inputTableSteps();
    onChangeInput();
    onClickPlusOrMinusInput();
    onChangeDatepicker();
    $('body').on('click','.btn.flagReturn',function()
    {
        thisBtn=$(this);
        flagReturn=$(this).attr('flagReturn');
        console.log(flagReturn,'flagReturn');
        data_post = {
            'date': $('.datePickerBlock input').val(),
            'flagReturn': flagReturn,
        }
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();
        // $.post('/site/add-client',post_data,function (response) {
        // }, 'json').done(function (data) {
        //
        // });
        $.post('/admin/disorder/', data_post).done(function (data) {
            console.log($('body .wrap .nav + .container'));
            thisBtn.closest('.container').html(data);



            $('.datePickerBlock #w0').datepicker($.extend({}, $.datepicker.regional['ru'], {"dateFormat":"yy-mm-dd"}));
            /* To initialize BS3 tooltips set this below */
            $(function () {
                $("[data-toggle='tooltip']").tooltip();
            });;
            /* To initialize BS3 popovers set this below */
            $(function () {
                $("[data-toggle='popover']").popover();
            });
        });

    });
});


function onClickPlusOrMinusInput()
{
    $('body').on('click', '.btn-number', function(e){
        e.preventDefault();

        fieldName = $(this).attr('data-field');
        type      = $(this).attr('data-type');
        var input = $("input[name='"+fieldName+"']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if(type == 'minus') {

                if(currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if(parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if(type == 'plus') {

                if(currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if(parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });
    $('body').on('focusin','.input-number', function(){
        $(this).data('oldValue', $(this).val());
    });
    $('body').on('change', '.input-number',function() {

        minValue =  parseInt($(this).attr('min'));
        maxValue =  parseInt($(this).attr('max'));
        valueCurrent = parseInt($(this).val());

        name = $(this).attr('name');
        if(valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        if(valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the maximum value was reached');
            $(this).val($(this).data('oldValue'));
        }


    });

    $('body').on('keydown',".input-number",function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.shiftKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
}

function onChangeInput()
{
    chosenInput='';
    chosenInputValue='';
    quantClick=0;
    quantClickTimeout=0;

    $('body').on('change','.disorderTable input',function()
    {
        input=$(this);
        if(chosenInput!=input)
        {
            chosenInput=input;
            quantClickTimeout=0;
            quantClick=0;

        }
        quantClick++;
        input.css({'background-color' : '#dfd000'});
        // if(chosenInput.index()==input.index() && chosenInputValue==input.val()) {
        //

        setTimeout(function () {
            quantClickTimeout++;
            if(quantClick==quantClickTimeout) {


                flagReturn = input.attr('flagReturn');
                count = input.val();
                dish_id = input.attr('dish_id');
                inpDate = input.attr('date');
                bufet_id = input.attr('bufet_id');

                data_post = {
                    'flagReturn': flagReturn,
                    'inpDate': inpDate,
                    'dish_id': dish_id,
                    'bufet_id': bufet_id,
                    'count': count,
                }
                data_post[yii.getCsrfParam()] = yii.getCsrfToken();
                $.post('/admin/disorder/default/ajax-order', data_post).done(function (data) {

                    // console.log($('body .wrap .nav + .container'));
                });

                if (input[0].hasAttribute('calculate')) {
                    calculate = input.attr('calculate');

                    quantId = '';
                    if (calculate == 'ordCalculate') {
                        quantId = input.attr('retCalculate');


                    }
                    else if (calculate == 'retCalculate') {
                        quantId = input.attr('ordCalculate');
                    }
                    returnQuant = $('input[retCalculate=' + quantId + ']').val();
                    orderQuant = $('input[ordCalculate=' + quantId + ']').val();
                    price = $('td[costquant=' + quantId + ']').text();
                    retOrdSum = returnQuant*price;
                    OrdSum = orderQuant*price;
                    $('td[retsum=' + quantId + ']').text(retOrdSum);
                    $('td[ordSum=' + quantId + ']').text(OrdSum);

                    console.log(returnQuant,'returnQuant');
                    console.log(orderQuant,'orderQuant');
                    console.log(price,'price');
                    console.log(OrdSum,'OrdSum');
                    console.log(retOrdSum,'retOrdSum');
                    console.log($('td[retsum=' + quantId + ']'),'$(\'td[retsum=\' + quantId + \']\')');
                    console.log($('td[ordSum=' + quantId + ']'),'$(\'td[ordSum=\' + quantId + \']\')');


                    retItogo = 0;
                    ordItogo = 0;
                    prifit = 0;
                    $('td[retSum]').each(function () {

                        retItogo += parseInt($(this).text());

                    });
                    $('td[ordSum]').each(function () {
                        ordItogo += parseInt($(this).text());
                    });
                    prifit=parseInt(ordItogo-retItogo);
                    $('.itogoSum.order').text(ordItogo);
                    $('.itogoSum.return').text(retItogo);
                    $('.itogoSum.profit').text(prifit);

                }



                input.css({'background-color' : '#24df4a'});
                quantClickTimeout = 0;
                quantClick=0;
            }
            // else
            // {
            //     console.log(quantClick,'quantClick');
            //     console.log(quantClickTimeout,'quantClickTimeout');
            //
            //
            // }
        }, 500);

        // }



    });
}

function inputTableSteps()
{
    colCount=0;
    $('tr:nth-child(1) td').each(function () {
        if ($(this).attr('colspan')) {
            colCount += +$(this).attr('colspan');
        } else {
            colCount++;
        }
    });
    $('body').on('keyup', '.disorderTable input',function (event) {

        if ( (event.keyCode >= 37 && event.keyCode <= 40) || event.keyCode ==107|| event.keyCode ==109) {
            thisTd = $(this).closest('td');
            indexTd = thisTd.index();

            thisTr = $(this).closest('tr');
            indexTr = thisTr.index() + 1;
            old_td = $('tr').eq(indexTr).find('td').eq(indexTd);



            input = thisTd.find('input');
            InpValue= parseInt(input.attr('value'));

            if (event.keyCode == 37) {
                if(event.shiftKey === true)
                {

                    InpValue--;
                    if(InpValue<0) InpValue=0;

                    input.attr('value',InpValue);
                    input.val(InpValue).change();
                }
                else
                {
                    indexTd--;
                    route = 'left';
                    // console.log('влево');
                }

            }
            if (event.keyCode == 38) {
                if(event.shiftKey === true)
                {

                    InpValue=InpValue+10;
                    if(InpValue>99) InpValue=99;

                    input.attr('value',InpValue)
                    input.val(InpValue).change();
                }
                else
                {
                    indexTr--;
                    route = 'up';
                    // input.val(input.attr('value'));
                    console.log('вверх');
                }

            }
            if (event.keyCode == 39) {
                if(event.shiftKey === true)
                {

                    InpValue++;
                    if(InpValue>99) InpValue=99;

                    input.attr('value',InpValue);
                    input.val(InpValue).change();
                }
                else {
                    indexTd++;
                    route = 'right';
                    // input.val(input.attr('value'));
                    // console.log('вправо');
                }
            }
            if (event.keyCode == 40) {
                if(event.shiftKey === true)
                {

                    InpValue=InpValue-10;
                    if(InpValue<0) InpValue=0;

                    input.attr('value',InpValue);
                    input.val(InpValue).change();
                }
                else
                {
                    indexTr++;
                    route = 'down';
                    // input.val(input.attr('value'));
                    // console.log('вниз');
                }

            }
            if (event.keyCode == 109) {
                if(event.shiftKey === true)
                {
                    InpValue=InpValue-10;
                }
                else
                {
                    InpValue--;
                }
                if(InpValue<0) InpValue=0;
                input.attr('value',InpValue);
                input.val(InpValue).change();
            }
            if (event.keyCode == 107) {
                if(event.shiftKey === true)
                {
                    InpValue=InpValue+10;
                }
                else
                {
                    InpValue++;
                }
                if(InpValue>99) InpValue=99;
                input.attr('value',InpValue);
                input.val(InpValue).change();
            }


            newTd = $('tr').eq(indexTr).find('td').eq(indexTd);
            input = newTd.find('input');


            if (input.length == 0) {
                switch (route) {
                    case 'left':
                        indexTd--;
                        break;
                    case 'up':
                        indexTr--;
                        break;
                    case 'right':
                        indexTd++;
                        break;
                    case 'down':
                        indexTr++;
                        break;

                }
                newTd = $('tr').eq(indexTr).find('td').eq(indexTd);
                input = newTd.find('input');
            }

            if (input.length == 1) {
                newTd.css('background-color', 'grey');
                old_td.css('background-color', '');
                // console.log(input.length, 'input.length');


                newTd.find('input').focus().select();
                //
                // console.log($('tr').eq(indexTr).find('td').eq(indexTd));
                // console.log(indexTr);
                // console.log(indexTd);
            }


        }
    });
}

function onChangeDatepicker()
{

    $('body').on('click','.datePickerOrders input',function (){
        $(this).blur();
    });
    $('body').on('change','.datePickerOrders input',function (){
        thisDate=$(this).val();
        window.location = '/admin/clientorder/?date='+thisDate;

    });
    $('body').on('click','.datePickerBlock input',function (){
       $(this).blur();
    });
    $('body').on('change','.datePickerBlock input',function (){

        tableType=$('.disorderTable').attr('tableType');
        flagReturn=$('.disorderTable').attr('flagReturn');
        thisDate=$(this).val();
        data_post = {
            'tableType': tableType,
            'date': thisDate,
            'flagReturn': flagReturn,
        }
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();
        // $.post('/site/add-client',post_data,function (response) {
        // }, 'json').done(function (data) {
        //
        // });
        $.post('/admin/disorder/', data_post).done(function (data) {

            container = $('.datePickerBlock').closest('.container');
            console.log(container);
            if (container.length == 0)
            {
                console.log(container);
                container=$('.datePickerBlock').closest('body');
            }
            container.html(data);

            printBtn=$('.datePickerBlock').closest('.container').find('.printDishorder');
            printBtn.attr('href',printBtn.attr('href')+'?date='+thisDate);



            $('.datePickerBlock #w0').datepicker($.extend({}, $.datepicker.regional['ru'], {"dateFormat":"yy-mm-dd"}));
            /* To initialize BS3 tooltips set this below */
            $(function () {
                $("[data-toggle='tooltip']").tooltip();
            });;
            /* To initialize BS3 popovers set this below */
            $(function () {
                $("[data-toggle='popover']").popover();
            });
        });
    });
}