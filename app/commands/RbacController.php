<?php

namespace app\commands;

use app\models\User;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * Назначение ролей пользователям
     *
     * @throws \yii\base\Exception
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $createBook = $auth->createPermission('createBook');
        $createBook->description = 'Create a book';
        $auth->add($createBook);

        $updateBook = $auth->createPermission('updateBook');
        $updateBook->description = 'Update a book';
        $auth->add($updateBook);

        $deleteBook = $auth->createPermission('deleteBook');
        $deleteBook->description = 'Delete a book';
        $auth->add($deleteBook);

        $createAuthor = $auth->createPermission('createAuthor');
        $createAuthor->description = 'Create a author';
        $auth->add($createAuthor);

        $updateAuthor = $auth->createPermission('updateAuthor');
        $updateAuthor->description = 'Update a author';
        $auth->add($updateAuthor);

        $deleteAuthor = $auth->createPermission('deleteAuthor');
        $deleteAuthor->description = 'Delete a author';
        $auth->add($deleteAuthor);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $createBook);
        $auth->addChild($user, $updateBook);
        $auth->addChild($user, $deleteBook);
        $auth->addChild($user, $createAuthor);
        $auth->addChild($user, $updateAuthor);
        $auth->addChild($user, $deleteAuthor);

        foreach (User::find()->all() as $u) {
            $auth->assign($user, $u->id);
        }
    }
}
