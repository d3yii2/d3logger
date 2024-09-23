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
class LogViewerItem extends Model
{    
    public array $directories = [];
    
    public array $files = [];
    
    public ?string $route = null;

    /**
     * @param string $route
     * @param array $directories
     * @param array $files
     * @return void
     */
    public function populate(string $route, array $directories, array $files): void
    {
        $this->route = $route;
        $this->directories = $directories;
        $this->files = $files;
    }
}
