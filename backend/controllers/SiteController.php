<?php

namespace backend\controllers;


use backend\models\ComplexesSearch;
use backend\models\ComplexMenu;
use backend\models\Dishes;
use backend\models\DishType;
use backend\models\DishesSearch;
use backend\models\Firms;
use backend\models\FirmSiteManualInput;
use backend\models\Spheres;
use backend\models\FirmSite;
use backend\models\FirmEmails;
use backend\models\FirmAddresses;
use backend\models\FirmsSphere;
use backend\models\FirmsTel;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * SiteController implements the CRUD actions for Dishes model.
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => '/admin/upload/mail', // Directory URL address, where files are stored.
                'path' => '@webroot/upload/mail', // Or absolute path to directory where files are stored.
            ],
            'file-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/upload/mail', // Directory URL address, where files are stored.
                'path' => Yii::$app->basePath . '/web/upload/mail', // Or absolute path to directory where files are stored.
                'uploadOnlyImage' => false, // Для загрузки не только изображений.
            ],
            'images-get' => [
                'class' => 'vova07\imperavi\actions\GetAction',
                'url' => '/admin/upload/mail', // Directory URL address, where files are stored.
                'path' => '@webroot/upload/mail', // Or absolute path to directory where files are stored.
                'options' => ['basePath' => Yii::getAlias('@backend/web/upload/mail')],
                'type' => '0',
            ],
            'files-get' => [
                'class' => 'vova07\imperavi\actions\GetAction',
                'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/upload/mail', // Directory URL address, where files are stored.
                'path' => Yii::$app->basePath . '/web/upload/mail', // Or absolute path to directory where files are stored.
                'type' => '1',//GetAction::TYPE_FILES,
            ],

        ];
    }

    public function actionAddComment()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model_tel = FirmsTel::find()->where(['id' => trim($data['id'])])->one();
            $model_tel->comment = $data['text'];
            $model_tel->save();
        }
    }

    public function actionComplexDishes()
    {
        if (Yii::$app->request->isAjax) {

            $complex_id=yii::$app->request->post('complex_id');
            $dish_type_id=yii::$app->request->post('dish_type_id');
            $dishes    = Dishes::find()->Dishes($complex_id,$dish_type_id);


            var_dump($dishes);
        }

//        $DishId=yii::$app->request->post('DishId');
//        $session = new Session;
//        $session->open();
//        $session['DishId']=$DishId;
//        $session->close();
//                return $DishId;
    }
    public function actionSessionComplex()
    {

        $DishId=yii::$app->request->post('DishId');
        $session = new Session;
        $session->open();
        $session['DishId']=$DishId;
        $session->close();
                return $DishId;
    }
    public function actionAddFirmFromGis()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $model = new Firms;

            $regular_zero = [
                'name' => '/->> (.*)<<-/i',
                'sphere' => '/[^-]>>.*\n(.*)/i'
            ];
            $regular = [
                'address' => '/^\n\n(.*)/',
                'skype' => '/skype:(.*)/',
                'icq' => '/icq:(.*)/',
                // 'facebook'=>'/Facebook:(.*)/',
                // 'twitter'=>'/Twitter:(.*)/',
                // 'vk'=>'/ВКонтакте:(.*)/',
                // 'site'=>'/\nhttp:\/\/(.*)/',
                'site' => '/http:\/\/(.*)/',
                'telephone' => '/тел\. (.*)/',
                // 'instagram'=>'/Instagram:(.*)/',
                'mailto' => '/mailto:(.*)/',

            ];
            $str = $data['newText'];


            foreach ($str as $key => $firm_data) {
                if (trim($firm_data)) {
                    if ($key == 0) {
                        foreach ($regular_zero as $reg_key => $reg) {
                            // $matches=[];
                            preg_match_all($reg, $firm_data, $matches);
                            $processed_data[$reg_key] = $matches[1][0];

                        }
                    } else {
                        foreach ($regular as $reg_key => $reg) {
                            //  $matches=[];
                            preg_match_all($reg, $firm_data, $matches);

                            if ($matches[1][0]) {

                                $processed_data[$key][$reg_key] = $matches[1];
                            }
                        }

                    }
                }

            }
            //  var_dump($processed_data);


            $name = Firms::find()->where(['name' => $processed_data['name']])->one();
            $processed_data['naaaaaame'] = $name;

            if ($name == null) {
                $model->name = $processed_data['name'];
                $model->date = date("Y-m-d H:i:s");
                $model->save();

                $firm_id = Yii::$app->db->lastInsertID;
                $processed_data['firm_id'] = $firm_id;

                $sphere = $processed_data['sphere'];
                $arSphere = explode(",", $sphere);
                unset($sphere);

                foreach ($arSphere as $sphere) {
                    $sph = Spheres::find()->where(['sphere' => trim($sphere)])->one();

                    if ($sph == null) {
                        $model = new Spheres;
                        $model->sphere = trim($sphere);
                        $model->save();
                        $sphere_id = Yii::$app->db->lastInsertID;
                    } else {
                        $sphere_id = $sph['id'];
                    }
                    // $processed_data['sph'][]=$sphere_id;
                    $sphere_model = new FirmsSphere;
                    $sphere_model->firm_id = $firm_id;
                    $sphere_model->sphere_id = $sphere_id;
                    $sphere_model->save();
                }
                foreach ($processed_data as $info) {
                    if (is_array($info)) {
                        if (isset($info['address'])) {
                            foreach ($info['address'] as $address) {
                                $model = new FirmAddresses;
                                $model->firm_id = $firm_id;
                                $model->address = $address;
                                $model->save();
                                $addr_id = Yii::$app->db->lastInsertID;
                                if (isset($info['telephone'])) {
                                    foreach ($info['telephone'] as $telephone) {
                                        $model_tel = new FirmsTel;
                                        $model_tel->firm_id = $firm_id;
                                        $model_tel->address_id = $addr_id;
                                        $model_tel->telephone = $telephone;
                                        $model_tel->save();
                                    }
                                }
                            }

                        } else {
                            if (isset($info['telephone'])) {

                                $model = new FirmAddresses;
                                $model->firm_id = $firm_id;
                                $model->address = 'Без адреса';
                                $model->save();
                                $addr_id = Yii::$app->db->lastInsertID;
                                foreach ($info['telephone'] as $telephone) {
                                    $model_tel = new FirmsTel;
                                    $model_tel->firm_id = $firm_id;
                                    $model_tel->address_id = $addr_id;
                                    $model_tel->telephone = $telephone;
                                    $model_tel->save();
                                }


                            }

                        }


                        if (isset($info['site'])) {
                            foreach ($info['site'] as $site) {
                                $model = new FirmSite;
                                $model->firm_id = $firm_id;
                                $model->site = $site;
                                $model->save();
                            }
                        }
                        if (isset($info['email'])) {
                            foreach ($info['email'] as $email) {
                                $model = new FirmEmails;
                                $model->firm_id = $firm_id;
                                $model->email = $email;
                                $model->save();
                            }
                        }
                    }
                }


//
//
//

//
//
//                    foreach($data['firm']['info'] as $info)
//                    {
//                         $model = new FirmAddresses;
//                        $model->firm_id=$firm_id;
//                        $model->address=$info['address'];
//                        $model->save(); 
//
//                        $addr_id=Yii::$app->db->lastInsertID;
//
//                        foreach($info['tel'] as $tel)
//                        {
//                            $model_tel = new FirmsTel;
//                            $model_tel->firm_id=$firm_id;
//                            $model_tel->address_id=$addr_id;
//                            $model_tel->telephone=$tel;
//                            $model_tel->save(); 
//                        }
//                    }  
//
//
//
//                    foreach($data['firm']['sphere'] as $sphere)
//                    {
//                         $model = new FirmsSphere;
//                        $model->firm_id=$firm_id;
//                        $model->sphere=$sphere;
//                        $model->save();                      
//                    }
                $processed_data['duble'] = true;


            } else {
                $processed_data['duble'] = false;
            }
            return json_encode($processed_data);
        }
    }

    public function actionSendMailToFirm()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $mail = FirmEmails::find()->where(['id' => trim($data['id'])])->one();

            $mail->status = 1;
            $mail->save();

            $message = Yii::$app->mailer->compose();

            // Прикрепление файла из локальной файловой системы:
            $message->attach(Yii::getAlias('@webroot/mailtemplates/attaches/dinners.pdf'));
            //$message->attach(Yii::getAlias('@webroot/images/logo.png'));

            $cid = $message->embed(Yii::getAlias('@webroot/images/logo.png'));
            $message
                ->setFrom('komplex@tverobedi.ru')
                ->setTo($data['email'])
                ->setSubject('Доставка обедов от 85 рублей')// тема письма
                ->setTextBody('')
                ->setHtmlBody('<table style="color: #5a5a5a;">
    <tbody>
        <tr style="width: 90%;">
            <td style="font-size: 20px;    text-align: center;">
                <p>ДОСТАВКА ОБЕДОВ ДОМОЙ И В ОФИС</p>
                <p>от 85 рублей</p>
            </td>
            <td>
                <img width="100px" src="' . $cid . '">
                <p style="    font-weight: bold;">8-904-016-25-31</p>
            </td>
        </tr>
    <tr style="    text-align: center;">
        <td colspan="2" style="    color: #0088ff;    font-size: 20px;">
        </td>
    </tr>
    <tr>
        <td colspan="2"><p>У нас:</p>
            <ul style="    color: #005594;">
                <li>Большой опыт (свыше 10 лет) работы на рынке услуг общественного питания;</li>
                <li>Опытные повара и квалифицированный обслуживающий персонал;</li>
                <li>Ежедневные поставки самой свежей продукции;</li>
                <li>Невысокие цены и большой ассортимент.</li>
            </ul>
        </td>        
    </tr>
    <tr>
        <td colspan="2"><p> Мы предлагаем полный комплекс услуг в сфере общественного питания:</p>
            <ul style="    color: #005594;">
                <li>Доставка обедов (от 85 рублей)</li>
                <li>Организация буфетов и выездных пунктов питания</li>
                <li>Организация работы производственных столовых на предприятиях</li>
                <li>Другие услуги ( выездное питание и пр. по договоренности с заказчиком)</li>
            </ul>
        </td>        
    </tr>
    <tr>        
        <td colspan="2">
            <p style="font-size: 16px;    font-weight: 600;"> В письме вложен документ!</p>
            <p>В нем весь наш ассортимент!*</p>
        </td>
    </tr>
     <tr>
        <td colspan="2">

            <p>* Цена на комплексные обеды составлена для индивидуальных заказов. При коллективных заказах на питание, предоставляется скидка.</p>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="    font-size: 17px;    font-weight: 700;"> 
          <p>Тел.:  42-06-64,8-904-016-25-31</p>
          <p>E-mail: komplex@tverobedi.ru</p>
        </td>
    </tr>
</tbody>
</table>')
                ->send();
        }

    }

    public function actionChangeTelStatus()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $tel = FirmsTel::find()->where(['id' => $data['id']])->one();
            print_r($tel);
            $tel->status = $data['status'];
            $tel->save();
        }

    }

    public function actionAddFirmEmail()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model = new FirmEmails;
            $model->firm_id = $data['id'];
            if (isset($data['tel_id']))
                $model->tel_id = $data['tel_id'];
            $model->email = $data['email'];
            $model->save();
            $mail_id = Yii::$app->db->lastInsertID;
            return $mail_id;
        }

    }

    public function actionFindFirmById()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $firm = Firms::find()->oneFirm($data['id']);
            return $this->renderPartial('firmSearch', [
                'firm' => $firm,
                'one_firm' => true,
//            'dataProvider' => $dataProvider,
            ]);
        }

    }

    public function actionDogMain()
    {

        return $this->render('dogMain', [
                      // 'images' => $images,
        ]);
    }
    public function actionAllTemplatesSearch()
    {

        return $this->render('templatesSearch', [
                      // 'images' => $images,
        ]);
    }
    public function actionTemplatesAdd()
    {

        return $this->render('templatesAdd', [
            // 'images' => $images,
        ]);
    }
    public function actionTemplateKushatPodano()
    {

        $arComplexes=ComplexesSearch::find()->where('active=1')->asArray()->all();
        foreach ($arComplexes as $complexKey=>$complexVal)
        {

            $arComplexMenu=ComplexMenu::find()->where('complex_id='.$complexVal['id'])->asArray()->all();
            foreach ($arComplexMenu as $menuKey=>$menuVal) {
                $type=DishType::find()->where('id='.$menuVal['type_id'])->one();
                $arComplexMenu[$menuKey]['typeName']=$type['type'];
                $arComplexMenu[$menuKey]['typeImage']=$type['image'];
            }
            $arComplexes[$complexKey]['dishes']=$arComplexMenu;
        }


        return $this->render('templateKushatPodano', [
             'arComplexes' => $arComplexes,
        ]);
    }

    public function actionAllFirmsSearch()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $firms = Firms::find()->AllFirms( $data['arChB']);

            foreach ($firms as $key => $firm) {
                foreach ($firm['firmsSpheres'] as $firmKey => $sphere) {
                    $sph = Spheres::find()->where(['id' => $sphere['sphere_id']])->asArray()->one();
                    $firms[$key]['firmsSpheres'][$firmKey]['sphere'] = $sph['sphere'];
                }
            }

            return $this->renderPartial('firmSearch', [
                'firms' => $firms,
                'arChB' => $data['arChB'],
//            'dataProvider' => $dataProvider,
            ]);
        }
        else
        {
            $firms = Firms::find()->AllFirms();
            foreach ($firms as $key => $firm) {
                foreach ($firm['firmsSpheres'] as $firmKey => $sphere) {
                    $sph = Spheres::find()->where(['id' => $sphere['sphere_id']])->asArray()->one();
                    $firms[$key]['firmsSpheres'][$firmKey]['sphere'] = $sph['sphere'];
                }
            }
            return $this->render('firmSearch', [
                'firms' => $firms,
//            'dataProvider' => $dataProvider,
            ]);
        }

    }

    public function actionFirmAdd()
    {
        return $this->render('firmAdd', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionClients()
    {
        $model=$this->findClients();
        return $this->render('/firms/clients', [

            'firms' => $model,
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionFirmInput()
    {
        $model = new FirmSiteManualInput();
        $post=Yii::$app->request->post();

       // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        if ($model->load($post)) {

            $model->date = date("Y-m-d H:i:s");
            $model->save();
            $firm_id = Yii::$app->db->lastInsertID;

            $post=$post['Firms'];
            if (isset($post['address'])) {
                $model = new FirmAddresses;
                $model->firm_id = $firm_id;
                $model->address = $post['address'];
                $model->save();
                $addr_id = Yii::$app->db->lastInsertID;
            }
            if (isset($post['telephone']))  {
                $model_tel = new FirmsTel;
                $model_tel->firm_id = $firm_id;
                $model_tel->address_id = $addr_id;
                $model_tel->status = 6;
                $model_tel->telephone = $post['telephone'];
                $model_tel->save();
            }
            if (isset($post['site'])) {
                    $model = new FirmSite;
                    $model->firm_id = $firm_id;
                    $model->site = $post['site'];
                    $model->save();

            }
            if (isset($post['email'])) {
                    $model = new FirmEmails;
                    $model->firm_id = $firm_id;
                    $model->email = $post['email'];
                    $model->save();
            }


            $model = new Firms();
            return $this->render('/firms/firmInput', [
                'model' => $model,
            ]);
//            return $this->redirect(['/firms/view', 'id' => $model->id]);
        } else {
            return $this->render('/firms/firmInput', [
                'model' => $model,
            ]);
        }
    }

    public function actionMailTemplate()
    {
        return $this->render('mailTemplate', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Complexes models.
     * @return mixed
     */

    public function actionComplexes()
    {
        $searchModel = new ComplexesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('complexes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Dishes models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['/dishes']);
//        $searchModel = new DishesSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('@app/modules/dishes/index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
    }
//
//    /**
//     * Displays a single Dishes model.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionView($id)
//    {
//        return $this->render('/dishes/view', [
//            'model' => $this->findDishes($id),
//        ]);
//    }
//
//    /**
//     * Creates a new Dishes model.
//     * If creation is successful, the browser will be redirected to the 'view' page.
//     * @return mixed
//     */
//    public function actionCreate()
//    {
//        $model = new Dishes();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['/dishes/view', 'id' => $model->id]);
//        } else {
//            return $this->render('/dishes/create', [
//                'model' => $model,
//            ]);
//        }
//    }
//
//    /**
//     * Updates an existing Dishes model.
//     * If update is successful, the browser will be redirected to the 'view' page.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findDishes($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['/dishes/view', 'id' => $model->id]);
//        } else {
//            return $this->render('/dishes/update', [
//                'model' => $model,
//            ]);
//        }
//    }
//
//    /**
//     * Deletes an existing Dishes model.
//     * If deletion is successful, the browser will be redirected to the 'index' page.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionDelete($id)
//    {
//        $this->findDishes($id)->delete();
//
//        return $this->redirect(['index']);
//    }
//
//    /**
//     * Finds the Dishes model based on its primary key value.
//     * If the model is not found, a 404 HTTP exception will be thrown.
//     * @param integer $id
//     * @return Dishes the loaded model
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    protected function findDishes($id)
//    {
//        if (($model = Dishes::findOne($id)) !== null) {
//            return $model;
//        } else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
//    }

    protected function findFirms($id)
    {
        if (($model = Firms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findClients()
    {
        if (($model = Firms::find()->clients()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
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
}
