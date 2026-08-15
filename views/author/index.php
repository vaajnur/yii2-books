<?php use yii\grid\GridView; use yii\helpers\Html; $this->title = 'Авторы'; ?>
<div class="d-flex justify-content-between align-items-center"><h1><?= Html::encode($this->title) ?></h1><?php if (!Yii::$app->user->isGuest): ?><?= Html::a('Добавить автора', ['create'], ['class' => 'btn btn-success']) ?><?php endif; ?></div>
<?= GridView::widget(['dataProvider' => $dataProvider, 'columns' => [['attribute' => 'full_name', 'format' => 'raw', 'value' => fn($m) => Html::a(Html::encode($m->full_name), ['view', 'id' => $m->id])], ['label' => 'Книг', 'value' => fn($m) => count($m->books)]]]) ?>
