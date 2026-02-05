<?php

namespace app\commands;

use app\models\User;
use Yii;
use yii\console\Controller;

class UserController extends Controller
{
    /**
     * Создать пользователя
     *
     * @param $email
     * @param $password
     */
    public function actionCreate($email, $password)
    {
        $user = new User();
        $user->email = $email;
        $user->password = Yii::$app->security->generatePasswordHash($password);
        $user->save();

        echo 'User created' . PHP_EOL;
    }
}
