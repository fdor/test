<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Yii::$app->user->can('updateBook')): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if(Yii::$app->user->can('deleteBook')): ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description',
            'year',
            'isbn',
            [
                'attribute' => 'photo',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<img src="/uploads/' . $model->photo . '" width=100 />';
                },
            ],
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
        ],
    ]) ?>

</div>
