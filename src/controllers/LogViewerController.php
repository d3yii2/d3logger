<?php

namespace d3logger\controllers;

use d3logger\components\LogViewer;
use d3logger\models\File;
use d3logger\models\LogViewerItem;
use d3system\yii2\LayoutController;
use Yii;
use yii\base\Exception;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\HttpException;

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

    /**
     * @throws HttpException
     * @throws Exception
     */
    public function actionIndex(?string $route = null, string $file = null): string
    {
        $this->menuRoute = 'd3logger/log-viewer';
        $logViewer = new LogViewer($route, $file);

        $fileModels = [];
        if ($route) {
            
            $files = $logViewer->getCurrentDirectoryFiles();
            
            $fileModels = [];
            
            foreach ($files as $file) {
                $fileModel = new File();
                $fileModel->populate($file);
                $fileModels[] = $fileModel;
            }
        }

        $dataProvider = new ArrayDataProvider(['allModels' => $fileModels]);

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'logViewer' => $logViewer,
                'route' => $route
            ]
        );
    }

    public function actionView(?string $route = null, string $file = null): string
    {
        $this->menuRoute = 'd3logger/log-viewer';
        $logViewer = new LogViewer($route, $file);
        return $this->render(
            'view',
            compact('logViewer', 'file')
        );
    }
}
