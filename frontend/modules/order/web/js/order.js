$(document).ready(function () {
    AddFirmFromGis();
    inputSearchAjax();
    addClient();
    confirmOrder();
    btnClearCart();
    orderDetails();
    editOrder()
    // clearCart();
    removeDishFromOrder();
    masks();
    addNewOptionFromInput();
    addClientAutoOrManual();
    // validateOrder();
    ImportantNeedCheckThisButtonsImportant();
    totalSumFunct($('#order_table'), $('.orderTotalSum'));
    findOrdersPrint();
    AllClients();
    aceptOrder();
    deleteOrder();
    chooseClientFromTable();
    $('body').on('click', '.print.orderPrintContain .toggleOrders', function () {
        $(this).toggleClass('glyphicon-chevron-right').toggleClass('glyphicon-chevron-left');
        $('.print.orderPrintContain').toggleClass('driveRight');
    });
    switchActiveDish();

});
$(document).on('ready pjax:success', function () {
    PjaxBodyFunctionsOFF();
    editOrder()
    AddFirmFromGis();
    inputSearchAjax();
    addClient();
    confirmOrder();
    btnClearCart();
    aceptOrder();
    deleteOrder();
    orderDetails();
    // clearCart();
    removeDishFromOrder();
    masks();
    addNewOptionFromInput();
    addClientAutoOrManual();
    // validateOrder();
    ImportantNeedCheckThisButtonsImportant();
    totalSumFunct($('#order_table'), $('.orderTotalSum'));
    findOrdersPrint();
    AllClients();
    chooseClientFromTable();
    switchActiveDish();
    $('body').off('click', '.print.orderPrintContain .toggleOrders');
    $('body').on('click', '.print.orderPrintContain .toggleOrders', function () {
        $(this).toggleClass('glyphicon-chevron-right').toggleClass('glyphicon-chevron-left');
        $('.print.orderPrintContain').toggleClass('driveRight');
    });

});

/**
 * Получить список событий, которые висят на элементе
 * @param object element jQuery элемент
 * @returns object|false
 */
function eventsList(element) {
    // В разных версиях jQuery список событий получается по-разному
    var events = element.data('events');
    if ( events !== undefined ) return events;

    events = $.data(element, 'events');
    if ( events !== undefined ) return events;

    events = $._data(element, 'events');
    if ( events !== undefined ) return events;

    events = $._data(element[0], 'events');
    if ( events !== undefined ) return events;

    return false;
}

/**
 * Проверить есть ли событие eventname на элементе element
 * @param object element jQuery-элемент
 * @param string eventname название события
 * @returns bool
 */
function checkEvent(element, eventname) {
    var events,
        ret = false;

    events = eventsList(element);
    if ( events ) {
        $.each(events, function (evName, e) {
            if ( evName == eventname ) {
                ret = true;
            }
        });
    }

    return ret;
}

function totalSumFunct(table, output) {
    totalSum = 0;
    $('tr:not(:first)', table).each(function (column, tr) {
        quant = $(this).find('.dishInputNum').val();

        sum = $(tr).find('.sumNum').text();
        totalSum += parseInt(sum) || 0;
    });

    output.text('Итого: ' + totalSum + ' руб');

    orderQuantity();
    $('.basket_count').text(totalSum);
    orderToSession();
}

function orderToSession() {
    //order=$('#basket_detail').html();
    order                         = $('#detailTable').html();
    post_data                     = [];
    post_data                     = {'order': order};
    post_data[yii.getCsrfParam()] = yii.getCsrfToken();

    $.post('/order/ajax/session-order', post_data).done(function (data) {

    });
}

function orderQuantity() {

    num = $('#order_table tr').length - 1;

    if ( num > 0 ) {
        $('.basket_count').text(num);
        modalHeaderOrder(true);
    }
    else {
        $('.basket_count').text(0);
        modalHeaderOrder(false);
    }


}

function aceptOrder() {

    $('body').on('click', '.APorderBtn.accept', function () {
        id                            = $(this).attr('order-id');
        post_data                     = [];
        post_data                     = {'id': id};
        post_data[yii.getCsrfParam()] = yii.getCsrfToken();
        $.post('/order/ajax/acept-order-by-id', post_data).done(function (data) {
            findOrdersPrint();
        });

    });
}

function deleteOrder() {

    $('body').on('click', '.APorderBtn.delete', function () {
        id = [];
        id.push($(this).attr('order-id'));
        post_data                     = [];
        post_data                     = {'arId': id};
        post_data[yii.getCsrfParam()] = yii.getCsrfToken();
        $.post('/order/ajax/delete-order-by-ids', post_data).done(function (data) {
            console.log(data, 'data');
            findOrdersPrint();
        });

    });
}

function orderDetails() {
    $('body').on('click', '.orderDetails', function () {
        $('#order_infoModal').remove();
        id                            = $(this).attr('order-id');
        post_data                     = [];
        post_data                     = {'id': id};
        post_data[yii.getCsrfParam()] = yii.getCsrfToken();

        $.post('/order/ajax/find-order-by-id', post_data).done(function (data) {
            console.log(data, 'orderDetails');
            $('body').append(data);
            $('#order_infoModal').modal();
            masks();
        });
    });
}

function AddFirmFromGis() {

    $('body').on('click', '.addFirmFromGis', function () {

        text    = $(this).siblings('textarea').val();
        newText = text.split('---------------');

        block        = $(this).closest('.addFromGisBlock');
        firmBlock    = block.siblings('div.clientFirm');
        addressBlock = block.siblings('div.firmAddress');
        select       = $(this).closest('.clientFirmBox').find('.firmAddress .formSelectBox').find('select');
        input        = $(this).closest('.clientFirmBox').find('.firmAddress .formInputBox').find('input');
        data_post    = [];


        data_post                     = {
            'newText': newText,
        };
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();
        selectHtml                    = '';
        $.post('/order/ajax/add-firm-from-gis', data_post, function (response) {
        }, 'json').success(function (response) {

            if ( !response['duble'] ) {
                alert('Данная фирма уже есть в базе');
            }
            else {

                $.each(response.siteAddress, function (index, value) {

                    selectHtml += '<option>' + value + '</option>';


                });
                select.html(selectHtml);
                firmBlock.find('input[name=clientFirm]').val(response.name);
                firmBlock.find('input[name=clientFirm]').attr('search_id', response.firm_id);

                input.val($('select').find('option:last').text());
                // $.post('/admin/site/find-firm-by-id', {id: response['firm_id']}).success(function (firm_data) {
                //     $('.firm_cont').after(firm_data);
                //     $('.selectpicker').selectpicker('refresh');
                // });
            }
            $('.addFromGisBlock').addClass('inactive');

            $('.clientFirm').removeClass('inactive');
            $('.firmAddress').removeClass('inactive');
            $('.firmAddress .formSelectBox').addClass('inactive').removeClass('inactive');
            $('.firmAddress .formInputBox').removeClass('inactive').addClass('inactive');
            $('.chooseFirmFromGis').removeClass('inactive');

        });
    });

    $('body').on('click', '.chooseFirmFromGis', function () {
        $('.addFromGisBlock').removeClass('inactive');
        $(this).addClass('inactive');
        $('.clientFirm').addClass('inactive');
    });
}

function inputSearchAjax() {
    $('body').on('keyup focus', 'input.search', function (event) {

        url          = '';
        response_val = '';
        minLen       = 2;
        $(this).addClass('writing');
        if ( $(this).attr('name') == 'clientAddr' ) {
            url          = '/order/ajax/find-streets';
            response_val = 'street_name';
            attr         = 'clientAddr';
        }
        if ( $(this).attr('name') == 'firmSphere' ) {
            url          = '/order/ajax/find-spheres';
            response_val = 'sphere';
            attr         = 'firmSphere';
        }
        if ( $(this).attr('name') == 'clientFirm' ) {

            url          = '/order/ajax/find-firms';
            response_val = 'name';
            attr         = 'clientFirm';
        }
        if ( $(this).attr('name') == 'clientChoose' ) {
            url          = '/order/ajax/find-client';
            response_val = 'client';
            attr         = 'clientChoose';
        }
        // if ($(this).attr('name') == 'drivers') {
        //     url = '/site/find-drivers';
        //     response_val = 'worker_name';
        //     minLen=0;
        // }

        input                         = $(this);
        searchWord                    = $(this).val();
        post_data                     = [];
        post_data                     = {'searchWord': searchWord};
        post_data[yii.getCsrfParam()] = yii.getCsrfToken();

        if ( event.keyCode == 13 ) {
            chooseInputBlock($(this));

        }

        aComplite($(this), url, post_data, response_val, minLen, attr)

    });
    $('body').on('blur', 'input.search', function (event) {
        val = $(this).val();

        if ( $.trim(val).length > 0 ) {
            $(this).val($.trim(val));
            chooseInputBlock($(this));
        }
    });

    ///удаление  из input id с выбранных данных
    $('body').on('input', '.search', function (e) {
        $(this).removeAttr('search_id');
    });
}

function chooseClientFromTable() {

    $('body').on('click', '.findClientBox tr', function () {
        tr = $(this);

        firm_name    = tr.find('.firm').text();
        firm_id      = tr.attr('firm-id');
        address      = tr.find('.address').text();
        address_id   = tr.attr('address-id');
        telephone    = tr.find('.telephone').text();
        telephone_id = tr.attr('telephone-id');
        client_id    = tr.attr('client-id');


        actionChooseClient(firm_name, firm_id, address, address_id, telephone, telephone_id, client_id)
    });


}

function editOrder() {

    $('body').on('click', '.editOrder', function () {
        order_data  = $(this).siblings('#ordersIds');
        order_table = $(this).siblings('.order').find('.order_table');
        cartDriver  = $(this).siblings('.cartDriver').html();
        cartTime    = $(this).siblings('.cartTime').find('input').val();

        order_id     = order_data.attr('order-id');
        firm_name    = order_data.attr('firm_name');
        firm_id      = order_data.attr('firm-id');
        address      = order_data.attr('address');
        address_id   = order_data.attr('address-id');
        telephone    = order_data.attr('telephone');
        telephone_id = order_data.attr('telephone-id');
        client_id    = order_data.attr('client-id');

        $('#order_table').html(order_table.html());
        totalSumFunct($('#order_table'), $('.orderTotalSum'));
        actionChooseClient(firm_name, firm_id, address, address_id, telephone, telephone_id, client_id, cartDriver, cartTime);


        $('#order_infoModal').modal('hide');

        $('#basket_detail').modal('show');
        $('#basket_detail').on('shown.bs.modal', function (e) {
            $('body').addClass('modal-open');
        });
        $('#basket_detail').find('.btnOrder').attr('order-id', order_id);

    });


}

function AllClients() {

    $('body').on('click', '.findClients', function () {
        $('.orderInfoBox').hide();
        $('.addClientBox').hide();
        $('.findClientBox').show();

        data_post                     = [];
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();

        $.post('/order/ajax/find-all-clients', data_post).done(function (data) {
            $('.findClientBox').html(data);
            masks();
        });
    });
}

function addClient() {

    $('body').on('click', '.NotAFirm', function (e) {
        ClientFirm          = $(this).parent('.inputClientFirm');
        inputClientFirm     = ClientFirm.find('input.clientFirm');
        // popoverClientFirm= ClientFirm.find('.popover');
        formGroupClientFirm = ClientFirm.find('.form-group');
        glyphicon           = formGroupClientFirm.find('.form-control-feedback');

        inputClientFirm.popover('hide');
        formGroupClientFirm.removeClass('has-error');
        // glyphicon.removeClass('glyphicon-ok').removeClass('glyphicon-remove');

        if ( $(this).prop('checked') ) {
            inputClientFirm.attr('disabled', 'disabled');
        }
        else {
            inputClientFirm.removeAttr('disabled');
        }

    });
    $('body').on('click', '.btnAddClientToggle', function (e) {
        $(this).find('i').toggleClass('glyphicon glyphicon-chevron-up').toggleClass('glyphicon glyphicon-chevron-down');
        $('.addClientInputs').toggle();
    });

    $('body').on('click', '.clienAddIco', function (e) {
        $('.findClientBox').hide();
        $('.orderInfoBox').hide();
        $('.addClientBox').show();
    });

//при добавлении клиента
    $('body').on('click', '.btnAddClient', function (e) {
        console.log($(this).parent('form').serializeArray(), '$(this).parent(\'firm\').serialize();');
        //переменная formValid
        var formValid = true;
        //перебрать все элементы управления input
        $('.addClientInputs input[type!="checkbox"]').each(function () {
            // $('.addClientBox input').each(function () {

            //найти предков, которые имеют класс .form-group, для установления success/error
            var formGroup = $(this).parents('.form-group');

            //найти glyphicon, который предназначен для показа иконки успеха или ошибки
            var glyphicon = formGroup.find('.form-control-feedback');
            //для валидации данных используем HTML5 функцию checkValidity
            // if ( $(this).hasClass('toSelect') ) {
            //     this_form_group = $(this).closest('.form-group');
            //     inpSelVal       = this_form_group.attr('inpSelVal');
            //     divVal          = this_form_group.attr('divVal');
            //
            //
            //     formSelectGroup = this_form_group.siblings('div.form-group[inpSelVal=' + inpSelVal + ']');
            //
            //
            //     var formSelectGroup = $(this).parents('.form-group');
            //
            //     var glyphiconSelect = formSelectGroup.find('.form-control-feedback');
            // }

            if ( this.checkValidity() ) {
                //добавить к formGroup класс .has-success, удалить has-error
                formGroup.addClass('has-success').removeClass('has-error');
                //добавить к glyphicon класс glyphicon-ok, удалить glyphicon-remove
                glyphicon.addClass('glyphicon-ok').removeClass('glyphicon-remove');
                if ( $(this).hasClass('toSelect') ) {
                    formSelectGroup.addClass('has-success').removeClass('has-error');
                    glyphiconSelect.addClass('glyphicon-ok').removeClass('glyphicon-remove');
                }
            } else {

                //добавить к formGroup класс .has-error, удалить .has-success
                formGroup.addClass('has-error').removeClass('has-success');
                //добавить к glyphicon класс glyphicon-remove, удалить glyphicon-ok
                glyphicon.addClass('glyphicon-remove').removeClass('glyphicon-ok');

                if ( $(this).hasClass('toSelect') ) {
                    formSelectGroup.removeClass('has-success').addClass('has-error');
                    glyphiconSelect.removeClass('glyphicon-ok').addClass('glyphicon-remove');
                }
                $(this).popover('show');

                //отметить форму как невалидную

                formValid = false;


            }

            // console.log(this);
            // console.log(this.checkValidity());
        });

        //если форма валидна, то
        if ( formValid ) {

            // address    = [];
            // telephones = [];
            // name       = '';
            // telephone  = '';
            // firm       = '';
            // sphere     = '';

            // $('.addClient .firmAddress select option').each(function (i, val) {
            //     if ( $(this).hasAttr('address_id') ) {
            //         if ( $.trim($(this).text()) != $.trim(val.text) ) {
            //             address.push($(this).attr('address_id'));
            //         }
            //     }
            //     else {
            //         address.push($.trim(val.text));
            //     }
            //
            // });
            // inputAdres = $('.addClient .firmAddress input').val();
            // if ( $.inArray(inputAdres, address) === -1 ) {
            //     address.push(inputAdres);
            // }
            //
            // $('.addClient .clientInf  select option').each(function (i, val) {
            //     telephones.push(val.text);
            // });
            // inputTel = $('.addClient .clientInf input').val();
            // if ( $.inArray(inputTel, telephones) === -1 ) {
            //     telephones.push(inputTel);
            // }
            //
            // post_data = {};
            // formArray = $('.form-horizontal').serializeArray();
            // $.each(formArray, function (index, value) {
            //     post_data[value.name] = value.value;
            // });
            //
            // if ( $('input.clientFirm').hasAttr('search_id') ) {
            //     post_data['clientFirm'] = $('input.clientFirm').attr('search_id');
            // }
            // if ( $('input.firmSphere').hasAttr('search_id') ) {
            //     post_data['firmSphere'] = $('input.firmSphere').attr('search_id');
            // }


            post_data                     = [];
            post_data[yii.getCsrfParam()] = yii.getCsrfToken();
            // post_data['address']          = address;
            // post_data['telephones']       = telephones;
            // post_data['adrComment']       = adrComment;
            // post_data['firm']       = firm;
            post_data = {'clientData': $(this).parent('form').serializeArray()};

            // console.log($(this),'$(this)');
            // console.log($(this).parent('form'),'$(this).parent(\'firm\')');
            $.post('/order/ajax/add-client', post_data, function (req, res) {
            }, 'json').done(function (data) {
                console.log(data);
                firm_name    = data.firm_name;
                firm_id      = data.firm_id;
                address      = data.address;
                address_id   = data.address_id;
                telephone    = data.telephone;
                telephone_id = data.telephone_id;
                client_id    = data.client_id;

                actionChooseClient(firm_name, firm_id, address, address_id, telephone, telephone_id, client_id);
            });


        }


    });
}

//оформление заказа
function confirmOrder() {

    $('body').on('click', '.btnOrder', function () {

        valid = validateOrder();
        if ( valid && !$(this).hasClass('loader') ) {
            $(this).addClass('loader');
            post_data                     = {'arId': $('#basket_detail').find('.btnOrder').attr('order-id')};
            post_data[yii.getCsrfParam()] = yii.getCsrfToken();
            $thisBtn                      = $(this);
            $.post('/order/ajax/delete-order-by-ids', post_data).done(function (data) {
                $('#basket_detail').find('.btnOrder').attr('order-id', '');
                order = {};
                $('tr:not(:first)', $('#order_table')).each(function (index, tr) {

                    order[index]            = {};
                    order[index]['dish_id'] = '';
                    order[index]['count']   = '';

                    if ( $(tr).hasAttr("dish_id") ) {
                        order[index]['dish_id'] = $(tr).attr('dish_id');
                    }
                    if ( $(tr).hasAttr("additional_dish_id") ) {
                        order[index]['additional_dish_id'] = [];
                        order[index]['additional_dish_id'] = $(tr).attr('additional_dish_id');
                    }
                    if ( $(tr).hasAttr("complex_attr") ) {
                        arrComplexAttr = $(tr).attr('complex_attr').split(',')  // массив ["a", "b", "c"]

                        order[index]['complexId'] = '';
                        dishArr                   = [];

                        $.each(arrComplexAttr, function (arIndex, value) {

                            if ( arIndex == arrComplexAttr.length - 1 ) {
                                console.log(value, 'complexId value');
                                order[index]['complexId'] = value;
                            }
                            else {
                                dishArr.push(value);
                            }
                        });
                        order[index]['dish_id'] = dishArr;
                    }
                    order[index]['count'] = $(tr).find('.dishInputNum').val();
                });
                data_post = [];

                deliveryTime = $('.orderInfoBox .cartTime input').val();

                if ( $('.orderInfoBox').hasClass('client') ) {
                    clientName     = $('.orderInfoBox .cartClientName input').val();
                    firm_id        = '';
                    firm_name      = $('.orderInfoBox .cartFirm input').val();
                    telephone_id   = '';
                    telephone_text = $('.orderInfoBox .cartTel  input').val();
                    address_id     = '';
                    address_name   = $('.orderInfoBox .cartAddress input').val();
                    comment        = $('.orderInfoBox .cartComent input').val();
                    deliveryTime   = $('.orderInfoBox .cartTime input').val();
                    driver_id      = '';
                    clientsOrder   = '1';
                }
                else {
                    clientName     = '';
                    firm_id        = $('.orderInfoBox').attr('firm_id');
                    firm_name      = '';
                    telephone_id   = $('.orderInfoBox .cartTel  .clienTelephone    select option:selected').attr('data-id');
                    telephone_text = $('.orderInfoBox .cartTel  .clienTelephone    select option:selected').text();
                    address_id     = $('.orderInfoBox .cartAddress .selectAddressBox  select option:selected').attr('data-id');
                    address_name   = $('.orderInfoBox .cartAddress .selectAddressBox  select option:selected').text();
                    comment        = $('.orderInfoBox .cartComent input').val();
                    deliveryTime   = $('.cartTime input').val();
                    driver_id      = $('.orderInfoBox .cartDriver  select option:selected').attr('data-id');
                    clientsOrder   = '';
                }
                data_post = {
                    'driver_id'     : driver_id,
                    'clientsOrder'  : clientsOrder,
                    'clientName'    : clientName,
                    'order'         : order,
                    'deliveryTime'  : deliveryTime,
                    'addrId'        : address_id,
                    'address_name'  : address_name,
                    'firmId'        : firm_id,
                    'firmName'      : firm_name,
                    'telId'         : telephone_id,
                    'telephone_text': telephone_text,
                    'comment'       : comment,
                };


                data_post[yii.getCsrfParam()] = yii.getCsrfToken();

                $.post('/order/ajax/find-same-order', data_post).done(function (data) {
                    same     = '';
                    same     = data;
                    newOrder = 'false';
                    same == '0' ? newOrder = 'true' : newOrder = 'false';
                    if ( newOrder == 'false' ) {
                        $thisBtn.before('<div class="choseAddingOrder">' +
                            '<div class="btn btn-primary addToOrder">Добавить к последнему заказу</div>' +
                            '<div class="btn btn-info NewOrder">Создать новый заказ</div>' +
                            '</div>');
                        $thisBtn.hide();
                        AddOrder(data_post)
                    }
                    else {


                        orderConfirm(data_post, newOrder);
                    }
                });
            });
        }
    });

}

function AddOrder(data_post) {

    $('body').on('click', '.choseAddingOrder .addToOrder', function () {
        newOrder = 'false';
        $(this).addClass('loader');
        $('.choseAddingOrder .NewOrder').remove();
        orderConfirm(data_post, newOrder);
    });

    $('body').on('click', '.choseAddingOrder .NewOrder', function () {
        $(this).addClass('loader');
        $('.choseAddingOrder .addToOrder').remove();
        newOrder = 'true';
        orderConfirm(data_post, newOrder);
    });

}

function orderConfirm(data_post, newOrder) {
    data_post['newOrder'] = newOrder;
    $.post('/order/ajax/order-confirm', data_post).done(function (data) {
        $('.loader').removeClass('loader');
        $('i .fa-spinner').remove();
        $('.hasClicked').removeClass('loader');

        $('.NewOrder').remove();
        $('.addToOrder').remove();
        $('.btnOrder').show();

        findOrdersPrint();
        clearCart();
        $('#basket_detail .modal-header .modal-title').text('Ваш заказ успешно оформлен.');

        setTimeout(function () {

            $('#basket_detail').modal('hide');

            // $('#basket_detail .modal-header .modal-title').text('Вы пока что еще ничего не добавили к заказу');

        }, 3000);


        console.log(data);
    });
}

function findOrdersPrint() {

    data_post                     = [];
    data_post[yii.getCsrfParam()] = yii.getCsrfToken();

    $.post('/order/ajax/is-admin', data_post).done(function (data) {
        if ( data == '1' ) {
            post_data                     = {};
            post_data[yii.getCsrfParam()] = yii.getCsrfToken();

            $.post('/order/ajax/find-print-orders', post_data).done(function (data) {
                $('.orderPrintContain').remove();
                $('body').append(data);

            });
        }
    });


}

function modalHeaderOrder(orderIsEmpty) {
    console.log(orderIsEmpty, 'orderIsEmpty');
    if ( orderIsEmpty ) {
        $('#basket_detail .modal-header .modal-title').text('Заказ');
        // $('#basket_detail').addClass('active');
        // $('#basket_detail').removeClass('inactive');
        $('.basket_count').css('display', 'block');
        $('.orderTotalSum').css('display', 'block');
        $('#detailTable').show();
        if ( $('#basket_detail .detailInfo').length == 0 ) {
            $('#basket_detail .modal-footer').removeClass('inactive');
            $('#basket_detail .orderInfoBox.addingBox').removeClass('inactive');

        }
        else {
            $('.detailControl').removeClass('inactive');
        }
    }
    else {
        $('#basket_detail .modal-header .modal-title').text('Вы пока что еще ничего не добавили к заказу');
        // $('#basket_detail').removeClass('active');
        // $('#basket_detail').addClass('inactive');
        $('#basket_detail .orderInfoBox.addingBox').addClass('inactive');
        $('.basket_count').css('display', 'none');
        $('.orderTotalSum').css('display', 'none');
        $('#detailTable').hide();
        if ( $('#basket_detail .detailInfo').length == 0 ) {
            $('#basket_detail .modal-footer').addClass('inactive');

        }
        else {
            $('.detailControl').addClass('inactive');
        }
    }
}

//очистить корзину
function clearCart() {

    $('#order_table').find('tr:not(:first)').remove();

    modalHeaderOrder(false);

    post_data                     = [];
    post_data[yii.getCsrfParam()] = yii.getCsrfToken();

    $.post('/order/ajax/session-order-clear', post_data).done(function (data) {

    });

    totalSumFunct($('#order_table'), $('.orderTotalSum'));
}

function btnClearCart() {

    $('body').on('click', '.btnClearCart', function () {
        clearCart();
    });
}


//удаление блюда из таблицы заказов
function removeDishFromOrder() {

    $('body').on('click', '.removeDishFromCart', function () {
        $(this).closest('tr').remove();
        table = $(this).closest('order_table');
        $('tr', table).each(function (column, tr) {


            $(tr).find('.num').text(column);
            inputGroup = $(tr).find('.dishBuyCount ');
            quant      = 'quant[' + inputGroup.find('input').attr('table-type') + column + ']';
            inputGroup.find('input').attr('name', quant);
            inputGroup.find('.dishBtnMinus').attr('data-field', quant);
            inputGroup.find('.dishBtnPlus').attr('data-field', quant);
        });

        totalSumFunct(table, $('.orderTotalSum'));

    });
}

function masks() {
    $.mask.definitions['h'] = "[0-1]";
    $.mask.definitions['z'] = "[0-9]";
    $.mask.definitions['x'] = "[0-5]";
    $(".phone").mask("+7(999) 999-99-99");
    // $.mask.definitions['h']='[0-2]';
    // $.mask.definitions['minute']='[0-59]';
    $(".time").mask("hz : xz");
    console.log($(".time"), '$(".time")');
}

///Добавление option в select при нажатии на кнопку .inpToSelectBtn
function addNewOptionFromInput() {

    $('body').on('click', '.inpToSelectBtn', function () {
        this_form_group = $(this).closest('.form-group');
        inpSelVal       = this_form_group.attr('inpSelVal');
        divVal          = this_form_group.attr('divVal');


        that_form_group = this_form_group.siblings('div.form-group[inpSelVal=' + inpSelVal + ']');

        // console.log(this_form_group, 'this_form_group');
        // console.log(that_form_group, 'that_form_group');
        // console.log(divVal, 'divVal');
        // console.log(inpSelVal, 'inpSelVal');

        ///если input
        if ( divVal == 'input' ) {
            this_input = this_form_group.find('input[name=' + inpSelVal + ']');
            val        = this_input.val();

            if ( !this_input[0].checkValidity() ) {
                this_input.popover('show');

                this_form_group.removeClass('has-success').addClass('has-error');

                $(this).css('background-color', 'red');
            }
            else {

                this_form_group.addClass('has-success').removeClass('has-error');
                that_form_group.addClass('has-success').removeClass('has-error');
                that_form_group.find('.form-control-feedback').addClass('glyphicon-ok').removeClass('glyphicon-remove');
                // console.log( that_form_group.find('select'),' that_form_group.find(\'select\')');
                // console.log(that_form_group.find('select option:last'),'that_form_group.find(\'select option:last\')');
                // console.log(val.length,'val.length');
                double = false;
                $.each(that_form_group.find('select option'), function (arIndex, value) {
                    if ( $.trim(val) == $.trim(value.text) ) {
                        double = true;
                    }


                });
                if ( !double ) {
                    that_form_group.find('select').append('<option data-id="0">' + val + '</option>');
                    that_form_group.find('select option:last').attr('selected', 'selected');
                }
                that_form_group.toggleClass('inactive');
                this_form_group.toggleClass('inactive');
                $(this).css('background-color', '');


            }
        }
        ///если select
        else {
            this_form_group.toggleClass('inactive');
            that_form_group.toggleClass('inactive');
            that_form_group.find('input[name=' + inpSelVal + ']').val('');
        }


    });
}

function addClientAutoOrManual() {

    $('body').on('click', '.btnChangeAuto', function (event) {
        $('.addClientManual').hide();
        $('.addClientAutomate').show();
    });

    $('body').on('click', '.btnChangeManual', function (event) {
        $('.addClientManual').show();
        $('.addClientAutomate').hide();
    });
}

function validateOrder() {
    //переменная formValid
    var formValid = true;
    //перебрать все элементы управления input
    $('.orderInfoBox input').each(function (index, value) {
        //найти предков, которые имеют класс .form-group, для установления success/error
        var formGroup = $(this).parents('.form-group');
        //найти glyphicon, который предназначен для показа иконки успеха или ошибки
        var glyphicon = formGroup.find('.form-control-feedback');
        //для валидации данных используем HTML5 функцию checkValidity
        if ( this.checkValidity() ) {
            //добавить к formGroup класс .has-success, удалить has-error
            formGroup.addClass('has-success').removeClass('has-error');
            //добавить к glyphicon класс glyphicon-ok, удалить glyphicon-remove
            glyphicon.addClass('glyphicon-ok').removeClass('glyphicon-remove');
        } else {
            console.log($(value));
            $(value).popover('show');
            //добавить к formGroup класс .has-error, удалить .has-success
            formGroup.addClass('has-error').removeClass('has-success');
            //добавить к glyphicon класс glyphicon-remove, удалить glyphicon-ok
            glyphicon.addClass('glyphicon-remove').removeClass('glyphicon-ok');
            //отметить форму как невалидную
            formValid = false;
        }
    });
    //если форма валидна, то
    if ( formValid ) {
        return true;
    }
    else {
        return false;
    }

}


function chooseInputBlock(input) {
    if ( $(input).hasClass('writing') ) {
        $('.ui-autocomplete.ui-front.ui-menu.ui-widget.ui-widget-content').hide();
        firmAdres = $(input).closest('div.clientFirm').siblings('.firmAddress');
        select    = firmAdres.find('select.addressSelect');
        select.html('');
        if ( firmAdres.hasClass('inactive') ) firmAdres.removeClass('inactive');
        inputAdr = $(input).closest('div.clientFirm').siblings('.firmAddress').find('input.address')
        firmAdres.find('.selectAddressBox').addClass('inactive');
        firmAdres.find('.inputAddressBox').removeClass('inactive');
        inputAdr.attr('firm_id', '');
        inputAdr.val('');
        $(input).removeClass('writing');
        $('.firmSphere').removeClass('inactive');
    }
}

function actionChooseClient(firm_name, firm_id, address, address_id, telephone, telephone_id, client_id, cartDriver="", cartTime='') {
    $('.findClientBox').hide();
    $('.orderInfoBox').show();
    $('.addClientBox').hide();

    // input.attr( 'search_id',client_id);

    infobox = $('.orderInfoBox');


    data_post = [];
    data_post = {
        'client_firm_name': firm_name,
        'client_address'  : address,
        'client_telephone': telephone,
        'client_id'       : client_id,
        'firm_id'         : firm_id,
        'address_id'      : address_id,
        'telephone_id'    : telephone_id,
    };

    data_post[yii.getCsrfParam()] = yii.getCsrfToken();

    $.post('/order/ajax/session-client', data_post, function (req, res) {
    }, 'json').done(function (data) {
        $('.orderInfoBox').removeClass('inactive');
        $('.orderInfoBox').attr('firm_id', data['firm_id']);

        infobox.find('.cartFirm').find('.orderInfoValue').text(firm_name);

        selectHtml = '';
        selected   = '';
        $.each(data['addresses'], function (arIndex, value) {
            selected = '';
            if ( arIndex == 0 ) {
                inputVal = value['address']['address'];
                $('.orderInfoBox').attr('address_id', value['id'])
            }
            selected = value['selected_by_default'] == '1' ? 'selected' : '';
            if ( selected == 'selected' ) {
                inputVal = value['address']['address'];
                $('.orderInfoBox').attr('address_id', value['id'])
            }

            selectHtml += '<option data-id="' + value['id'] + '" ' + selected + '>' + value['address']['address'] + '</option>';
        });
        telephone_id = $('.orderInfoBox').attr('telephone_id');
        address_id   = $('.orderInfoBox').attr('address_id');
        firm_id      = $('.orderInfoBox').attr('firm_id');
        if ( cartTime ) {
            $('.orderInfoBox').find('.cartTime input').val(cartTime);
        }

        $('.orderInfoBox').find('.cartAddress .orderInfoValue .formSelectBox select').html(selectHtml);
        $('.orderInfoBox').find('.cartAddress .orderInfoValue .formInputBox input').val(inputVal);


        selectHtml = '';
        selected   = '';
        $.each(data['telephones'], function (arIndex, value) {
            selected = '';
            if ( arIndex == 0 ) {
                inputVal = value['client_telephone'];
                $('.orderInfoBox').attr('telephone_id', value['id'])
            }
            selected = value['selected_by_default'] == '1' ? 'selected' : '';
            if ( selected == 'selected' ) {
                inputVal = value['client_telephone'];
                $('.orderInfoBox').attr('telephone_id', value['id'])
            }

            selectHtml += '<option data-id="' + value['id'] + '" ' + selected + '>' + value['client_telephone'] + '</option>';
        });


        $('.orderInfoBox').find('.cartTel  .orderInfoValue .formSelectBox select').html(selectHtml);
        $('.orderInfoBox').find('.cartTel  .orderInfoValue .formInputBox input').val(inputVal);

        selectHtml = '';
        selected   = '';
        if ( cartDriver ) {
            $('.orderInfoBox').find('.cartDriver   .orderInfoValue select').html(cartDriver);
        }
        else {
            $.each(data['drivers'], function (arIndex, value) {
                selectHtml += '<option data-id="' + value['id'] + '" ' + selected + '>' + value['worker_name'] + '</option>';
            });
            $('.orderInfoBox').find('.cartDriver   .orderInfoValue select').html(selectHtml);
        }

        // infobox.find('.cartAddress').find('.orderInfoValue').text(address);
        // infobox.find('.cartTel').find('.orderInfoValue').text(telephone);


        infobox.attr('firm_id', firm_id);
        //
        // masks();

    });

}

function aComplite(input, url, post_data, response_val, minLen, attr) {
    input.autocomplete({
        source   : function (request, response) {
            $.post(url, post_data, function (request, response) {
            }, 'json').done(function (data) {
                console.log(data, 'data');
                response(data);
            });
        },
        minLength: minLen,
        select   : function (event, ui) {
            item = ui.item;
            $(input).removeClass('writing');
            if ( attr == 'clientChoose' ) {
                input.val('');
                actionChooseClient(item.firm_name, item.firm_id, item.address, item.address_id, item.telephone, item.telephone_id, item.client_id);
            }

            // else if (response_val == '/site/find-drivers') {
            //     input.val(item[response_val]);
            //     input.attr('search_id', item.id);
            //     data_post = [];
            //     data_post = {
            //         'driver_name': item[response_val],
            //         'driver_id': item.id,
            //
            //     };
            //
            //
            //     data_post[yii.getCsrfParam()] = yii.getCsrfToken();
            //     $.post('/order/ajax/session-driver', data_post).done(function (data) {
            //         $('.orderInfoBox').removeClass('inactive');
            //         console.log(data, 'data_post');
            //     });
            // }
            else if ( attr == 'clientFirm' ) {
                input.val(item[response_val]);
                input.attr('search_id', item.id);
                $('.firmSphere').addClass('inactive');
                firm_id = item.id;
                // if($(input).closest('.addClient').hasClass('addClientAutomate'))
                // {
                data_post = [];
                data_post = {
                    'firmId': firm_id,

                };


                data_post[yii.getCsrfParam()] = yii.getCsrfToken();
                $.post('/order/ajax/find-firm-address', data_post, function (req, res) {
                }, 'json').done(function (data) {

                    firmAdres = $(input).closest('div.clientFirm').siblings('.firmAddress');
                    inputAdr  = firmAdres.find('input.address');
                    select    = firmAdres.find('select.addressSelect');
                    select.html('');
                    if ( firmAdres.hasClass('inactive') ) firmAdres.removeClass('inactive');
                    if ( data.length > 0 ) {
                        selectHtml = '';


                        $.each(data, function (arIndex, value) {
                            selectHtml += '<option address_id="' + value.id + '">' + value.address + '</option>';
                        });

                        select.html(selectHtml);

                        select.closest('.form-group').addClass('has-success').removeClass('has-error');
                        select.siblings('.form-control-feedback').addClass('glyphicon-ok').removeClass('glyphicon-remove');

                        inputAdr.attr('firm_id', firm_id);
                        inputAdr.val(firmAdres.find('select.addressSelect option:first').text());

                        firmAdres.find('.selectAddressBox').removeClass('inactive');
                        firmAdres.find('.inputAddressBox').addClass('inactive');

                        console.log('11111111111111111');
                    }
                    else {
                        select.closest('.form-group').removeClass('has-success').removeClass('has-error');
                        select.siblings('.form-control-feedback').removeClass('glyphicon-ok').removeClass('glyphicon-remove');

                        inputAdr.attr('firm_id', '');
                        inputAdr.val('');

                        inputAdr = $(input).closest('div.clientFirm').siblings('.firmAddress').find('input.address')
                        firmAdres.find('.selectAddressBox').addClass('inactive');
                        firmAdres.find('.inputAddressBox').removeClass('inactive');
                        console.log('22222222222');

                    }


                });
                // }
                // else
                // {
                //     input.val(item[response_val]);
                //     input.attr('search_id', item.id);
                // }

            }
            else {
                console.log(item, 'item');
                input.val(item[response_val]);
                input.attr('search_id', item.id);
            }

            return false;

        },
        focus    : function (event, ui) {
            item = ui.item;
            if ( response_val == 'client' ) {
                // input.val( item.address +  item.firm_name );
                // input.attr( 'search_id',item.client_id);
            }
            else {
                // input.val(item[response_val]);
                // input.attr('search_id', item.id);
            }

            return false;
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {

        if ( response_val == 'client' ) {

            return $("<li>").attr('client_id', item.client_id)
                .append("<div class='client_data_box'>"
                    + "<div class='li_search_addrr'>" + item.address + ",</div>"
                    + "<div class='li_search_tel'>" + item.telephone + "</div>"
                    + "<div class='li_search_name'>" + item.firm_name + "</div>"

                    + "</div>")
                .appendTo(ul);
        }
        else {
            return $("<li>").attr(response_val + 'id', item.id)
                .append("<div>" + item[response_val] + "</div>")
                .appendTo(ul);
        }

    }
}


function ImportantNeedCheckThisButtonsImportant() {


    $('body').on('click', '.btnSearchClient', function (e) {

        $('.chooseClientBox').show();
        $('.addClientBox').hide();
    });

    $('body').on('change', 'select.addressSelect', function () {
        selVal = $(this).val();
        $(this).closest('.firmAddress').find('input.clientAddr').val(selVal);
    });

    $('body').on('focus', '.clientFirm,.clientOffice', function () {
        $('.clientOffice').parents('.form-group').removeClass('has-success').removeClass('has-error');
        $('.clientFirm').parents('.form-group').removeClass('has-success').removeClass('has-error');
        $('.clientOffice').parents('.form-group').find('.form-control-feedback').removeClass('glyphicon-ok').removeClass('glyphicon-exclamation-sign');
        $('.clientFirm').parents('.form-group').find('.form-control-feedback').removeClass('glyphicon-ok').removeClass('glyphicon-exclamation-sign');
    });

    $('body').on('focus', '.formInputBox input', function () {
        $(this).popover('destroy');
        $(this).siblings('.inpToSelectBtn').css('background-color', '');
        $(this).closest('.form-group').removeClass('has-success').removeClass('has-error');
    });

}
function switchActiveDish() {
    $('body').on('click', '.dish_box .material-switch label', function () {

        switcher=$(this).siblings('input');
        checked     = !switcher.prop("checked");
        dishbox=switcher.closest('.dish_box')
        dish_id=dishbox.attr('dish_id');

        console.log(data_post, 'data_post');
        data_post                     = {
            'dish_id'  : dish_id,
            'checked'  : checked,
        };
        data_post[yii.getCsrfParam()] = yii.getCsrfToken();

        $.post('dishes/ajax/switch-active-dish', data_post).done(function (data) {
            dishbox.toggleClass('inactiveDish');
            console.log(data, 'data');
        });

    });

}

function PjaxBodyFunctionsOFF() {
    $('body').off('click', '.addFirmFromGis');
    $('body').off('click', '.chooseFirmFromGis');
    $('body').off('input', '.search');
    $('body').off('click', '.findClientBox tr');
    $('body').off('click', '.findClients');
    $('body').off('click', '.clienAddIco');
    $('body').off('click', '.btnAddClient');
    $('body').off('click', '.btnOrder');
    $('body').off('click', '.choseAddingOrder .addToOrder');
    $('body').off('click', '.choseAddingOrder .NewOrder');
    $('body').off('click', '.btnClearCart');
    $('body').off('click', '.removeDishFromCart');
    $('body').off('click', '.inpToSelectBtn');
    $('body').off('click', '.btnChangeAuto');
    $('body').off('click', '.btnChangeManual');
    $('body').off('click', '.btnSearchClient');
    $('body').off('change', 'select.addressSelect');
    $('body').off('focus', '.clientFirm,.clientOffice');
    $('body').off('focus', '.formInputBox input');
    $('body').off('keyup focus', 'input.search');
    $('body').off('blur', 'input.search');
    $('body').off('click', '.btnAddClientToggle');
    $('body').off('click', '.orderDetails');
    $('body').off('click', '.APorderBtn.delete');
    $('body').off('click', '.APorderBtn.accept');
    $('body').off('click', '.editOrder');
    $('body').off('click', '.dish_box .material-switch label');
}