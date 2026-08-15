<?php

namespace app\controllers;

use app\models\Author;
use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class BookController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => ['class' => AccessControl::class, 'only' => ['create', 'update', 'delete'], 'rules' => [['actions' => ['create', 'update', 'delete'], 'allow' => true, 'roles' => ['@']]]],
            'verbs' => ['class' => VerbFilter::class, 'actions' => ['delete' => ['POST']]],
        ];
    }
    public function actionIndex(): string
    {
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => Book::find()->with('authors')->orderBy(['created_at' => SORT_DESC])])]);
    }
    public function actionView(int $id): string { return $this->render('view', ['model' => $this->findModel($id)]); }
    public function actionCreate()
    {
        $model = new Book();
        if ($this->save($model, true)) return $this->redirect(['view', 'id' => $model->id]);
        return $this->render('create', ['model' => $model, 'authors' => $this->authorList()]);
    }
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        if ($this->save($model, false)) return $this->redirect(['view', 'id' => $model->id]);
        return $this->render('update', ['model' => $model, 'authors' => $this->authorList()]);
    }
    private function save(Book $model, bool $isNew): bool
    {
        if (!$model->load(Yii::$app->request->post())) return false;
        $model->coverFile = UploadedFile::getInstance($model, 'coverFile');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->validate() || !$model->uploadCover() || !$model->save(false)) { $transaction->rollBack(); return false; }
            $model->syncAuthors(); $transaction->commit();
            if ($isNew) $this->notifySubscribers($model);
            Yii::$app->session->setFlash('success', 'Книга сохранена.'); return true;
        } catch (\Throwable $e) { if ($transaction->isActive) $transaction->rollBack(); throw $e; }
    }
    private function notifySubscribers(Book $book): void
    {
        $phones = (new \yii\db\Query())->select('phone')->distinct()->from('{{%subscription}}')->where(['author_id' => $book->authorIds])->andWhere(['not', ['phone' => null]])->column();
        foreach ($phones as $phone) {
            try { Yii::$app->smsPilot->send($phone, "Новая книга «{$book->title}» ({$book->publication_year}) появилась в каталоге."); }
            catch (\Throwable $e) { Yii::warning('Не удалось отправить SMS на ' . $phone . ': ' . $e->getMessage(), __METHOD__); }
        }
    }
    public function actionDelete(int $id)
    {
        $model = $this->findModel($id); $cover = $model->cover; $model->delete();
        if ($cover) { $path = Yii::getAlias('@webroot/uploads/covers/') . $cover; if (is_file($path)) @unlink($path); }
        Yii::$app->session->setFlash('success', 'Книга удалена.'); return $this->redirect(['index']);
    }
    private function findModel(int $id): Book { $model = Book::find()->with('authors')->where(['id' => $id])->one(); if (!$model) throw new NotFoundHttpException('Книга не найдена.'); return $model; }
    private function authorList(): array { return ArrayHelper::map(Author::find()->orderBy('full_name')->all(), 'id', 'full_name'); }
}
