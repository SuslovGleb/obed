<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Url;
use common\models\User;
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use backend\modules\disorder\assets\DisorderAsset;
AppAsset::register($this);
DisorderAsset::register($this);
if (User::isAdmin()) {
    $js = <<< 'SCRIPT'
/* To initialize BS3 tooltips set this below */
$(function () { 
    $("[data-toggle='tooltip']").tooltip(); 
});;
/* To initialize BS3 popovers set this below */
$(function () { 
    $("[data-toggle='popover']").popover(); 
});
SCRIPT;
// Register tooltip/popover initialization javascript
    $this->registerJs($js);

    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin(
            [
                'brandLabel' => 'My Company',
                'brandUrl'   => Yii::$app->homeUrl,
                'options'    => [
                    'class' => 'navbar-inverse navbar-right',
//                    'style'=>'       width: 15%;
//                                    position: fixed;
//                                    /* float: right; */
//                                    right: 0;',
                ],
            ]);

        $menuItems = [
            [
                'label' => 'Фирмы',
                'items' => [
                    ['label' => 'Обзвон', 'url' => ['/site/all-firms-search']], // второй уровень
                    ['label' => 'Постоянные клиенты', 'url' => ['/site/clients']], // второй уровень
                    [
                        'label' => 'Добавить',
                        'items' => [
                            ['label' => 'Вручную.', 'url' => ['/site/firm-input']], // второй уровень
                            ['label' => '2GIS', 'url' => ['/site/firm-add']], // второй уровень
                        ]
                    ],


                ]
            ],
            [
                'label' => 'Шаблоны страниц',
                'items' => [
                    ['label' => 'главная для собак', 'url' => ['/site/dog-main']], // второй уровень
                    ['label' => 'Посмотреть', 'url' => ['/site/all-templates-search']], // второй уровень
                    ['label' => 'Добавить', 'url' => ['/site/templates-add']], // второй уровень
                    ['label' => 'Столовая ', 'url' => ['/site/template-kushat-podano']], // второй уровень
                ]
            ],
            [
                'label' => 'Почта',
                'items' => [
                    ['label' => 'Шаблон', 'url' => ['/site/mail-template']], // второй уровень
                    ['label' => 'Рассылка', 'url' => ['/site/mailSend']], // второй уровень
                ]
            ],
            [
                'label' => 'Работники',
                'items' => [
                    ['label' => 'работники', 'url' => ['/workers']],
                    ['label' => 'специальность работников', 'url' => ['/workers-speciality']],
                ]
            ],

            ['label' => 'Типы блюд', 'url' => ['/dish-type']],

            [
                'label' => 'Заказы',
                'items' => [
                    ['label' => 'Заказы клиентов', 'url' => ['/clientorder']], // второй уровень
                    ['label' => 'Создать заказ', 'url' => ['/clientorder/default/add-order']], // второй уровень

                ]
            ],
            [
                'label' => 'Распечатки',
                'items' => [
                    ['label' => 'Заказ', 'url' => ['/clientorder/default/print-order']], // второй уровень
                    ['label' => 'Меню на неделю', 'url' => ['/clientorder/default/menu']], // второй уровень
                    ['label' => 'Листовка', 'url' => ['/clientorder/default/flyer']], // второй уровень
                ]
            ],

            ['label' => 'Комплексы', 'url' => ['/complex']],
            ['label' => 'Продукты', 'url' => ['/products']],
            ['label' => 'Блюда', 'url' => ['/site/index']],
            ['label' => 'Разнорядка', 'url' => ['/disorder']],
            ['label' => 'Реклама', 'url' => ['/commercial']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            $menuItems[] = '<li>' . Html::beginForm(['/site/logout'], 'post') . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-link logout']) . Html::endForm() . '</li>';
        }

        echo Nav::widget(
            [
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items'   => $menuItems,
            ]);

        NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget(
                [
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
    <?php
}
else
{
    Yii::$app->response->redirect(Url::to('http://new.tverobedi.ru/site/login?id=1', true));
}
    ?>