<?php use yii\helpers\Html; $this->title = 'ТОП-10 авторов'; ?>
<h1><?= Html::encode($this->title) ?></h1>
<?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-4']) ?><div class="col-auto"><?= Html::input('number', 'year', $year, ['class' => 'form-control', 'min' => 1000, 'max' => date('Y') + 1, 'aria-label' => 'Год']) ?></div><div class="col-auto"><?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?></div><?= Html::endForm() ?>
<table class="table table-striped"><thead><tr><th>#</th><th>Автор</th><th>Количество книг</th></tr></thead><tbody><?php foreach ($authors as $i => $author): ?><tr><td><?= $i + 1 ?></td><td><?= Html::a(Html::encode($author['full_name']), ['/author/view', 'id' => $author['id']]) ?></td><td><?= $author['book_count'] ?></td></tr><?php endforeach; ?></tbody></table>
<?php if (!$authors): ?><p class="text-muted">За выбранный год книг нет.</p><?php endif; ?>
