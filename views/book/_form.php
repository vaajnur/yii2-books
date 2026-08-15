<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/** @var app\models\Book $model */
/** @var array $authors */
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'authorIds')->listBox($authors, ['multiple' => true, 'size' => 7]) ?>
<?= $form->field($model, 'publication_year')->input('number') ?>
<?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
<?= $form->field($model, 'coverFile')->fileInput(['accept' => 'image/png,image/jpeg,image/webp']) ?>
<?php if ($model->cover): ?><?= Html::img(Yii::getAlias('@web/uploads/covers/') . rawurlencode($model->cover), ['style' => 'max-height:160px', 'alt' => 'Текущая обложка']) ?><?php endif; ?>
<div class="mt-3"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></div>
<?php ActiveForm::end(); ?>
