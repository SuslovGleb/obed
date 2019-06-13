<?php

namespace frontend\controllers;

use frontend\models\BuffetsOrder;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\ComplexesSearch;
use frontend\models\DishType;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Complexes;
use frontend\models\ComplexMenu;
use frontend\models\Dishes;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */

//public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */


//    public function actionAjaxSes($DishId='')
//    {
//        if (Yii::$app->request->isAjax) {
//            $DishId=yii::$app->request->post('DishId');
//
//            return $DishId;
//        }
//
////        $DishId=yii::$app->request->post('DishId');
////        $session = new Session;
////        $session->open();
////        $session['DishId']=$DishId;
////        $session->close();
////                return $DishId;
//    }




    public function actionDishes(/*$dishType=4,*/
        $complexId = 0, $complexName = '', $complexImage = '')
    {
//        $dishes = Dishes::find()->Dishes($complexId/*,$dishType*/);
////        echo '<pre>';
////        print_r($dishes);
////        echo '</pre>';die;
//        $dishTypes = ComplexMenu::find()->dishTypes($complexId);
//
//
//        $session = Yii::$app->session;
//        Yii::$app->params['drivers'] = $this->FindDrivers();
//        return $this->render(
//            'dishes', [
//                'session'      => $session,
//                'dishes'       => $dishes,
//                'dishTypes'    => $dishTypes,
//                //'dishType'=>$dishType,
//                'complexId'    => $complexId,
//                'complexName'  => $complexName,
//                'complexImage' => $complexImage,
//            ]);
    }









    public function actionIndex($complexImage = '')
    {
        return $this->redirect('/dishes/index');
//        $complexes = Complexes::find()->allComplexes();
//        $dishTypes = ComplexMenu::find()->dishTypes(true);
//        $dishes = Dishes::find()->Dishes(true);
//
//        return $this->render(
//            'index', [
//                'dishes'       => $dishes,
//                'complexes'    => $complexes,
//                'dishTypes'    => $dishTypes,
//                'complexImage' => $complexImage,
//            ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render(
                'login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render(
                'contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render(
            'signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render(
            'requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render(
            'resetPassword', [
            'model' => $model,
        ]);
    }
}
