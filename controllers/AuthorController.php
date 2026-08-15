<?php

namespace app\controllers;

use app\models\Author;
use app\models\Subscription;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AuthorController extends Controller
{
    public function behaviors(): array
    {
        return ['access' => ['class' => AccessControl::class, 'only' => ['create', 'update', 'delete'], 'rules' => [['allow' => true, 'roles' => ['@']]]],
            'verbs' => ['class' => VerbFilter::class, 'actions' => ['delete' => ['POST'], 'subscribe' => ['POST']]]];
    }
    public function actionIndex(): string { return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => Author::find()->with('books')->orderBy('full_name')])]); }
    public function actionView(int $id): string { return $this->render('view', ['model' => $this->findModel($id), 'subscription' => new Subscription(['author_id' => $id])]); }
    public function actionSubscribe(int $id)
    {
        $author = $this->findModel($id); $subscription = new Subscription(['author_id' => $id]);
        if ($subscription->load(Yii::$app->request->post()) && $subscription->save()) { Yii::$app->session->setFlash('success', 'Вы подписались на SMS о новых книгах автора.'); return $this->redirect(['view', 'id' => $id]); }
        return $this->render('view', ['model' => $author, 'subscription' => $subscription]);
    }
    public function actionCreate() { $model = new Author(); if ($model->load(Yii::$app->request->post()) && $model->save()) return $this->redirect(['view', 'id' => $model->id]); return $this->render('create', ['model' => $model]); }
    public function actionUpdate(int $id) { $model = $this->findModel($id); if ($model->load(Yii::$app->request->post()) && $model->save()) return $this->redirect(['view', 'id' => $id]); return $this->render('update', ['model' => $model]); }
    public function actionDelete(int $id) { $this->findModel($id)->delete(); return $this->redirect(['index']); }
    private function findModel(int $id): Author { $model = Author::find()->with('books')->where(['id' => $id])->one(); if (!$model) throw new NotFoundHttpException('Автор не найден.'); return $model; }
}
