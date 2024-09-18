<?php



use yii\db\Migration;
use d3logger\accessRights\D3loggerViewUserRole;

class m240919_010707_create_roleD3loggerView  extends Migration {

    public function up() {

        $auth = Yii::$app->authManager;
        $role = $auth->createRole(D3loggerViewUserRole::NAME);
        $auth->add($role);

    }

    public function down() {
        $auth = Yii::$app->authManager;
        $role = $auth->createRole(D3loggerViewUserRole::NAME);
        $auth->remove($role);
    }
}
