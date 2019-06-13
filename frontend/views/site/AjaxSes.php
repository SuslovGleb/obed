<?php
//use  yii\web\Request;

//$request = Yii::$app->request;
//
//$session = Yii::$app->session;
//
//$session->open();
//$ses=$session->set('dishId', $_POST['DishId']);
//$ses= Yii::$app->request->post('DishId');

//$name = $request->post('DishId');  
//$name = $_POST['DishId'];  
$session = Yii::$app->session;
$session->open();
if($DishId)
{
    $session['DishId'] = $DishId;
    echo $session['DishId'];
}
if($DishId)
{
    $session['DishId'] = $DishId;
    echo $session['DishId'];
}
