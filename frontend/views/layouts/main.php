<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use common\models\User;
use yii\base\Widget;
use backend\controllers\Svg;
use yii\widgets\Pjax;

use frontend\assets\AppAsset;
use frontend\assets\FontAsset;
use frontend\modules\dishes\assets\DishesAsset;
use frontend\modules\complex\assets\ComplexAsset;
use frontend\modules\order\assets\OrderAsset;

OrderAsset::register($this);
DishesAsset::register($this);
ComplexAsset::register($this);
AppAsset::register($this);
FontAsset::register($this);

$svg = new Svg;

$Kastrul = $svg->Kastrul('#FFF', 60);
$LK = $svg->Lk('#000', 40);
$Exit = $svg->ExitIco('#000', 35);
$Login = $svg->Login('#000', 35);
?>


<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl;
        ?>/images/favicon.ico" type="image/x-icon"/>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <?php $this->beginBody() ?>
    <body>
    <? Pjax::begin(['id' => 'main', 'timeout' => 3000,]); ?>
    <?php

//    NavBar::begin([
//        'options' => [
//            'class' => 'menu',
//            'id' => 'menu',
//        ],
//    ]);

    //echo DateTimePicker::widget([
    //    'name' => 'datetime_10',
    //    'options' => ['placeholder' => 'Select operating time ...'],
    //    'convertFormat' => true,
    //    'pluginOptions' => [
    //        'format' => 'd-M-Y g:i A',
    //        'startDate' => '01-Mar-2014 12:00 AM',
    //        'todayHighlight' => true
    //    ],
    //    'language' => 'ru',
    //]);
    $menuItems = [
        //['label' => 'Home', 'url' => ['/site/index']],
        [
                'label' => 'Комплексы',
                'url' => ['/complex'],
                 'active' => in_array(\Yii::$app->controller->module->id,['complex']),
//                'active' => \Yii::$app->controller->id == 'complex',
                'options' => ["class" => "topMenu"],
        ],
        [
                'label' => 'Меню',
                'url' => ['/dishes'],
                'active' => in_array(\Yii::$app->controller->module->id,['dishes']),
//                'active' => \Yii::$app->controller->id == 'dishes',
                'options' => ["class" => "topMenu"]],
        //['label' => 'About', 'url' => ['/site/about']],
        //['label' => 'Contact', 'url' => ['/site/contact']],
    ];


//    NavBar::end();


    if (Yii::$app->user->isGuest) {
        $signup = '<div class="user_menu">';
        $signup .= Html::tag('div', $LK,
            $options = [
                'id' => "lk",
                'class' => "not_print",
            ]
        );
        $signup .= '<div>Регистрация</div>';
        $signup .= '</div>';

        $userMenu .= Html::a(
            $signup,
            ['/site/signup', 'id' => 1], ['class' => 'profile-link']);

        $login .= '<div class="user_menu">';
        $login .= Html::tag('div', $Login,
            $options = [
                'id' => "login",
                'class' => "not_print",
            ]
        );
        $login .= '<div>Войти</div>';
        $userMenu .= Html::a(
            $login,
            ['/site/login', 'id' => 1], ['class' => 'profile-link']);


        $userMenu .= '</div>';
    } else {

        $userMenu = '<div class="user_menu">';

        if (User::isAdmin()) {
            $lk .= Html::tag('div', $LK,
                $options = [
                    'id' => "lk",
                    'class' => "not_print",
                ]
            );
            $lk .= '<div>Админка</div>';


            $userMenu .= Html::a(
                $lk,
                ['/admin/site/index'], ['class' => 'profile-link']);
            $userMenu .= '</div>';
        }
        else  if (User::isOperator()) {
            $userMenu.='';

        }else {
            $lk .= Html::tag('div', $LK,
                $options = [
                    'id' => "lk",
                    'class' => "not_print",
                ]
            );
            $lk .= '<div style="    line-height: 18px;
    padding-top: 9px;">Личный кабинет</div>';


            $userMenu .= Html::a(
                $lk,
                ['/site/user/lk', 'id' => 1], ['class' => 'profile-link']);
            $userMenu .= '</div>';
        }




        $userMenu .= '<div class="user_menu">';


        $exit .= Html::tag('div', $Exit,
            $options = [
                'id' => "exit",
                'class' => "not_print",
            ]
        );
        $userMenu .= Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                $exit . '<div>Выйти</div>',
                ['class' => 'btn btn-link logout']
            )

            . Html::endForm();
        $userMenu .= '</div>';

    }
//$userMenu='';

    ?>

    <header>
        <div id="head_box">

            <div id="midle_box">
                <div id="slogan">
                    <h2 style="
                        font-size: 39pt;
                    ">"Кушать подано"</h2>
                    <div id="logoBox">
                        <a href="/dishes"><?= Html::img('/images/logo.svg',
                                $options = [
                                    'width' => '100%',
                                    'style' => '-webkit-filter: drop-shadow(11px 9px 3px rgba(0, 39, 7, 0.5));
                        filter: drop-shadow(11px 9px 6px rgba(0, 39, 7, 0.5));
                        '
                                ]); ?></a>
                    </div><h1 style="
                        font-size: 32px;
                        float: right;
                        padding-right: 54px;
                    ">Доставка обедов</h1>
                </div>
                <div id="head_telephones">+7-904-024-61-72
                </div>

            </div>
            <div id="user_menu">
                <div class="user_menu_box">
                    <?= $userMenu ?>
                </div>
            </div>

        </div>
        <div id="main_menu_box">
            <div class="Main_menu">


                <?= Nav::widget([
                    'options' => ['class' => ''],
                    'items' => $menuItems,
                    'encodeLabels' => false,
//                    'activateParents' => true
                ]); ?>
            </div>
            <div class="orderMenuBox" data-toggle="modal" data-target="#basket_detail">
                <div class="orderMenuLabel">Заказ</div>
                <div class="basket_box">


                    <!--                    <div id="basket_detail"></div>-->
                    <div class="basket">


                        <?=$Kastrul?>
                    </div>

                    <?php
                    $basketCount = 0;
                    if ($basketCount) {
                        $cartClass = 'active';
                        ?>
                        <div class="basket_count"><?= $basketCount; ?></div>
                        <?php
                    } else {
                        $cartClass = 'inactive';
                        ?>
                        <div class="basket_count" style="display:none"><?= $basketCount; ?></div>
                        <?php
                    }
                    ?>


                </div>

            </div>
            <?php $divClient = Html::tag('div', '',
                $options = [
                    'id' => "client",
                    'class' => "not_print",
                ]
            );
            $divViewport = Html::tag('div', '',
                $options = [
                    'id' => "viewport",
                    'class' => "not_print",
                ]
            );
            $divBasketCloud = Html::tag('div', '',
                $options = [
                    'id' => "basket_cloud",
                    'class' => "not_print active",
                ]
            );

            $imgLogo = Html::img('/images/logo.svg',
                $options = [
                    'width' => '100%',
                    'style' => '-webkit-filter: drop-shadow(11px 9px 3px rgba(0, 39, 7, 0.5));
                                    filter: drop-shadow(11px 9px 6px rgba(0, 39, 7, 0.5));
                                    margin-left: -53px;'
                ]);

            $divBasketCount = Html::tag('div', '',
                $options = [
                    'id' => "basket_count",
                    'class' => "not_print",
                ]
            );
            $divBasketSpeach = Html::tag('div', '',
                $options = [
                    'id' => "basket_speech",
                    'class' => "not_print active",
                ]
            );


            //  echo $divClient;
            // echo Html::tag('div',
            //       $divViewport . $divBasketCloud . $imgLogo . $divBasketCount,
            //               $options = [
            //                   'id'=>"basket",
            //                   'class'=>"not_print active",
            //                   ]
            //           );
            //// echo $divBasketSpeach;
            $orderEmpty = true;

            ?>



    </header>
    <?php
    if (Yii::$app->session->has('order')) $orderEmpty = false;
    echo $this->render('@frontend/modules/order/views/default/index',[
        'modal'=>true,
        'orderEmpty'=>$orderEmpty,
    ]);
    //            $divConstruction = Html::tag('div', '',
    //                $options = [
    //                    'id'=>"construction",
    //                    'class'=>"not_print active",
    //                    ]
    //            );
    $divMenu = Html::tag('div', '',
        $options = [
            'id' => "menu",
            'class' => "not_print",
        ]
    );
    $divDays = Html::tag('div', '',
        $options = [
            'id' => "days",
            'class' => "not_print",
        ]
    );
    $divDays = Html::tag('div', '',
        $options = [
            'id' => "days",
            'class' => "not_print",
        ]
    );


    echo $divConstruction;


    ?>


    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>


    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
    <? Pjax::end(); ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>