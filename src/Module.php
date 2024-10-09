<?php

namespace d3logger;

use d3system\yii2\base\D3Module;
use Yii;

/**
 * Class Module
 * @package d3yii2\d3logger
 */
class Module extends D3Module
{
    public array $accessRoles = []; 
    
    public $controllerNamespace = 'd3logger\controllers';
       
    /**
     * @return string
     */
    public function getLabel(): string
    {
        return Yii::t('d3logger', 'd3yii2/d3logger');
    }
}
