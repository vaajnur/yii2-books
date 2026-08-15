<?php use yii\helpers\Html; ?>
<div class="col-md-4 mb-4"><div class="card h-100">
<?php if ($model->cover): ?><?= Html::img(['/uploads/covers/' . $model->cover], ['class' => 'card-img-top', 'style' => 'height:300px;object-fit:cover', 'alt' => $model->title]) ?><?php endif; ?>
<div class="card-body"><h2 class="h5"><?= Html::a(Html::encode($model->title), ['view', 'id' => $model->id]) ?></h2><p><?= Html::encode(implode(', ', array_column($model->authors, 'full_name'))) ?></p><span class="badge bg-secondary"><?= $model->publication_year ?></span></div>
</div></div>
