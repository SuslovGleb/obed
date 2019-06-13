<?php
if($orderEmpty)
{
    $headerText='Вы пока что еще ничего не добавили к заказу';
    $headerClass='detailTitleNotOrder';
}
else
{
    $headerClass='detailTitle';
    $headerText='Заказ';
}
if($modal)
{
    $header='<button type="button" class="closeBasket glyphicon glyphicon-remove close"
                    data-dismiss="modal" aria-label="Close">
            </button>';
    $header.=' <div class="'.$headerClass.'"><h1 class="modal-title">'.$headerText.'</h1></div>';
}
else
{
    $header='<h1>'.$headerText.'</h1>';
}

echo $header;