<?php

    $headerClass='detailTitle';
    $headerText='Заказ';

    $header='<button type="button" class="closeBasket glyphicon glyphicon-remove close"
                    data-dismiss="modal" aria-label="Close">
            </button>';
    $header.=' <div class="'.$headerClass.'"><h1 class="modal-title">'.$headerText.'</h1></div>';

echo $header;