<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\db\ActiveQuery ;
use yii\db\Query;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegistrationForm;
use app\models\UploadImageForm;
use app\models\MyUser;
use app\components\Taxi;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actionSpeak($message = "default message") { 
        return $this->render("speak",['message' => $message]); 
     } 
     
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $model = new ContactForm();
        $model->scenario = ContactForm::SCENARIO_EMAIL_FROM_USER;
        if ($model->load(Yii::$app->request->post()) && $model->
           contact(Yii::$app->params ['adminEmail'])) {
              Yii::$app->session->setFlash('contactFormSubmitted');  
              return $this->refresh();
        }
        return $this->render('contact', [
           'model' => $model,
        ]);
     }
     public function actionShowContactModel() {
        $mContactForm = new \app\models\ContactForm();
        $mContactForm->name = "contactForm";
        $mContactForm->email = "user@gmail.com";
        $mContactForm->subject = "subject";
        $mContactForm->body = "body";
        return \yii\helpers\Json::encode($mContactForm);
     }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionTestWidget() { 
        return $this->render('testwidget'); 
    }
    
    public function actionTestResponse() {
        return \Yii::$app->response->sendFile('favicon.ico');
    }

    public function actionMaintenance() {
        echo "<h1>Maintenance</h1>";
    }

    public function actionRoutes() {
        return $this->render('routes');
    }

    public function actionRegistration() { 
        $model = new RegistrationForm(); 
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request>post())) { 
           Yii::$app->response->format = Response::FORMAT_JSON; 
           return ActiveForm::validate($model); 
        } 
        return $this->render('registration', ['model' => $model]); 
    }

    public function actionAdHocValidation() {
        $model = DynamicModel::validateData([
           'username' => 'John',
           'email' => 'john@gmail.com'
        ], [
           [['username', 'email'], 'string', 'max' => 12],
           ['email', 'email'],
        ]);
         
        if ($model->hasErrors()) {
           var_dump($model->errors);
        } else {
           echo "success";
        }
    }

    public function actionOpenAndCloseSession() {
        $session = Yii::$app->session;
        // open a session
        $session->open();
        // check if a session is already opened
        if ($session->isActive) echo "session is active";
        // close a session
        $session->close();
        // destroys all data registered to a session
        $session->destroy();
     }

     public function actionAccessSession() {

        $session = Yii::$app->session;
         
        // set a session variable
        $session->set('language', 'ru-RU');
         
        // get a session variable
        $language = $session->get('language');
        var_dump($language);
               
        // remove a session variable
        $session->remove('language');
               
        // check if a session variable exists
        if (!$session->has('language')) echo "language is not set";
               
        $session['captcha'] = [
           'value' => 'aSBS23',
           'lifetime' => 7200,
        ];
        var_dump($session['captcha']);
    }
    
    public function actionShowFlash() {
        $session = Yii::$app->session;
        // set a flash message named as "greeting"
        $session->setFlash('greeting', 'Hello user!');
        return $this->render('showflash');
    }

    public function actionReadCookies() { 
        // get cookies from the "request" component 
        $cookies = Yii::$app->request->cookies; 
        // get the "language" cookie value 
        // if the cookie does not exist, return "ru" as the default value 
        $language = $cookies->getValue('language', 'ru'); 
        // an alternative way of getting the "language" cookie value 
        if (($cookie = $cookies->get('language')) !== null) { 
           $language = $cookie->value; 
        } 
        // you may also use $cookies like an array 
        if (isset($cookies['language'])) { 
           $language = $cookies['language']->value; 
        } 
        // check if there is a "language" cookie 
        if ($cookies->has('language')) echo "Current language: $language"; 
    }

    public function actionSendCookies() { 
        // get cookies from the "response" component 
        $cookies = Yii::$app->response->cookies; 
        // add a new cookie to the response to be sent 
        $cookies->add(new \yii\web\Cookie([ 
           'name' => 'language', 
           'value' => 'ru-RU', 
        ])); 
        $cookies->add(new \yii\web\Cookie([
           'name' => 'username', 
           'value' => 'John', 
        ])); 
        $cookies->add(new \yii\web\Cookie([ 
           'name' => 'country', 
           'value' => 'USA', 
        ])); 
    }
    
    public function actionUploadImage() {
        $model = new UploadImageForm();
        if (Yii::$app->request->isPost) {
           $model->image = UploadedFile::getInstance($model, 'image');
           if ($model->upload()) {
              // file is uploaded successfully
              echo "File successfully uploaded";
              return;
           }
        }
        return $this->render('upload', ['model' => $model]);
    }

    public function actionFormatter(){
        return $this->render('formatter');
    }

    public function actionPagination() {
        //preparing the query
        $query = MyUser::find();
        // get the total number of users
        $count = $query->count();
        //creating the pagination object
        $pagination = new Pagination(['totalCount' => $count, 'defaultPageSize' => 10]);
        //limit the query using the pagination and retrieve the users
        $models = $query->offset($pagination->offset)
           ->limit($pagination->limit)
           ->all();
        return $this->render('pagination', [
           'models' => $models,
           'pagination' => $pagination,
        ]);
    }

    public function actionSorting() {
        //declaring the sort object
        $sort = new Sort([
           'attributes' => ['id', 'name', 'email'], 
        ]);
        //retrieving all users
        $models = MyUser::find()
           ->orderBy($sort->orders)
           ->all();
        return $this->render('sorting', [
           'models' => $models,
           'sort' => $sort,
        ]);
    }

    public function actionProperties() {
        $object = new Taxi();
        // equivalent to $phone = $object->getPhone();
        $phone = $object->phone;
        var_dump($phone);
        // equivalent to $object->setLabel('abc');
        $object->phone = '79005448877';
        var_dump($object);
    }

    public function actionDataProvider() {
        $data = MyUser::find()->asArray()->all();
        $provider = new ArrayDataProvider([
           'allModels' => $data,
           'pagination' => [
              'pageSize' => 3,
           ],
           'sort' => [
              'attributes' => ['id', 'name'],
           ],
        ]);
        // get the rows in the currently requested page
        $users = $provider->getModels();
        var_dump($users);
    }

    public function actionDataWidget() {
        $dataProvider = new ActiveDataProvider([
           'query' => MyUser::find(),
           'pagination' => [
              'pageSize' => 20,
           ],
        ]);
        return $this->render('datawidget', [
           'dataProvider' => $dataProvider
        ]);
    }

    public function actionTestEvent() {
        $model = new MyUser();
        $model->name = "John";
        $model->email = "john@gmail.com";
        if($model->save()) {
           $model->trigger(MyUser::EVENT_NEW_USER);
        }
    }

    public function actionTestBehavior() {
        //creating a new user
        $model = new MyUser();
        $model->name = "John";
        $model->email = "john@gmail.com";
        if($model->save()){
           var_dump(MyUser::find()->asArray()->all());
        }
    }

    public function actionTestDb() {
        $users = (new \yii\db\Query())
           ->select(['id', 'name', 'email'])
           ->from('user')
           ->orderBy('name DESC')
           ->all();
        var_dump($users);
    }
}
