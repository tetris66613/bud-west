<?php

namespace app\modules\admin\controllers;

use app\modules\admin\controllers\AdminController;
use app\models\Article;

class ArticleController extends AdminController
{
    public function actionIndex()
    {
        $articlesData = Article::gridData();

        return $this->render('index', [
            'articlesData' => $articlesData,
        ]);
    }

    public function actionCreate()
    {
        return $this->render('create');
    }
}
