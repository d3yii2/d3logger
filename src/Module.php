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

    public string $theme = self::THEME_BLANKON;

    //Default 
    public const THEME_BLANKON = 'views';
    
    public const THEME_ARGON = 'views-argon';
    
    public $controllerNamespace = 'd3logger\controllers';
        
    public function init()
    {
        parent::init();
        
        $this->viewPath = '@vendor/d3yii2/d3logger/src/' . $this->theme;        
    }
    
    
    /**
     * @return string
     */
    public function getLabel(): string
    {
        return Yii::t('d3logger', 'd3yii2/d3logger');
    }
}
