<?php

use d3logger\components\LogViewer;
use d3logger\models\LogViewerItem;
use d3system\helpers\D3FileHelper;
use d3system\widgets\ThBadge;
use d3yii2\d3files\widgets\D3FilesPreviewWidget;
use d3yii2\d3labels\models\D3lLabelHistory;
use eaBlankonThema\widget\ThButton;
use eaBlankonThema\widget\ThDetailView;
use eaBlankonThema\widget\ThGridView;
use eaBlankonThema\widget\ThNav;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii2d3\d3persons\dictionaries\UserDictionary;

/**
 * @var LogViewer $logViewer
 */

$route = $logViewer->getRoute();
?>
<?= ThButton::widget(
        [
            'label' => $route ? 'Logs' : '',
            'link' => Url::to(['/d3logger/log-viewer']),
            'type' => ThButton::TYPE_PRIMARY,
            'icon' => $route ? ThButton::ICON_ARROW_LEFT : ThButton::ICON_REFRESH
        ]
) ?>
<h3><?= $route ?? 'Logs' ?></h3>

<?= ThGridView::widget([
    'dataProvider' => $dataProvider,
    'actionColumnTemplate' => false,
    'tableOptions' => [
        'class' => 'table table-success dataTable table-striped floatThead-table fileList'
    ],
    'columns' => [
        [
            'attribute' => 'files',
            'format' => 'raw',
            'value' => static function (LogViewerItem $model) {

                $navItems = [];

                foreach ($model->files as $file) {

                    $fileName = D3FileHelper::getBasename($file);
                    
                    $navItems[] = [
                        'label' => $fileName,
                        'url' => Url::to(['/d3logger/log-viewer', 'route' => $model->route, 'file' => $fileName])
                    ];
                }
                
                return ThNav::widget(['items' => $navItems]);
            }
        ],
        [
            'attribute' => 'subdirectories',
            'format' => 'raw',
            'value' => static function (LogViewerItem $model) use ($logViewer) {
                $navItems = [];
                foreach ($model->directories as $dir) {

                    $item = [
                        'label' => D3FileHelper::getBasename($dir),
                        'url' => Url::to(['/d3logger/log-viewer', 'route' => $logViewer->getRoute($dir)])
                    ];

                    $navItems[] = $item;
                }
                return ThNav::widget(['items' => $navItems]);
            }
        ],
    ]
]) 
?>
<?php if ($logViewer->showFileContent): ?>
    <div class="panel">
        <div class="panel-body" style="max-height: 800px; overflow-y:scroll; padding:10px">
            <h4><?= basename($logViewer->showFileContent) ?></h4>
            <?= str_replace("\n", '<br/>', file_get_contents($logViewer->showFileContent)) ?? 'File Not found'; ?>
        </div>
    </div>
<?php endif; ?>

