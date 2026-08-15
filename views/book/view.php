<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
$this->title = $model->title; ?>
<h1><?= Html::encode($this->title) ?></h1>
<?php if (!Yii::$app->user->isGuest): ?><p><?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?> <?= Html::a('Удалить', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger', 'data' => ['method' => 'post', 'confirm' => 'Удалить книгу?']]) ?></p><?php endif; ?>
<?php if ($model->cover): ?><?= Html::img(['/uploads/covers/' . $model->cover], ['style' => 'max-width:300px;max-height:420px', 'class' => 'mb-3', 'alt' => $model->title]) ?><?php endif; ?>
<?= DetailView::widget(['model' => $model, 'attributes' => ['title', ['label' => 'Авторы', 'format' => 'raw', 'value' => implode(', ', array_map(fn($a) => Html::a(Html::encode($a->full_name), ['/author/view', 'id' => $a->id]), $model->authors))], 'publication_year', 'isbn', ['attribute' => 'description', 'format' => 'ntext']]]) ?>
