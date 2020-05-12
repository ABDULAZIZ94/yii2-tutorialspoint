<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegistrationForm;

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
}
