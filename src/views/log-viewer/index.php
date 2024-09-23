<?php

use cornernote\returnurl\ReturnUrl;
use d3logger\components\LogViewer;
use d3logger\models\LogViewerItem;
use d3system\helpers\D3FileHelper;
use eaBlankonThema\widget\ThButtonDropDown;
use eaBlankonThema\widget\ThGridView;
use eaBlankonThema\widget\ThNav;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

/**
 * @var LogViewer $logViewer
 * @var string $route
 * @var ArrayDataProvider $dataProvider
 */
$this->title = 'Logfiles';
$this->setPageHeader($this->title);
$this->setPageIcon('bars');

$userDirectories=$logViewer->userDirectories();
echo ThButtonDropDown::widget([
    'label' => 'Directories: ',
    'list' => array_combine($userDirectories,$userDirectories),
    'listUrl' => [
        '',
        'route' => '@id'
    ],
    'selected' => $route,
]);
$ru = ReturnUrl::getToken();
echo ThGridView::widget([
    'dataProvider' => $dataProvider,
    'actionColumnTemplate' => false,
    'tableOptions' => [
        'class' => 'table table-success dataTable table-striped floatThead-table fileList'
    ],
    'columns' => [
        [
            'attribute' => 'files',
            'format' => 'raw',
            'value' => static function (LogViewerItem $model) use ($ru) {
                $navItems = [];
                foreach ($model->files as $file) {
                    $fileName = D3FileHelper::getBasename($file);
                    $navItems[] = [
                        'label' => $fileName,
                        'url' => Url::to([
                            '/d3logger/log-viewer/view',
                            'route' => $model->route,
                            'file' => $fileName,
                            'ru' => $ru,
                        ])
                    ];
                }
                return ThNav::widget(['items' => $navItems]);
            }
        ],
    ]
]);
