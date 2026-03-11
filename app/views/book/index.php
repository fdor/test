<?php

use app\models\Book;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'authors',
                'value' => function ($model) {
                    $authors = $model->authors;
                    $authorsArray = [];
                    foreach ($authors as $author) {
                        $authorsArray[] = $author->getFullName();
                    }
                    return implode(', ', $authorsArray);
                },
            ],
            'title',
            [
                'attribute' => 'photo',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->photo 
                        ? Html::img('/uploads/' . $model->photo, ['width' => '50px', 'height' => '70px', 'style' => 'object-fit: cover;'])
                        : 'Нет фото';
                },
                'contentOptions' => ['style' => 'text-align: center; width: 80px;'],
            ],
            'description',
            'year',
            'isbn',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Book $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'template' => Book::getButtons(),
            ],
        ],
    ]); ?>


</div>
