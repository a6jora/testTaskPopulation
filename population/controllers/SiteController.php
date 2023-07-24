<?php

namespace app\controllers;

use app\services\DatabaseService;
use app\services\PopulationService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
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
     * @return string
     */
    public function actionLoadByUrl(): string
    {
        $params = Yii::$app->request->get();
        $service = new DatabaseService();
        $service->setDataFromURL($params['url']);
        return json_encode(['upload' => true]);

    }

    public function actionPopulation() {
        $params = Yii::$app->request->get();
        $service = new PopulationService();

        return json_encode(['population' => $service->getPopulation($params)]);
    }

    public function actionUpload()
    {
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('data');
            $service = new DatabaseService();
            $service->setDataFromFile($file);
            return json_encode(['upload' => true]);
        }
        return json_encode(['upload' => false]);
    }

}
