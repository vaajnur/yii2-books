<?php use yii\helpers\Html; $this->title = 'Редактировать книгу'; ?><h1><?= Html::encode($this->title) ?></h1><?= $this->render('_form', compact('model', 'authors')) ?>
