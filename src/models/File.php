<?php

namespace d3logger\models;


use d3logger\components\LogViewer;
use d3system\helpers\D3FileHelper;
use yii\base\Model;
use yii\helpers\FileHelper;
use Yii;

/**
 * This is the model class for the Directory of Files
 */
class File extends Model
{
    public string $name;

    public int $size;    
    
    public string $lastModified;

    /**
     * @param $filePath
     * @return void
     */
    public function populate($filePath): void
    {
        $this->name = D3FileHelper::getBasename($filePath);
        $this->size = filesize($filePath);
        $lastModifiedTIme = filemtime($filePath);
        $this->lastModified = $lastModifiedTIme ? date('Y:m:d H:i:s', $lastModifiedTIme) : 0;
    }
}
