<?php

namespace d3logger\controllers;

use d3logger\components\LogViewer;
use d3logger\models\LogViewerItem;
use d3system\helpers\D3FileHelper;
use eaBlankonThema\yii2\web\LayoutController;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use Yii;

class LogViewerController extends LayoutController
{
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    /**
     * specify route for identifing active menu item
     */
    public $menuRoute = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'roles' => array_keys(Yii::$app->getModule('d3logger')->accessRoles)
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(?string $route = null, string $file = null)
    {
        $model = new LogViewerItem();
        $logViewer = new LogViewer($route, $file);
        $model->populate(
            $logViewer->getRoute(),
            $logViewer->getCurrentDirectories(),
            $logViewer->getCurrentDirectoryFiles(),
        );
        
        $models = [$model];
        
        $dataProvider = new ArrayDataProvider(['allModels' => $models]);
        
        return $this->render('index', compact('dataProvider', 'logViewer'));
    }
}
