window.arChecked = [];
$(document).ready(function () {
    markAsPrinted();
    onChangeDatepicker();
    deleteOrder();
    addClientDish();
    findComplexByDishes();
    ComplexChose();

    operationDshPlus();
    operationDishMnus();
    operationDshRemove();
    operationDshDisassembl();
});

function ComplexChose() {
    $('body').on('click', '.ComplexChoseBox', function () {
        if ($(this).hasClass('choses')) {
            totalAndResetDishes();
        }
        else {
            if ($(this).hasClass('fits')
                && $(this).hasClass('canChose')) {
                assembleComplex($(this));
            }
            else if (
                !$(this).hasClass('fits')
                && !$(this).hasClass('canNotChose')
                && !$(this).hasClass('choses')
                && !$(this).hasClass('notFits')
            ) {

                $('.ComplexChoseBox').removeClass('choses');
                $(this).addClass('choses');
                $('.dishBox').removeClass('ComplexGrey');
                $('.dishBox .dishName').removeClass('ComplexGrey');
                var complex_id = parseInt($(this).attr('complex_id'));
                $.each($('.dishBox'), function () {
                    var arComplex_ids = $.map($(this).attr('complex_ids').split(','), function (value) {
                        return parseInt(value, 10);
                        // or return +value; which handles float values as well
                    });

                    //arComplex_ids=$(this).attr('complex_ids').split(',');
                    console.log(arComplex_ids);
                    if ($.inArray(complex_id, arComplex_ids) == -1) {
                        $(this).addClass('ComplexGrey');
                        $(this).find('.dishName').addClass('ComplexGrey');
                        console.log($.inArray(complex_id, arComplex_ids), 'inarray');
                        console.log(complex_id, 'complex_id');
                    }
                    else {
                        $(this).addClass('ComplexChose');
                    }
                    // if($(this).attr('complex_ids'))
                });
            }
        }

    });
}

function addClientDish() {
    $('body').on('click', '.flyerDay.addClientOrder .dishBox', function () {
        console.log('click');
        var count = 0;
        var dishName = $(this).find('.dishName').text();
        var id = $(this).attr('id');
        var typeId = $.trim($(this).attr('typeId'));
        var cost = $(this).attr('cost');
        var weight = $(this).attr('weight');
        var additional_type = $(this).closest('.type').attr('additionaltype');

        var thisBlock = $(this);
        //если идет выбор комплекса
        if (
            thisBlock.hasClass('ComplexChose')
            || thisBlock.hasClass('ComplexGrey')
        ) {
            //если не идет выбор совместного блюда и блюдо активно
            if (!thisBlock.hasClass('ComplexGrey')) {
                // cost=$('.ComplexChoseBox.choses').attr('complexprice');
                var dishblock = $(this);
                addDishToComplex(dishName, id, cost, typeId, dishblock);

            }
        }
        else {
            //если не идет выбор совместного блюда и блюдо активно
            if (!thisBlock.hasClass('grey')) {
                //если нет совместных блюд
                if (!additional_type) { //проверяем в таблице на наличие
                    //добавляем

                    addDishToTable(dishName, id, cost, typeId)
                }
                //если есть совместные блюда
                else {
                    //если уже идет выбор совместного блюда
                    if ($(this).hasClass('same') || $(this).hasClass('additional')) {
                        //если выбрано само блюдо
                        if (thisBlock.hasClass('same')) {
                            //проверяем в таблице на наличие
                            //добавляем
                            addDishToTable(dishName, id, cost, typeId);
                            //снимаем все ограничения
                            thisBlock.removeClass('same');
                        }
                        //если выбрано другое
                        if (thisBlock.hasClass('additional')) {
                            var additional_dish_id = $(this).attr('additional');
                            var additional_dishDox = $('.dishBox#' + additional_dish_id);

                            var typeId1 = additional_dishDox.attr('typeId');
                            var dishName2 = additional_dishDox.find('.dishName').text();
                            var id2 = additional_dishDox.attr('id');
                            var cost2 = additional_dishDox.attr('cost');

                            var typeId2 = typeId;
                            var dishName1 = dishName;
                            var id1 = id;
                            var cost1 = cost;
                            //проверяем в таблице на наличие
                            //добавляем
                            //снимаем все ограничения
                            addSeveralDishToTable(dishName1, dishName2, id1, id2, cost1, cost2, typeId1, typeId2);
                            // addDishToTable(dishName,id,cost,weight);
                            thisBlock = $(this);
                            thisBlock.removeClass('same');
                            additional_dishDox.removeClass('same');

                        }
                    }
                    //иначе
                    else {
                        //оставляем только тип блюд и само блюдо
                        $.each($('.type'), function () {
                            //не активный
                            if ($(this).attr('type_id') != additional_type) {

                                $(this).find('.dishName').addClass('grey');
                                $(this).find('.dishBox').addClass('grey');
                                thisBlock.addClass('same');
                                thisBlock.find('.dishName').removeClass('grey');
                                thisBlock.removeClass('grey');
                            }
                            //активный
                            else {
                                $(this).find('.dishBox').addClass('additional');
                                $(this).find('.dishBox').attr('additional', id);
                                // $(this).append('<div class="dishBox additional addSeparately"><div class="dish "><div class="dishName">Добавить отдельно</div></div></div> ')
                                // $('.addSeparately').attr('additional', id);
                            }
                        });
                    }
                }
            }
        }
    });
    totalAndResetDishes();

}

function addSeveralDishToTable(dishName1, dishName2, id1, id2, cost1, cost2, typeId1, typeId2) {
    console.log(dishName1, 'dishName1');
    console.log(dishName2, 'dishName2');
    if (typeId1 > typeId2) {
        id1 = [id2, id2 = id1][0];
        dishName1 = [dishName2, dishName2 = dishName1][0];
        typeId1 = [typeId2, typeId2 = typeId1][0];
        cost1 = [cost2, cost2 = cost1][0];
    }

    var row = $('[dish_id="' + id1 + '"][additional_dish_id = "' + id2 + '"]');
    if (row.length) {
        var count = parseInt(row.find('td.tableCount').attr('count'));
        count++;

        row.find('td.tableCount').attr('count', count);
        row.find('td.tableCount').text(count);
        row.find('td.tableSum').text((parseInt(cost1) + parseInt(cost2)) * count + 'р');
        row.find('td.tableSum').attr('sum', (parseInt(cost1) + parseInt(cost2)) * count);
        row
            .animate({backgroundColor: "#00ff2d"}, 200)
            .animate({backgroundColor: row.css('background-color')}, 200, function () {
                row.css('background-color', '');
            });
    }
    else {
        count = 1;
        $('.flyerDay.addClientOrder .row3 table tbody #tableTotal').before('' +
            '<tr class="tabeDishes" typeId="' + typeId1 + '" additional_typeId="' + typeId2 + '" dish_id="' + id1 + '" additional_dish_id="' + id2 + '">' +
            '                      <td class="tableName" name1="' + dishName1 + '" name2="' + dishName2 + '">' + dishName1 + '+' + dishName2 + '</td>\n' +
            '                    <td class="tableCost" cost1="' + parseInt(cost1) + '"cost2="' + parseInt(cost2) + '"cost="' + (parseInt(cost1) + parseInt(cost2)) + '">' + cost1 + '+' + cost2 + '</td>\n' +
            '                    <td class="tableCount" count="' + count + '">' + count + '</td>\n' +
            '                    <td class="tableSum" sum="' + (parseInt(cost1) + parseInt(cost2)) * count + '">' + (parseInt(cost1) + parseInt(cost2)) * count + 'р</td>' +
            '                    <td class="operations">' +
            '                           <div class="operatons glyphicon glyphicon-plus operationDshPlus"></div>' +
            '               <div class="operatons glyphicon glyphicon-minus operationDishMnus"></div>' +
            '                           <div class="operatons glyphicon glyphicon-remove operationDshRemove"></div>' +
            '                           <div class="operatons glyphicon glyphicon-share-alt operationDshDisassembl"></div>' +
            '                     </td>' +
            '</tr>');
    }

    totalAndResetDishes();
}

function addDishToComplex(dishName, id, cost, typeId, dishblock) {

    var dish = {};
    var disTtype;
    dish = {
        "id": id,
        "dishName": dishName,
        "cost": cost,

    };
    dishblock.closest('.type').find('.dishBox').removeClass('ComplexChosen');
    dishblock.addClass('ComplexChosen');

    $('.ComplexChoseBox.choses').attr('dish-type' + parseInt(typeId), JSON.stringify(dish));


    var typeCount = 0;
    var dishChoseCount = 0;
    var complexTypes = {};
    for (var i = 1; i < 5; i++) {
        var attr = $('.ComplexChoseBox.choses').attr('dish-type' + i);
        if (typeof attr !== typeof undefined && attr !== false) {
            var dishTtype = $('.ComplexChoseBox.choses').attr('dish-type' + i);
            if (dishTtype) {
                complexTypes['type' + i] = dishTtype;
                dishChoseCount++;
            }
            typeCount++;
        }
    }
    console.log(complexTypes, 'complexTypes');
    console.log(dishChoseCount, 'dishChoseCount');

    if (dishChoseCount == typeCount) {
        var complex_id = $('.ComplexChoseBox.choses').attr('complex_id');
        var complex_name = $('.ComplexChoseBox.choses').attr('complexname');
        var complex_price = $('.ComplexChoseBox.choses').attr('complexprice');
        var tableRowTypes = '';
        var dishSum = 0;
        var tabeDishesAttr = '';
        var complex_attr = 'complex_attr="';
        var parse;
        var dishes_id = '';
        var typesIDS = 'typesIDS="';
        var dishesIDS = 'dishesIDS="';
        var dishesCosts = 'dishesCosts="';
        var dishesNames = '';
        // var complex_attr="318,262,273,1"
        // console.log(typeCount,'typeCount');

        complex_name += ' (';
        for (var i = 1; i < 5; i++) {
            var attr = $('.ComplexChoseBox.choses').attr('dish-type' + i);
            if (typeof attr !== typeof undefined && attr !== false) {
                parse = JSON.parse($('.ComplexChoseBox.choses').attr('dish-type' + i));


                dishesIDS += parse['id'] + ',';
                typesIDS += i + ',';
                dishesCosts += parse['cost'] + ',';
                console.log(parse, 'parse');
                dishesNames += parse['dishName'] + ',';
                complex_name += parse['dishName'] + ',';
                dishes_id += parse['id'] + ',';

                tabeDishesAttr += "id"
                dishSum += parseInt(parse['cost']);

                // tableRowTypes+='dish-type'+i+'=""';
                $('.ComplexChoseBox.choses').attr('dish-type' + i, '');
            }
        }
        typesIDS = typesIDS.substring(0, typesIDS.length - 1);
        dishesIDS = dishesIDS.substring(0, dishesIDS.length - 1);
        dishesCosts = dishesCosts.substring(0, dishesCosts.length - 1);
        complex_name = complex_name.substring(0, complex_name.length - 1);
        dishes_id = dishes_id.substring(0, dishes_id.length - 1);

        typesIDS += '"';
        dishesIDS += '"';
        dishesCosts += '"';

        complex_name += ' )';
        complex_attr += dishes_id + ',' + complex_id + '"';
        console.log(dishSum, 'dishSum');
        console.log(complex_attr, 'complex_attr');

        var row = $('.flyerDay.addClientOrder .row3 table tr[complex_attr="' + dishes_id + ',' + complex_id + '"]');
        if (row.length) {
            var count = parseInt(row.find('td.tableCount').attr('count'));
            count++;

            row.find('td.tableCount').attr('count', count);
            row.find('td.tableCount').text(count);
            row.find('td.tableSum').text(complex_price * count + 'р');
            row.find('td.tableSum').attr('sum', complex_price * count);
            row
                .animate({backgroundColor: "#00ff2d"}, 200)
                .animate({backgroundColor: row.css('background-color')}, 200, function () {
                    row.css('background-color', '');
                });
        }
        else {
            var count = 1;
            dishesNames = dishesNames.replace(/"/g, "&quot;");
            dishesNames = dishesNames.replace(/'/g, "&quot;");
            count = 1;
            $('.flyerDay.addClientOrder .row3 table tbody .tableTitles').after('' +
                '<tr class="tabeDishes" ' + complex_attr + typesIDS + dishesIDS + dishesCosts + 'dishesNames="' + dishesNames + '">' +
                '                    <td calss="tableName">' + complex_name + '</td>\n' +
                '                    <td class="tableCost" cost="' + complex_price + '">' + complex_price + '</td>\n' +
                '                    <td class="tableCount" count="' + count + '">' + count + '</td>\n' +
                '                    <td class="tableSum" sum="' + complex_price * count + '">' + complex_price * count + 'р</td>' +
                '                    <td class="operations">' +
                '                           <div class="operatons glyphicon glyphicon-plus operationDshPlus"></div>' +
                '                           <div class="operatons glyphicon glyphicon-minus operationDishMnus"></div>' +
                '                           <div class="operatons glyphicon glyphicon-remove operationDshRemove"></div>' +
                '                           <div class="operatons glyphicon glyphicon-share-alt operationDshDisassembl"></div>' +
                '                     </td>' +
                '</tr>');
        }


        totalAndResetDishes();
    }

}


function addDishToTable(dishName, id, cost, typeId) {

    var row = $('.flyerDay.addClientOrder .row3 table tr[dish_id="' + id + '"]:not([additional_dish_id])');
    if (row.length) {
        var count = parseInt(row.find('td.tableCount').attr('count'));
        count++;

        row.find('td.tableCount').attr('count', count);
        row.find('td.tableCount').text(count);
        row.find('td.tableSum').text(cost * count + 'р');
        row.find('td.tableSum').attr('sum', cost * count);
        row
            .animate({backgroundColor: "#00ff2d"}, 200)
            .animate({backgroundColor: row.css('background-color')}, 200, function () {
                row.css('background-color', '');
            });


        //
    }
    else {
        count = 1;
        $('.flyerDay.addClientOrder .row3 table tbody #tableTotal').before('' +
            '<tr class="tabeDishes" typeId="' + typeId + '" dish_id="' + id + '">' +
            '                    <td class="tableName">' + dishName + '</td>\n' +
            '                    <td class="tableCost" cost="' + cost + '">' + cost + '</td>\n' +
            '                    <td class="tableCount" count="' + count + '">' + count + '</td>\n' +
            '                    <td class="tableSum" sum="' + cost * count + '">' + cost * count + 'р</td>' +
            '                    <td class="operations">' +
            '                           <div class="operatons glyphicon glyphicon-plus operationDshPlus"></div>' +
            '                           <div class="operatons glyphicon glyphicon-minus operationDishMnus"></div>' +
            '                           <div class="operatons glyphicon glyphicon-remove operationDshRemove"></div>' +
            '                     </td>' +
            '</tr>');
    }


    totalAndResetDishes();


}

function findComplexByDishes() {
    $('body').on('click', '.tabeDishes', function () {
        var dishesId = [];
        var countSelectForComplex = 0;

        var thisType = $(this).attr('typeid');
        var additional_typeid = $(this).attr('additional_typeid');
        var thisDish = $(this).attr('dish_id');
        var thisAdditionalDish = $(this).attr('dish_id');
        var thisRow = $(this);


        if (
            !$(this)[0].hasAttribute("complex_attr")
            && $(this).attr("typeid") != '5'
        ) {
            if ($(this).hasClass('selectForComplex')) {
                $('.tabeDishes[typeid=' + thisType + ']').removeClass('excessDish').removeClass('selectForComplex');
                $('.tabeDishes[typeid=' + additional_typeid + ']').removeClass('excessDish').removeClass('selectForComplex');
                $('.tabeDishes[additional_typeid=' + thisType + ']').removeClass('excessDish').removeClass('selectForComplex');
                $('.tabeDishes[additional_typeid=' + additional_typeid + ']').removeClass('excessDish').removeClass('selectForComplex');
            }
            else {
                $('.tabeDishes[typeid=' + thisType + ']').removeClass('excessDish').removeClass('selectForComplex').addClass('excessDish');
                $('.tabeDishes[typeid=' + additional_typeid + ']').removeClass('excessDish').removeClass('selectForComplex').addClass('excessDish');
                $('.tabeDishes[additional_typeid=' + thisType + ']').removeClass('excessDish').removeClass('selectForComplex').addClass('excessDish');
                $('.tabeDishes[additional_typeid=' + additional_typeid + ']').removeClass('excessDish').removeClass('selectForComplex').addClass('excessDish');
                $(this).removeClass('excessDish');
                $(this).addClass('selectForComplex');
            }
        }
        //находим количество выбраных блюд
        $.each($('.tabeDishes'), function (index, tr) {
            if ($(this).hasClass('selectForComplex')) {
                if ($(this)[0].hasAttribute('additional_dish_id')) {
                    dishesId.push(parseInt($(this).attr('additional_dish_id')));
                    countSelectForComplex++;
                }
                dishesId.push(parseInt($(this).attr('dish_id')));
                // dishesId[$(this).attr('dish_id')]=parseInt($(this).find('.tableCount').attr('count'));
                countSelectForComplex++;
            }
        });

        //найти комплексы с блюдами из таблицы
        if (countSelectForComplex) {
            $('.ComplexChoseBox').removeClass('fits');
            //находим одинаковые значения комплексов в блюдах
            var fitBlocks = [];
            var Arr1;
            var Arr2;
            var count = 0;
            $.each($('.selectForComplex'), function () {
                var Complex_ids = $('.dishBox[id="' + $(this).attr('dish_id') + '"]').attr('complex_ids');
                var arComplex_ids = Complex_ids.split(',');
                if ($(this)[0].hasAttribute('additional_dish_id')) {
                    var Complex_ids2 = $('.dishBox[id="' + $(this).attr('additional_dish_id') + '"]').attr('complex_ids');
                    var arComplex_ids2 = Complex_ids2.split(',');

                    if (count == 0) {
                        Arr1 = arComplex_ids;
                        Arr2 = arComplex_ids2;
                        Arr1 = Arr1.filter(value => -1 !== Arr2.indexOf(value));
                        count++;
                    }
                    else {
                        Arr2 = arComplex_ids;
                        Arr1 = Arr1.filter(value => -1 !== Arr2.indexOf(value));
                        Arr2 = arComplex_ids2;
                        Arr1 = Arr1.filter(value => -1 !== Arr2.indexOf(value));
                    }

                }
                else {
                    if (count == 0) {
                        Arr1 = arComplex_ids;
                        count++;
                    }
                    else {
                        Arr2 = arComplex_ids;
                        Arr1 = Arr1.filter(value => -1 !== Arr2.indexOf(value));
                    }
                }

                //----находим одинаковые значения комплексов в блюдах - результат Arr1


            });

            $('.ComplexChoseBox').addClass('notFits');
            $.each(Arr1, function (i, val) {
                $('.ComplexChoseBox[complex_id="' + val + '"]').removeClass('notFits').addClass('fits');
            });
            $.each($('.fits'), function () {
                var typeCount = 0;
                for (var i = 1; i < 5; i++) {
                    var attr = $(this).attr('dish-type' + i);

                    if (typeof attr !== typeof undefined && attr !== false) {
                        typeCount++;
                    }

                }
                if (typeCount == countSelectForComplex) {
                    $(this).addClass('canChose');
                    $(this).removeClass('canNotChose');
                }
                else {
                    $(this).addClass('canNotChose');
                    $(this).removeClass('canChose');
                }

            });
            if (
                $('.fits.canChose').length == 1
                && $('.fits.canNotChose').length == 0) {

                assembleComplex();
            }


        }
        else {
            totalAndResetDishes();
        }
    });
    // $.post('/admin/clientorder/ajax/find-complex-by-dishes', data_post).done(function (data) {

}

function assembleComplex(thisComplex) {

    if (!thisComplex) {
        var complex_name = $('.fits.canChose').attr('complexname');
        var complex_price = $('.fits.canChose').attr('complexprice');
        var complex_id = $('.fits.canChose').attr('complex_id');
    }
    else {
        var complex_name = thisComplex.attr('complexname');
        var complex_price = thisComplex.attr('complexprice');
        var complex_id = thisComplex.attr('complex_id');
    }
    //complex_attr="318,262,273,1" typesids="1,3,4" dishesids="318,262,273" dishescosts="25,40,35"
    var complex_attr = '';

    var dishes_id = '';
    var dishesNames = "";
    var typesIDS = 'typesIDS="';
    var dishesIDS = 'dishesIDS="';
    var dishesCosts = 'dishesCosts="';
    var dishCount = 0;

    var dishesPrice = '';
    var dishesTypeIds = '';
    var dishesids = '';

    $.each($('.selectForComplex'), function () {
        console.log($(this).attr('dish_id'), '$(this).attr(\'dish_id\')');
        dishesNames += $(this).find('.tableName').text() + ',';
        console.log(dishesNames, 'dishesNames');
        dishesIDS += $(this).attr('dish_id') + ',';
        complex_attr += $(this).attr('dish_id') + ',';
        dishesCosts += $(this).find('.tableCost').attr('cost') + ',';
        typesIDS += $(this).attr('typeid') + ',';
        dishCount = $(this).find('.tableCount').attr('count');
        dishCount--;
        if (!dishCount) {
            $(this).remove();
        }
        else {
            var sum = parseInt($(this).find('.tableCost').attr('cost')) * dishCount;
            $(this).find('.tableCount').attr('count', dishCount);
            $(this).find('.tableCount').text(dishCount);
            $(this).find('.tableSum').attr('sum', sum);
            $(this).find('.tableSum').text(sum);
        }

    });

    dishesNames = dishesNames.substring(0, dishesNames.length - 1);
    dishesIDS = dishesIDS.substring(0, dishesIDS.length - 1);
    typesIDS = typesIDS.substring(0, typesIDS.length - 1);
    dishesCosts = dishesCosts.substring(0, dishesCosts.length - 1);
    complex_attr += complex_id;
    dishesIDS += '"';
    typesIDS += '"';
    dishesCosts += '"';
    complex_name += '(' + dishesNames + ')"';
    dishesNames = dishesNames.replace(/"/g, "&quot;");
    dishesNames = dishesNames.replace(/'/g, "&quot;");
    var findSameRow = $('.tabeDishes[complex_attr="' + complex_attr + '"]');
    if (findSameRow.length) {
        var sameCount = findSameRow.find('.tableCount').attr('count');
        var sameTableSum = findSameRow.find('.tableSum');
        findSameRow.find('.tableCount').attr('count', parseInt(sameCount) + 1);
        findSameRow.find('.tableCount').text(parseInt(sameCount) + 1);
        sameTableSum.attr('sum', (parseInt(sameCount) + 1) * parseInt(complex_price))
        sameTableSum.text((parseInt(sameCount) + 1) * parseInt(complex_price) + 'р')
    }
    else {
        count = 1;
        $('.flyerDay.addClientOrder .row3 table tbody .tableTitles').after('' +
            '<tr class="tabeDishes" complex_attr="' + complex_attr + '"' + typesIDS + dishesIDS + dishesCosts + 'dishesNames="' + dishesNames + '">' +
            '                    <td calss="tableName">' + complex_name + '</td>\n' +
            '                    <td class="tableCost" cost="' + complex_price + '">' + complex_price + '</td>\n' +
            '                    <td class="tableCount" count="' + count + '">' + count + '</td>\n' +
            '                    <td class="tableSum" sum="' + complex_price * count + '">' + complex_price * count + 'р</td>' +
            '                    <td class="operations">' +
            '                           <div class="operatons glyphicon glyphicon-plus operationDshPlus"></div>' +
            '                           <div class="operatons glyphicon glyphicon-minus operationDishMnus"></div>' +
            '                           <div class="operatons glyphicon glyphicon-remove operationDshRemove"></div>' +
            '                           <div class="operatons glyphicon glyphicon-share-alt operationDshDisassembl"></div>' +
            '                     </td>' +
            '</tr>');

    }

    totalAndResetDishes();
}

function totalAndResetDishes() {


    var total = 0;
    $.each($('.flyerDay.addClientOrder .row3 table td.tableSum'), function () {
        total += parseInt($(this).attr('sum'));
        console.log($(this), '$(this)');

    });
    $('#tableSum').text(total + 'р');

    $('.addSeparately').closest('.dishBox').remove();
    $('.tabeDishes.excessDish').removeClass('excessDish');
    $('.tabeDishes.selectForComplex').removeClass('selectForComplex');

    $('.dishName').removeClass('grey');
    $('.dishBox').removeClass('grey');
    $('.dishBox').removeClass('additional');
    $('.dishBox').removeAttr('additional');
    $('.ComplexChosen').removeClass('ComplexChosen');
    $('.ComplexChose').removeClass('ComplexChose');
    $('.ComplexGrey').removeClass('ComplexGrey');
    $('.ComplexChoseBox.choses').removeClass('choses');
    $('.ComplexChoseBox.canChose').removeClass('canChose');
    $('.ComplexChoseBox').removeClass('fits');
    $('.ComplexChoseBox').removeClass('notFits');
    $('.ComplexChoseBox').removeClass('canNotChose');
}



function deleteOrder() {

    $('body').on('click', '.APorderBtn.delete', function () {
        var id = [];
        id.push($(this).attr('order-id'));
        var data_post = {
            'arId': id,
        };
        $(this).addClass('loader');
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();
        $.post('/admin/clientorder/ajax/delete-order-by-ids', data_post).done(function (data) {
            console.log(data, 'data');
            $.pjax.reload({container: '#pjax_1'});
            $(this).removeClass('loader');
        });

    });
}

function markAsPrinted() {
    $('body').on('click', '.markAsPrinted', function () {
        var idS = $('#ordersIds').attr('ids');
        var data_post = {
            'idS': idS,
        };
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();

        $.post('/admin/clientorder/ajax/mark-as-printed', data_post).done(function (data) {

            console.log(data, 'data');
        });
    });

}

function onChangeDatepicker() {

    $('body').on('click', '.datePickerOrders input', function () {
        $(this).blur();
    });
    $('body').on('change', '.datePickerOrders input', function () {
        var thisDate = $(this).val();
        window.location = '/admin/clientorder/?date=' + thisDate;

    });
}


function operationDshPlus() {
    $('body').on('click', '.operationDshPlus', function (event) {
        event.stopPropagation();
        var count = $(this).closest('.tabeDishes').find('.tableCount');
        var sum = $(this).closest('.tabeDishes').find('.tableSum');
        var cost = $(this).closest('.tabeDishes').find('.tableCost');
        count.attr('count', parseInt(count.attr('count')) + 1);
        count.text(parseInt(count.attr('count')));
        sum.text(parseInt(count.attr('count')) * cost.attr('cost') + 'р');
        sum.attr('sum', parseInt(count.attr('count')) * cost.attr('cost'));
        totalAndResetDishes();

    });

}

function operationDishMnus() {
    $('body').on('click', '.operationDishMnus', function (event) {
        var count = $(this).closest('.tabeDishes').find('.tableCount');
        var sum = $(this).closest('.tabeDishes').find('.tableSum');
        var cost = $(this).closest('.tabeDishes').find('.tableCost');
        if (parseInt(count.attr('count')) > 1) {
            count.attr('count', parseInt(count.attr('count')) - 1);
            count.text(parseInt(count.attr('count')));
            sum.text(parseInt(count.attr('count')) * cost.attr('cost') + 'р');
            sum.attr('sum', parseInt(count.attr('count')) * cost.attr('cost'));
            totalAndResetDishes();
        }
        event.stopPropagation();
    });
}

function operationDshRemove() {
    $('body').on('click', '.operationDshRemove', function (event) {
        $(this).closest('.tabeDishes').remove();
        totalAndResetDishes();
        event.stopPropagation();
    });
}

function operationDshDisassembl() {
    $('body').on('click', '.operationDshDisassembl', function (event) {

        var row = $(this).closest('.tabeDishes');
        if (!row[0].hasAttribute("complex_attr")) {
            console.log($(this));
            console.log('!$(this)[0].hasAttribute("complex_attr")');
            var name1 = row.find('.tableName').attr('name1');
            var name2 = row.find('.tableName').attr('name2');
            var cost1 = row.find('.tableCost').attr('cost1');
            var cost2 = row.find('.tableCost').attr('cost2');
            var count = row.find('.tableCount').attr('count');
            var type1 = row.attr('typeid');
            var type2 = row.attr('additional_typeid');
            var dishId1 = row.attr('dish_id');
            var dishId2 = row.attr('additional_dish_id');


            $('.flyerDay.addClientOrder .row3 table tbody #tableTotal').before('' +
                '<tr class="tabeDishes" typeId="' + type1 + '" dish_id="' + dishId1 + '">' +
                '                    <td class="tableName">' + name1 + '</td>\n' +
                '                    <td class="tableCost" cost="' + cost1 + '">' + cost1 + '</td>\n' +
                '                    <td class="tableCount" count="' + count + '">' + count + '</td>\n' +
                '                    <td class="tableSum" sum="' + cost1 * count + '">' + cost1 * count + 'р</td>' +
                '                    <td class="operations">' +
                '                           <div class="operatons glyphicon glyphicon-plus operationDshPlus"></div>' +
                '                           <div class="operatons glyphicon glyphicon-minus operationDishMnus"></div>' +
                '                           <div class="operatons glyphicon glyphicon-remove operationDshRemove"></div>' +
                '                     </td>' +
                '</tr>');
            $('.flyerDay.addClientOrder .row3 table tbody #tableTotal').before('' +
                '<tr class="tabeDishes" typeId="' + type2 + '" dish_id="' + dishId2 + '">' +
                '                    <td class="tableName">' + name2 + '</td>\n' +
                '                    <td class="tableCost" cost="' + cost2 + '">' + cost2 + '</td>\n' +
                '                    <td class="tableCount" count="' + count + '">' + count + '</td>\n' +
                '                    <td class="tableSum" sum="' + cost2 * count + '">' + cost2 * count + 'р</td>' +
                '                    <td class="operations">' +
                '                           <div class="operatons glyphicon glyphicon-plus operationDshPlus"></div>' +
                '                           <div class="operatons glyphicon glyphicon-minus operationDishMnus"></div>' +
                '                           <div class="operatons glyphicon glyphicon-remove operationDshRemove"></div>' +
                '                     </td>' +
                '</tr>');


        }
        else {
            var dshes_ids = row.attr('dishesids');
            var types_ids = row.attr('typesids');
            var dishesnames = row.attr('dishesnames');
            var dishescosts = row.attr('dishescosts');
            var dish_count = parseInt(row.find('.tableCount').attr('count'));
            dshes_ids = dshes_ids.split(',');
            types_ids = types_ids.split(',');
            dishesnames = dishesnames.split(',');
            dishescosts = dishescosts.split(',');


            $.each(dshes_ids, function (i, id) {
                if ($('.tabeDishes[dish_id=' + id + ']').length) {
                    var old_dish = parseInt($('.tabeDishes[dish_id=' + id + ']').find('.tableCount').attr('count'));
                    $('.tabeDishes[dish_id=' + id + ']').find('.tableCount').attr('count', old_dish + dish_count);
                    $('.tabeDishes[dish_id=' + id + ']').find('.tableCost').attr('cost', dishescosts[i]);
                    $('.tabeDishes[dish_id=' + id + ']').find('.tableSum').attr('cost', (old_dish + dish_count) * dishescosts[i]);
                    $('.tabeDishes[dish_id=' + id + ']').find('.tableCount').text(old_dish + dish_count);
                    $('.tabeDishes[dish_id=' + id + ']').find('.tableCost').text(dishescosts[i]);
                    $('.tabeDishes[dish_id=' + id + ']').find('.tableSum').text((old_dish + dish_count) * dishescosts[i]);
                }
                else {
                    $('.flyerDay.addClientOrder .row3 table tbody #tableTotal').before('' +
                        '<tr class="tabeDishes" typeId="' + types_ids[i] + '" dish_id="' + dshes_ids[i] + '">' +
                        '                    <td class="tableName">' + dishesnames[i] + '</td>\n' +
                        '                    <td class="tableCost" cost="' + dishescosts[i] + '">' + dishescosts[i] + '</td>\n' +
                        '                    <td class="tableCount" count="' + dish_count + '">' + dish_count + '</td>\n' +
                        '                    <td class="tableSum" sum="' + dishescosts[i] * dish_count + '">' + dishescosts[i] * dish_count + 'р</td>' +
                        '                    <td class="operations">' +
                        '                           <div class="operatons glyphicon glyphicon-plus operationDshPlus"></div>' +
                        '                           <div class="operatons glyphicon glyphicon-minus operationDishMnus"></div>' +
                        '                           <div class="operatons glyphicon glyphicon-remove operationDshRemove"></div>' +
                        '                     </td>' +
                        '</tr>');
                }

            });

        }
        row.remove();

        event.stopPropagation();
    });
}