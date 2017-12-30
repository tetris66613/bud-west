<?php

namespace app\modules\admin\models;

use Yii;
use app\models\CustomModel as Model;
use app\models\Article;
use dosamigos\tinymce\TinyMce;

class ArticleForms extends Model
{
    const SCENARIO_CREATE = 'article_create';
    const SCENARIO_EDIT = 'article_edit';
    const SCENARIO_DELETE = 'article_delete';

    public $id = 0;
    public $title;
    public $content;

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_CREATE => ['title', 'content'],
            self::SCENARIO_EDIT => ['id', 'title', 'content'],
            self::SCENARIO_DELETE => ['id'],
        ]);
    }

    public function formName()
    {
        return $this->getScenario();
    }

    public function rules()
    {
        return [
            [['id', 'title', 'content'], 'required'],
            [['title', 'content'], 'filter', 'filter' => 'trim'],
            [['title', 'content'], 'string'],
        ];
    }

    public function requestCreate()
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $article = new Article();
            $article->setAttributes($this->getActiveAttributes());

            if ($article->save()) {
                return true;
            }
        }

        return false;
    }

    public function renderContentField($form)
    {
        return $form->field($this, 'content')->widget(TinyMce::className(), [
            'language' => Yii::$app->language,
            'options' => ['rows' => 20],
        ]);
    }

    public function requestEdit(&$article)
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $article->setAttributes($this->getActiveAttributes());

            if ($article->save()) {
                return true;
            }
        }

        return false;
    }

    public function requestDelete()
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $article = Article::findOne($this->id);

            if ($article && $article->delete()) {
                return true;
            }
        }

        return false;
    }
}
