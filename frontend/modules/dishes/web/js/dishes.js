$(document).ready(function () {
    addDishToBasket();
    addAdditionalDish();
    onModalHide();
    // switchActiveDish();
});

function addDishToBasket() {
    $('body').on("click", ".btnAddDish", function () {

        if ( $(this).hasClass('btnmodal') ) {

            modalDishBox           = $(this).closest('.dish_box');
            additional_dish_cost   = modalDishBox.attr('cost');
            additional_dish_weight = modalDishBox.attr('weight');
            additional_dish_id     = modalDishBox.attr('dish_id');
            additional_dish_name   = modalDishBox.attr('value');
            modal=modalDishBox.closest('.modal');
            dish_id                = modal.attr('dish-id');



            // console.log(additional_dish_cost,'additional_dish_cost');
            // console.log(additional_dish_weight,'additional_dish_weight');
            // console.log(additional_dish_id,'additional_dish_id');
            // console.log(additional_dish_name,'additional_dish_name');
            // console.log(dish_id,'dish_id');
            // console.log(modal,'modal');

            dish_box      = $('.tab-content .dish_box[dish_id="' + dish_id + '"]');
            dish_box_cost = dish_box.find('.costNum');

            // console.log(dish_box,'dish_box');
            dish_box.find('.dish_name').text(dish_box.attr('value') + ' + ' + additional_dish_name);

            dish_box.attr('additional_dish_id', additional_dish_id);
            dish_box.attr('additional_dish_name', additional_dish_name);
            dish_box.attr('additional_dish_weight', additional_dish_weight);
            dish_box.attr('additional_dish_cost', additional_dish_cost);


            dish_box_cost.text(parseInt(dish_box_cost.text()) + parseInt(additional_dish_cost));

            clone_box     = dish_box.find('.add_additional_dish');
            new_clone_box = clone_box.clone();

            clone_box.addClass('glyphicon-remove').removeClass('glyphicon-plus');
            clone_box.after(new_clone_box);
            new_clone_box.addClass('glyphicon-pencil').removeClass('glyphicon-plus');

            dish_box.find('.dish_image').after('<div class="additional_image glyphicon glyphicon-plus"></div>');

            modal.modal('hide');
            // <div class="additional_image glyphicon glyphicon-plus"></div>
        }
        else {


            dish_box             = $(this).closest('.dish_box');
            count                = dish_box.find('.dishInputNum').val();
            dish_id              = dish_box.attr('dish_id');
            dish_name            = dish_box.attr('value');
            dish_cost            = dish_box.attr('cost');
            additional_dish_id   = dish_box.attr('additional_dish_id');
            additional_dish_name = dish_box.attr('additional_dish_name');
            additional_dish_cost = dish_box.attr('additional_dish_cost');

            // console.log(additional_dish_id, 'additional_dish_id');
            if ( additional_dish_name ) {
                final_name      = dish_name + '+ <br> ' + additional_dish_name;
                final_cost_text = dish_cost + '<br>' + additional_dish_cost;
                final_cost_val  = parseInt(dish_cost) + parseInt(additional_dish_cost);
                attr            = ' dish_id="' + dish_id + '" additional_dish_id="' + additional_dish_id + '" ';
            }
            else {
                final_name      = dish_name;
                final_cost_text = dish_cost;
                final_cost_val  = dish_cost;
                attr            = ' dish_id="' + dish_id + '" ';
            }

            num = $('#order_table tr').length;

            can_add_row = 1;
            $('tr:not(:first)', $('#order_table')).each(function (column, tr) {

                if ( ($(tr).attr('additional_dish_id') == additional_dish_id && $(tr).attr('dish_id') == dish_id)
                    ||
                    ($(tr).attr('additional_dish_id') == dish_id && $(tr).attr('dish_id') == additional_dish_id) ) {
                    addDishToExist(tr);

                }
            });

            $(this).closest('.dish_box').clone()
                .css({'position' : 'absolute', 'z-index' : '11100', top: $(this).offset().top-50, left:$(this).offset().left-300})
                .appendTo("body")
                .animate({opacity: 0.05,
                    left: $(".basket_box").offset()['left']-270,
                    top: $(".basket_box").offset()['top'],
                    width: 0,
                    height: 0,
                }, 1000, function() {
                    $(this).remove();
                });


            AddRowToDetailCart(can_add_row, attr, num, final_name, final_cost_text, count, final_cost_val);


        }

    });
}

function addAdditionalDish() {
    $('body').on('click', '.add_additional_dish,.question', function () {

        dataTarget   = $(this).attr('data-target');
        modal=$(dataTarget);

        dishBox   = $(this).closest('.dish_box');
        // postType  = $(this).attr('posttype');
        dish_name = dishBox.find('.dish_name').text();
        dish_id   = dishBox.attr('dish_id');

        modal.attr('dish-id',dish_id);
        modal.find('.dish_modal_name').text(dish_name+'+');


        // Thtml     = $(this).html();
        // thisBlock = $(this);
        // thisBlock.html(Thtml);


        // if ( !$(this).hasClass('alreadyModal') ) {
        //
        //     $('.add_additional_dish').addClass('alreadyModal');
        //     $('.question').addClass('alreadyModal');
        //     dishBox   = $(this).closest('.dish_box');
        //     postType  = $(this).attr('posttype');
        //     dish_name = dishBox.find('.dish_name').text();
        //     dish_id   = dishBox.attr('dish_id');
        //     Thtml     = $(this).html();
        //     thisBlock = $(this);
        //
        //
        //
        //     if ( thisBlock.hasClass('add_additional_dish') ) {
        //         thisBlock.removeClass('glyphicon-plus');
        //         thisBlock.css('background-color', '#1d8a00');
        //     }
        //     $(this).html('<div id="floatingBarsG" style="position:absolute;">\n\t<div class="blockG" id="rotateG_01"></div>\n\t<div class="blockG" id="rotateG_02"></div>\n\t<div class="blockG" id="rotateG_03"></div>\n\t<div class="blockG" id="rotateG_04"></div>\n\t<div class="blockG" id="rotateG_05"></div>\n\t<div class="blockG" id="rotateG_06"></div>\n\t<div class="blockG" id="rotateG_07"></div>\n\t<div class="blockG" id="rotateG_08"></div>\n</div>');
        //
        //     post_data                     = [];
        //     post_data                     = {
        //         'complex_id'    : 0,
        //         'dish_name'     : dish_name,
        //         'dish_id'       : dish_id,
        //         'dish_type_id'  : postType,
        //         'additionalDish': 1,
        //     };
        //     post_data[yii.getCsrfParam()] = yii.getCsrfToken();
        //
        //     $.post('/complex/ajax/one-dish-type', post_data).done(function (data) {
        //         console.log(data);
        //         $('#myModal').remove();
        //         $('.container').append(data);
        //         $('#myModal').modal('show');
        //         if ( thisBlock.hasClass('add_additional_dish') ) {
        //             thisBlock.addClass('glyphicon-plus');
        //             thisBlock.css('background-color', '');
        //         }
        //         $('#floatingBarsG').remove();
        //         thisBlock.html(Thtml);
        //     });
        // }
    });

}

function onModalHide() {
    $('body').on('hidden.bs.modal', '#myModal', function () {
        $('.alreadyModal').removeClass('alreadyModal');
    });
}

function trBasketHTML(attr, num, final_name, final_cost_val, final_cost_text, countRow, sum) {
    var tr = '<tr  ' + attr + '>' +
        '        <td class="num">' + num + '</td>' +
        '        <td class="name">' + final_name + '</td>' +
        '        <td class="cost" cost="' + final_cost_val + '">' + final_cost_text + ' </td>' +
        '        <td class="count">' + countRow + '</td>' +
        '        <td class="sum"><div class="sumNum">' + sum + '</div></td>' +
        '        </tr>';

    return tr;
}

function dishInputNumHTML(num, count) {
    var div = '<div class="input-group dishBuyCount table">\n' +
        '    <span class="input-group-btn">\n' +
        '    <button type="button" class="btn dishBtnMinus btn-number" data-type="minus" data-field="quant[' + num + ']">\n' +
        '    <span class="glyphicon glyphicon-minus"></span>\n' +
        '    </button>\n' +
        '    </span>\n' +
        '    <input type="text" name="quant[' + num + ']" class="form-control input-number dishInputNum" value="' + count + '" min="1" max="99">\n' +
        '    <span class="input-group-btn">\n' +
        '    <button type="button" class="btn btn-number dishBtnPlus" data-type="plus" data-field="quant[' + num + ']">\n' +
        '    <span class="glyphicon glyphicon-plus"></span>\n' +
        '    </button>\n' +
        '    </span>\n' +
        '    </div>';
    return div;
}

function AddRowToDetailCart(can_add_row, attr, num, final_name, final_cost_text, count, final_cost_val) {
    sum      = parseInt(count) * parseInt(final_cost_val);
    countRow = dishInputNumHTML(num, count);

    if ( can_add_row ) {
        $('#order_table tr:last').after(trBasketHTML(attr, num, final_name, final_cost_val, final_cost_text, countRow, sum));

        $('.removeDishFromCart').remove();
        $('#order_table .sum').append(' <div class="glyphicon glyphicon-remove removeDishFromCart"></div>');
    }

    totalSumFunct($('#order_table'), $('.orderTotalSum'));

}

function addDishToExist(tr) {
    old_count   = $(tr).find('.dishInputNum').val();
    count       = parseInt(old_count) + parseInt(count);
    sum         = parseInt(count) * parseInt(final_cost_val);
    can_add_row = false;
    // $(tr).find('.count').text(count);
    $(tr).find('.dishInputNum').val(count);
    $(tr).find('.dishInputNum').attr('value', count);
    $(tr).find('.sumNum').text(sum);

}
