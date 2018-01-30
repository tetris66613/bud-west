<?php

namespace app\controllers;

use app\controllers\ClientController as Controller;
use app\models\Menu;
use app\models\Article;
use app\models\ArticleType;
use app\models\ArticleRelate;

class MenuController extends Controller
{
    public function actionView($id)
    {
        $menu = Menu::find()->where(['id' => $id])->asArray()->one();
        $articleUnique = Article::find()->joinWith('articleRelate')->where(['type_id' => ArticleType::RELATE_MENU_UNIQUE, 'related_id' => $id])->asArray()->one();
        $articleList = Article::find()->joinWith('articleRelate')->where(['type_id' => ArticleType::RELATE_MENU_LIST, 'related_id' => $id])->asArray()->all();

        return $this->render('view', [
            'menu' => $menu,
            'articleUnique' => $articleUnique,
            'articleList' => $articleList,
        ]);
    }
}
