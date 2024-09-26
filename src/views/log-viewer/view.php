<?php

use cornernote\returnurl\ReturnUrl;
use d3logger\components\LogViewer;
use eaBlankonThema\widget\ThReturnButton;

/**
 * @var LogViewer $logViewer
 * @var string $file
 */
$this->title = 'Logfiles';
$this->setPageHeader($this->title);
$this->setPageIcon('bars');
echo ThReturnButton::widget(['backUrl' => ReturnUrl::getUrl()]);
?>
<h4><?= $logViewer->getRoute() ?> / <?= $file ?></h4>
<div class="panel">
    <div class="panel-body log-file">
        <?= str_replace("\n", '<br/>', file_get_contents($logViewer->showFileContent)) ?? 'File Not found'; ?>
    </div>
</div>


<style>
    .log-file {
        font-family: "Sometype Mono", monospace;
        font-optical-sizing: auto;
        font-weight: 400;
        font-size: 12px;
        font-style: normal;
        max-height: 800px;
        overflow-y: scroll;
        padding: 10px;
    }

</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sometype+Mono:ital,wght@0,400..700;1,400..700&display=swap"
      rel="stylesheet">
