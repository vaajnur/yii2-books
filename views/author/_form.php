<?php use yii\helpers\Html; use yii\widgets\ActiveForm; $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?><?php ActiveForm::end(); ?>
