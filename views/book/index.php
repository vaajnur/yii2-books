<?php
use yii\helpers\Html;
use yii\widgets\ListView;
$this->title = 'Каталог книг'; ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h1><?= Html::encode($this->title) ?></h1><?php if (!Yii::$app->user->isGuest): ?><?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?><?php endif; ?></div>
<?= ListView::widget(['dataProvider' => $dataProvider, 'itemView' => '_item', 'layout' => "{summary}\n<div class=\"row\">{items}</div>\n{pager}"]) ?>
