<?php

namespace app\controllers;

use app\controllers\ClientController as Controller;
use app\models\Article;

class ArticleController extends Controller
{
    public function actionView($id)
    {
        $article = Article::find()->where(['id' => $id])->asArray()->one();

        return $this->render('view', [
            'article' => $article,
        ]);
    }
}
