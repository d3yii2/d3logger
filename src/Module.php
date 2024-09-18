<?php

namespace d3logger;

use Yii;

/**
 * Class Module
 * @package d3yii2\d3logger
 */
class Module extends \yii\base\Module
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
