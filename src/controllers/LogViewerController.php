<?php

namespace d3logger\controllers;

use d3logger\components\LogViewer;
use d3logger\models\File;
use d3logger\models\LogViewerItem;
use d3system\yii2\LayoutController;
use eaBlankonThema\widget\ThButton;
use Yii;
use yii\base\Exception;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
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
                            'download',
                        ],
                        'roles' => array_keys(Yii::$app->getModule('d3logger')->accessRoles)
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string|null $route
     * @param string|null $file
     * @return string
     * @throws Exception
     * @throws HttpException
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

        $sort = new Sort([
            'attributes' => [
                'name',
                'size',
                'lastModified',
            ],
        ]);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $fileModels,
            'sort' => $sort,
        ]);

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'logViewer' => $logViewer,
                'route' => $route
            ]
        );
    }

    /**
     * @param string|null $route
     * @param string|null $file
     * @return string
     * @throws Exception
     * @throws HttpException
     */
    public function actionView(?string $route = null, string $file = null): string
    {
        $this->menuRoute = 'd3logger/log-viewer';
        $logViewer = new LogViewer($route, $file);
        
        $fileContent = '';
        
        try {
            
            $filePath = $logViewer->getFilePath($route, $file);
            
            if ($logViewer->fileIsOversized($filePath)) {
                
                $rowLimit = 2000;
                
                $fileContent = 'File is too large! Showing only last ' . $rowLimit . ' rows...' . PHP_EOL . PHP_EOL;
                
                $fileContent .= implode(PHP_EOL, $logViewer->readFileLastLines($filePath, $rowLimit));
                
                $fileContent .= PHP_EOL . 
                    ThButton::widget([
                    'label' => 'Download full File',
                    'type' => ThButton::TYPE_PRIMARY,
                    'icon' => ThButton::ICON_ARROW_DOWN,
                    'link' => Url::to([
                        '/d3logger/log-viewer/download',
                        'route' => $route,
                        'file' => $file,
                    ])
                ]);
            } else {
                $fileContent = file_get_contents($logViewer->showFileContent);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage() . $e->getTraceAsString());
            $fileContent = 'Cannot read File';
        }
        
        return $this->render(
            'view',
            compact('logViewer', 'file', 'fileContent')
        );
    }

    /**
     * @param string|null $route
     * @param string|null $file
     * @return void
     * @throws Exception
     * @throws HttpException
     */
    public function actionDownload(?string $route = null, string $file = null): void
    {
        $logViewer = new LogViewer($route, $file);
        $logViewer->download($route, $file);
    }
}
