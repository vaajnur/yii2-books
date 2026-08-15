<?php

namespace app\controllers;

use app\models\Book;
use Yii;
use yii\web\Controller;

class ReportController extends Controller
{
    public function actionIndex(?int $year = null): string
    {
        $year ??= (int) date('Y');
        $authors = Book::find()->alias('b')->select(['a.id', 'a.full_name', 'book_count' => 'COUNT(DISTINCT b.id)'])
            ->innerJoin('{{%book_author}} ba', 'ba.book_id = b.id')->innerJoin('{{%author}} a', 'a.id = ba.author_id')
            ->where(['b.publication_year' => $year])->groupBy(['a.id', 'a.full_name'])->orderBy(['book_count' => SORT_DESC, 'a.full_name' => SORT_ASC])->limit(10)->asArray()->all();
        return $this->render('index', ['authors' => $authors, 'year' => $year]);
    }
}
