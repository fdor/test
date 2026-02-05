<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\Author $model */
/** @var app\models\Subscription $subscription */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Yii::$app->user->can('updateAuthor')): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>

        <?php if(Yii::$app->user->can('deleteAuthor')): ?>
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
            'first_name',
            'second_name',
            'last_name',
        ],
    ]) ?>

    <br>

    <h5>Подписка на автора</h5>
    <div style="background: #eee; padding: 10px;">
        <?php $form = ActiveForm::begin() ?>
            <?= $form->field($subscription, 'phone')->widget(MaskedInput::class, [
                'mask' => '7 (999) 999-99-99'
            ]); ?>
            <button type="submit" class="btn btn-primary">Подписаться на новые книги автора</button>
        <?php ActiveForm::end() ?>
    </div>
</div>
