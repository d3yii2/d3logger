<?php

use cornernote\returnurl\ReturnUrl;
use d3logger\components\LogViewer;
use eaBlankonThema\widget\ThReturnButton;

/**
 * @var LogViewer $logViewer
 */
$this->title = 'Logfiles';
$this->setPageHeader($this->title);
$this->setPageIcon('bars');
echo ThReturnButton::widget(['backUrl' => ReturnUrl::getUrl()]);
?>
<h3><?= $logViewer->showFileContent?></h3>
<div class="panel">
    <div class="panel-body" style="max-height: 800px; overflow-y:scroll; padding:10px">
        <h4><?= $logViewer->getPath() ?></h4>
        <?= str_replace("\n", '<br/>', file_get_contents($logViewer->showFileContent)) ?? 'File Not found'; ?>
    </div>
</div>


