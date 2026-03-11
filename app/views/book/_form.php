<?php

use app\models\Author;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photo')->fileInput() ?>

    <?php if ($model->photo): ?>
        <div class="mb-3">
            <label class="form-label">Текущее изображение:</label><br>
            <img src="/uploads/<?= $model->photo ?>" alt="<?= $model->title ?>" style="max-width: 200px; max-height: 300px; object-fit: cover; border: 1px solid #ddd; padding: 5px;">
            <br>
            <small class="text-muted">Чтобы заменить изображение, выберите новый файл выше</small>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'authorsFromForm')->checkboxList(Author::getAuthorArray()); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
