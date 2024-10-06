<?php

use cornernote\returnurl\ReturnUrl;
use d3logger\components\LogViewer;
use d3logger\models\LogViewerItem;
use d3system\helpers\D3FileHelper;
use eaBlankonThema\widget\ThButton;
use eaBlankonThema\widget\ThButtonDropDown;
use eaBlankonThema\widget\ThGridView;
use eaBlankonThema\widget\ThNav;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use d3logger\models\File;
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
        'name',
        'size',
        'lastModified',
        [
            'attribute' => 'view',
            'format' => 'raw',
            'value' => static function (File $model) use ($ru, $route) {
                return ThButton::widget([
                    'label' => 'View',
                    'type' => ThButton::TYPE_PRIMARY,
                    'icon' => ThButton::ICON_CHECK,
                    'link' => Url::to([
                        '/d3logger/log-viewer/view',
                        'route' => $route,
                        'file' => $model->name,
                        'ru' => $ru,
                    ])
                ]);
            }
        ],
        [
            'attribute' => 'download',
            'format' => 'raw',
            'value' => static function (File $model) use ($route) {
                return ThButton::widget([
                    'label' => 'Download',
                    'type' => ThButton::TYPE_PRIMARY,
                    'icon' => ThButton::ICON_ARROW_DOWN,
                    'link' => Url::to([
                        '/d3logger/log-viewer/download',
                        'route' => $route,
                        'file' => $model->name,
                    ])
                ]);
            }
        ],
    ]
]);
