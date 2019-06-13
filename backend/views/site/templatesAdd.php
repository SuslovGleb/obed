<?php

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\controllers\Svg;
use \yii\helpers\Url;
use vova07\imperavi\Widget;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

$this->title = 'My Yii Application';

$svg=new Svg;

$images['up']=$svg->Up('rgba(165, 165, 165, 0.4)');
$images['groming']=$svg->Groming('rgba(165, 165, 165, 0.4)');
$images['handling']=$svg->Handling('rgba(165, 165, 165, 0.4)');
$images['photo']=$svg->Photo('rgba(165, 165, 165, 0.4)');
$images['gantel']=$svg->Gantel('rgba(165, 165, 165, 0.4)');
$images['dress']=$svg->Dress('rgba(165, 165, 165, 0.4)');
$images['gpsPoint']=$svg->GpsPoint('rgba(165, 165, 165, 0.4)');

?>


<div id="content" style="
    box-shadow: 0px 0px 0px 1px;
">
    <section id="block0">

    <?php
    NavBar::begin([
        'brandLabel' => 'Чемпион',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            'style' => '    position: initial;    margin: 0;',
        ],
    ]);

    $menuItems = [
        ['label' => 'Громинг'],
        ['label' => 'Хендлинг'],
        ['label' => 'Фотоуслуги'],
        ['label' => 'Дог-фитнес'],
        ['label' => 'Дрессировка'],
        ['label' => 'Контакты'],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);

    NavBar::end();
    ?>

    <?php
//    echo  Html::tag('a', $images['groming'], [
//        'href'=>'#block1',
//        'class'=>'menu_image',
//        'data-title'=>'Громинг',
//        'data-toggle'=>'tooltip',
//        'data-placement'=>"left",
//        'style'=>'text-decoration: underline; cursor:pointer;'
//    ]);

    ?>
    <div class="menu_left">
        <a href="#block0" class="menu_image"><?=$images['up']?></a>
        <a href="#block1" class="menu_image"><?=$images['groming']?></a>
        <a href="#block2" class="menu_image"><?=$images['handling']?></a>
        <a href="#block3" class="menu_image"><?=$images['photo']?></a>
        <a href="#block4" class="menu_image"><?=$images['gantel']?></a>
        <a href="#block5" class="menu_image"><?=$images['dress']?></a>
        <a href="#block6" class="menu_image"><?=$images['gpsPoint']?></a>
    </div>
    <div style="
    font-size: 25pt;
">
        <div style="
    background-image: url(/images/landings/landing1/main_image_landing.jpg);
    height: 800px;
    background-size: 100%;
    background-repeat: no-repeat;
    color: white;
    padding: 25px;
">
            <div class="header_info">
                <h1>Заоголовок</h1>
                мы предлагаем большой спектр по подготовке животных и бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла бла
            </div>
            <div class="telephone_header">750-464</div>



        </div>
    </div>
    </section>
    <section id="block1">

        <div class="text1" style="
    float: left;
    width: 70%;
    padding: 70px 0 70px 70px;
    font-size: 14pt;
    z-index: 111;
">
            <H1>Гроуминг</H1>
            Вы приобрели породистого щенка, уже успели полюбить его и конечно Вам хочется чтобы .Ваш питомец был самым красивым ,самым лучшим и воспитанным. С каждым днем вопросов по его уходу и правильному выращиванию становится все больше.
        </div>
        <div class="image1">
            <img src="/images/landings/dog1.png" alt="" style="
                                    /* -webkit-filter: drop-shadow(11px 9px 3px rgba(0, 39, 7, 0.5)); */
                                    /* filter: drop-shadow(11px 9px 6px rgba(0, 39, 7, 0.5)); */
                                    /* margin-left: -53px; */
                                    /* position: absolute; */
                                    /* top: 0; */
                                    height: 480px;
                                    float: right;
                                    ">
        </div>
        <div class="back_image" style="
    height: 700px;
    background-color: #7bb5e6;
    "></div>
        <div class="back_image1" style="
    background-color: #ff9cfc;
    /* width: 100%; */
    /* height: 400px; */
    /* border-radius: 0 0 1000px 0; */
    /* height: 0; */
    /* width: 120px; */
    border-top: 115px solid #7bb5e6;
    /* border-left: 60px solid transparent; */
    border-right: 1080px solid transparent;
    /* transform: rotate(180deg); */
    /* z-index: 0; */
    /* right: 0; */
    /* width: 0; */
    "></div>
    </section>


    <section id="block2">
        <div class="text2" style="
    float: right;
    width: 51%;
    padding: 70px   70px  70px 0;
    font-size: 14pt;
    z-index: 111;
">
            <H1>Хэндлинг</H1>
            Конечно, в интернете есть много общей информации об этом, но каждый щенок индивидуален и проблемы у каждого свои. При этом Вы даже можете об них не подозревать. Для этого и существует  наш Хендлинг- центр «Чемпион». Наши специалисты помогут Вам  вырастить собаку так, чтобы Вы ею гордились.
        </div>
        <div class="image2" style="
    float: right;
">
            <img src="/images/landings/landing1/dog2.png" alt="" style="
                                    /* -webkit-filter: drop-shadow(11px 9px 3px rgba(0, 39, 7, 0.5)); */
                                    /* filter: drop-shadow(11px 9px 6px rgba(0, 39, 7, 0.5)); */
                                    /* margin-left: -53px; */
                                    /* position: absolute; */
                                    /* top: 0; */
                                    height: 370px;
                                    float: right;
                                    ">
        </div>
        <div class="back_image" style="
    height: 700px;
    background-color: #ff9cfc;
    "></div>
        <div class="back_image2" style="
    background-color: #8fda86;
    /* width: 100%; */
    /* height: 400px; */
    /* border-radius: 0 0 1000px 0; */
    /* height: 0; */
    /* width: 120px; */
    border-top: 115px solid #ff9cfc;
    border-right: 0px solid transparent;
    border-left: 1080px solid transparent;
    /* transform: rotate(180deg); */
    /* z-index: 0; */
    /* right: 0; */
    /* width: 0; */
    "></div>
    </section>


    <section id="block3">
        <div class="text1" style="
    float: left;
    width: 70%;
    padding: 70px 0 70px 70px;
    font-size: 14pt;
    z-index: 111;
">
            <H1>Фото услуги</H1>
            В нашем Хендлинг –центре:
            <ul>
                <li>  Индивидуальные и групповые занятия в зале с опытным инструктором</li>
                <li>Фитнес тренинг для всех собак,</li>
                <li>Курс послушания, коррекция поведения для собак, работаем в группах и индивидуально</li>
                <li>Грумминг-студия, все виды ухода за Вашим питомцем</li>
                <li>Фотоуслуги, возможен выезд к заказчику</li>
                <li>Инструктор по вязкам</li>
            </ul>







        </div>
        <div class="image3">
            <img src="/images/landings/landing1/dog3.png" alt="" style="
                                    /* -webkit-filter: drop-shadow(11px 9px 3px rgba(0, 39, 7, 0.5)); */
                                    /* filter: drop-shadow(11px 9px 6px rgba(0, 39, 7, 0.5)); */
                                    /* margin-left: -53px; */
                                    /* position: absolute; */
                                    /* top: 0; */
                                    height: 528px;
                                    float: right;
                                    ">
        </div>
        <div class="back_image" style="
    height: 700px;
    background-color: #8fda86;
    "></div>
        <div class="back_image3" style="
    /* background-color: #86bbda; */
    /* width: 100%; */
    /* height: 400px; */
    /* border-radius: 0 0 1000px 0; */
    /* height: 0; */
    /* width: 120px; */
    border-top: 115px solid #8fda86;
    /* border-left: 60px solid transparent; */
    border-right: 670px solid transparent;
    /* transform: rotate(180deg); */
    /* z-index: 0; */
    /* right: 0; */
    /* width: 0; */
    "></div>
    </section>
    <section id="block4">
        <div class="text1" style="
    float: left;
    width: 70%;
    padding: 70px 0 70px 70px;
    font-size: 14pt;
    z-index: 111;
">

            <H1>Дог-Фитнес</H1>
            В нашем Хендлинг –центре:
            <ul>
                <li>  Индивидуальные и групповые занятия в зале с опытным инструктором</li>
                <li>Фитнес тренинг для всех собак,</li>
                <li>Курс послушания, коррекция поведения для собак, работаем в группах и индивидуально</li>
                <li>Грумминг-студия, все виды ухода за Вашим питомцем</li>
                <li>Фотоуслуги, возможен выезд к заказчику</li>
                <li>Инструктор по вязкам</li>
            </ul>







        </div>
        <div class="image3">
            <img src="/images/landings/landing1/dog3.png" alt="" style="
                                    /* -webkit-filter: drop-shadow(11px 9px 3px rgba(0, 39, 7, 0.5)); */
                                    /* filter: drop-shadow(11px 9px 6px rgba(0, 39, 7, 0.5)); */
                                    /* margin-left: -53px; */
                                    /* position: absolute; */
                                    /* top: 0; */
                                    height: 528px;
                                    float: right;
                                    ">
        </div>
        <div class="back_image" style="
    height: 700px;
    background-color: #fff;
    "></div>


    </section>

    <section id="block5" data-content="Popover with data-trigger" rel="popover" data-placement="bottom" data-original-title="Title" data-trigger="hover">
        <div class="text1" style="
    float: left;
    width: 80%;
    padding: 70px;
    font-size: 14pt;
    z-index: 111;
">
            <H1>Дрессировка</H1>
            Вы приобрели породистого щенка, уже успели полюбить его и конечно Вам хочется чтобы .Ваш питомец был самым красивым ,самым лучшим и воспитанным. С каждым днем вопросов по его уходу и правильному выращиванию становится все больше.
        </div>
        <div class="image1">
            <img src="/images/landings/dog1.png" alt="" style="
                                    /* -webkit-filter: drop-shadow(11px 9px 3px rgba(0, 39, 7, 0.5)); */
                                    /* filter: drop-shadow(11px 9px 6px rgba(0, 39, 7, 0.5)); */
                                    /* margin-left: -53px; */
                                    /* position: absolute; */
                                    /* top: 0; */
                                    height: 300px;
                                    float: right;
                                    ">
        </div>

        <div class="back_image5" style="
    /* background-color: #ff9cfc; */
    /* width: 100%; */
    /* height: 400px; */
    /* border-radius: 0 0 1000px 0; */
    /* height: 0; */
    /* width: 120px; */
    border-bottom: 57px solid #f9f880;
    /* border-left: 60px solid transparent; */
    border-right: 666px solid transparent;
    /* transform: rotate(180deg); */
    /* z-index: 0; */
    /* right: 0; */
    /* width: 0; */
    "></div>
        <div class="back_image" style="
    height: 294px;
    background-color: #f9f880;
    ">


        </div>
    </section>
    <section id="block6">
        <H1>Расположение центра</H1>
        <script
                type="text/javascript"
                charset="utf-8"
                async
                src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Aea8f6f06e9a82ebd09a9b2a93a5724cab39365915242f7daaef9e2f1e910a87f&amp;width=100%25&amp;height=600&amp;lang=ru_RU&amp;scroll=false">

        </script>
    </section>
</div>

<script>





    $(window).scroll(function(){
        var $sections = $('section');
        $sections.each(function(i,el){
            var top  = $(el).offset().top-90;
            var bottom = top +$(el).height();
            var scroll = $(window).scrollTop();
            var id = $(el).attr('id');
            if( scroll > top && scroll < bottom){
                $('a.active').removeClass('active');
                $('a[href="#'+id+'"]').addClass('active');

            }
        })
    });

    $(".menu_left").on("click","a", function (event) {
        $('body,html').stop();
        // исключаем стандартную реакцию браузера
        event.preventDefault();

        // получем идентификатор блока из атрибута href
        var id  = $(this).attr('href'),

            // находим высоту, на которой расположен блок
            top = $(id).offset().top-80;

        // анимируем переход к блоку, время: 800 мс
        $('body,html').animate({scrollTop: top}, 800);
    });
</script>