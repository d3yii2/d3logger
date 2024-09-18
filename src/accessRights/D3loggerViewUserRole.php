<?php

namespace d3logger\accessRights;


use CompanyRights\components\UserRoleInterface;
use yii2d3\d3persons\accessRights\CompanyOwnerUserRole;
use Yii;

class D3loggerViewUserRole implements UserRoleInterface
{

    public const NAME = 'D3loggerView';

    /**
    * @inheritdoc
    */
    public function getType(): string
    {
        return self::TYPE_REGULAR;
    }

    /**
    * @inheritdoc
    */
    public function getLabel(): string
    {
        return Yii::t('', 'Access to Log files');
    }

    /**
    * @inheritdoc
    */
    public function getComments(): string
    {
        return Yii::t('', '');
    }

    /**
    * @inheritdoc
    */
    public function getIcon(): string
    {
        return '';
    }

    /**
    * @inheritdoc
    */
    public function getLabelType(): string
    {
        return '';
    }

    /**
    * @inheritdoc
    */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
    * @inheritdoc
    */
    public function getAssigments(): array
    {
        return [];
    }

    /**
    * @inheritdoc
    */
    public function canAssign(): bool
    {
        return Yii::$app->user->can(CompanyOwnerUserRole::NAME);
    }

    /**
    * @inheritdoc
    */
    public function canView(): bool
    {
        //return \Yii::$app->user->can(SystemAdminUserRole::NAME);
        return Yii::$app->user->can(CompanyOwnerUserRole::NAME);
    }

    /**
    * @inheritdoc
    */
    public function canRevoke(): bool
    {
        return Yii::$app->user->can(CompanyOwnerUserRole::NAME);
    }

}

