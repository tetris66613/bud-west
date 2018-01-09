<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\models\Article;
use app\modules\admin\controllers\AdminController;
use app\modules\admin\models\ArticleForms;
use app\modules\admin\Module;

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
        $article = Article::find()->where(['articles.id' => $id])->joinWith('articleRelate')->one();

        if (!$article) {
            throw new NotFoundHttpException(Module::t('main', 'Article not found'));
        }

        $model = new ArticleForms(['scenario' => ArticleForms::SCENARIO_EDIT]);
        if ($model->requestEdit($article)) {
            return $this->redirect(['index']);
        }
        $model->setAttributes($article->getAttributes());
        $model->setRelatedAttributes($article->articleRelate);

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

        throw new NotFoundHttpException(Module::t('main', 'Article not found'));
    }

    public function actionUpdateModel($scenario)
    {
        $model = new ArticleForms(['scenario' => $scenario]);
        $model->load(Yii::$app->request->post());

        $content = $this->renderPartial('fields', [
            'form' => new \yii\widgets\ActiveForm(),
            'model' => $model,
            'attributes' => ['relatedType', 'menuRelatedType', 'menuRelatedLevel', 'relatedId'],
        ]);

        return $content;
    }
}
