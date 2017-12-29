<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\models\Article;
use app\modules\admin\controllers\AdminController;
use app\modules\admin\models\ArticleForms;

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
        $model = new ArticleForms(['scenario' => ArticleForms::SCENARIO_CREATE]);

        if ($model->requestCreate()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $article = Article::findOne($id);

        if (!$article) {
            throw new NotFoundHttpException(Yii::t('main', 'Article not found'));
        }

        $model = new ArticleForms(['scenario' => ArticleForms::SCENARIO_EDIT]);
        if ($model->requestEdit($article)) {
            return $this->redirect(['index']);
        }
        $model->setAttributes($article->getAttributes());

        $modelDelete = new ArticleForms(['scenario' => ArticleForms::SCENARIO_DELETE]);
        $modelDelete->setAttributes($article->getAttributes());

        return $this->render('edit', [
            'model' => $model,
            'modelDelete' => $modelDelete,
        ]);
    }

    public function actionDelete()
    {
        $model = new ArticleForms(['scenario' => ArticleForms::SCENARIO_DELETE]);

        if ($model->requestDelete()) {
            return $this->redirect(['index']);
        }

        throw new NotFoundHttpException(Yii::t('app', 'Article not found or already removed'));
    }
}
