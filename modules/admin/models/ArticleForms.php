<?php

namespace app\modules\admin\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\CustomModel as Model;
use app\models\Article;
use app\models\ArticleRelate;
use app\models\ArticleType;
use app\models\Menu;
use app\widgets\Ajax;
use app\modules\admin\Module;
use dosamigos\tinymce\TinyMce;

class ArticleForms extends Model
{
    const SCENARIO_CREATE = 'article_create';
    const SCENARIO_EDIT = 'article_edit';
    const SCENARIO_DELETE = 'article_delete';

    const ALL_MENU_TYPES = Menu::TYPE_CLIENT_NAVBAR - 1;
    const ALL_MENU_LEVELS = Menu::LEVEL_ROOT - 1;

    public $id = 0;
    public $title;
    public $description;
    public $content;
    public $relatedType = ArticleType::RELATE_NO;
    public $menuRelatedType = self::ALL_MENU_TYPES;
    public $menuRelatedLevel = self::ALL_MENU_LEVELS;
    public $relatedId;

    private $onchangeRelateArticle;

    public function init()
    {
        parent::init();
        $updateModelAction = ['admin/article/update-model', 'scenario' => $this->getScenario()];
        $this->onchangeRelateArticle = Ajax::post($updateModelAction, 'div#update-article-model', ['replaceFunc' => 'replaceWith']);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_CREATE => ['title', 'description', 'content', 'relatedType', 'menuRelatedType', 'menuRelatedLevel', 'relatedId'],
            self::SCENARIO_EDIT => ['id', 'title', 'description', 'content', 'relatedType', 'menuRelatedType', 'menuRelatedLevel', 'relatedId'],
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
            [['title', 'content', 'description'], 'filter', 'filter' => 'trim'],
            [['title', 'content'], 'string'],
            ['description', 'default', 'value' => ''],
            ['relatedType', 'in', 'range' => array_keys($this->relateTypeItems())],
        ];
    }

    public function dynamicRules()
    {
        return [
            ['menuRelatedType', 'in', 'range' => array_keys($this->menuRelatedTypeItems())],
            ['menuRelatedLevel', 'in', 'range' => array_keys($this->menuRelatedLevelItems())],
            ['relatedId', 'in', 'range' => array_keys($this->relatedIdItems())],
            ['relatedType', 'validateRelatedType'],
        ];
    }

    public function validateRelatedType($attr, $params)
    {
        if ($this->$attr && !$this->relatedId) {
            $this->addError($this->$attr, Module::t('main', 'Cannot use related type without relate'));
        }
    }

    public function setRelatedAttributes($related)
    {
        if ($related) {
            $this->relatedType = $related['type_id'];
            $this->relatedId = $related['related_id'];
        }
    }

    public function relateTypeItems()
    {
        if (!$this->relatedIdItems()) return ArticleType::typeNoItem();
        return ArticleType::typeItems(false);
    }

    public function menuRelatedTypeItems()
    {
        return ArrayHelper::merge([self::ALL_MENU_TYPES => Module::t('main', 'All menu types')], Menu::typeItems());
    }

    public function menuRelatedLevelItems()
    {
        return ArrayHelper::merge([self::ALL_MENU_LEVELS => Module::t('main', 'All menu levels')], Menu::levelItems());
    }

    public function relatedIdItems()
    {
        // related id not only menu for future
        $query = Menu::find();
        if ($this->menuRelatedType != self::ALL_MENU_TYPES) {
            $query->andWhere(['type' => $this->menuRelatedType]);
        }
        if ($this->menuRelatedLevel != self::ALL_MENU_LEVELS) {
            $query->andWhere(['level' => $this->menuRelatedLevel]);
        }

        $relatedIdnotin = [];

        if ($this->relatedType == ArticleType::RELATE_MENU_UNIQUE) {
            $relatedIdnotin = ArticleRelate::find()->select('related_id')->where(['type_id' => ArticleType::RELATE_MENU_UNIQUE])->column();
        }

        // exclude related id for edit themself
        if ($this->scenario == self::SCENARIO_EDIT && $this->relatedId) {
            unset($relatedIdnotin[array_search($this->relatedId, $relatedIdnotin)]);
        }

        if ($relatedIdnotin) {
            $query->andWhere(['not in', 'id', $relatedIdnotin]);
        }

        $items = ArrayHelper::map($query->asArray()->all(), 'id', 'title');
        return $items;
    }

    /**
     * @param prepend - rewrites base options
     * @param append - add additional options if in base this options not exist
     */
    public static function tinymceClientOptions(array $prepend = [], array $append = [])
    {
        return array_merge(
            $append,
            [
                'plugins' => [
                    "advlist autolink lists link charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste image"
                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                'image_class_list' => [
                    ['title' => 'None', 'value' => ''],
                    ['title' => Module::t('main', 'Responsive (max width 100%)'), 'value' => 'img-responsive'],
                ],
            ],
            $prepend
        );

        return ;
    }

    public function renderDescriptionField($form)
    {
        return $form->field($this, 'description')->widget(TinyMce::className(), [
            'language' => Yii::$app->language,
            'options' => ['rows' => 8],
            'clientOptions' => self::tinymceClientOptions(),
        ]);
    }

    public function renderContentField($form)
    {
        return $form->field($this, 'content')->widget(TinyMce::className(), [
            'language' => Yii::$app->language,
            'options' => ['rows' => 20],
            'clientOptions' => self::tinymceClientOptions(),
        ]);
    }

    public function renderRelatedTypeField($form)
    {
        return $form->field($this, 'relatedType')->dropDownList($this->relateTypeItems(), ['onchange' => $this->onchangeRelateArticle]);
    }

    public function renderMenuRelatedTypeField($form)
    {
        if (!$this->relatedType) return '';
        return $form->field($this, 'menuRelatedType')->dropDownList($this->menuRelatedTypeItems(), ['onchange' => $this->onchangeRelateArticle]);
    }

    public function renderMenuRelatedLevelField($form)
    {
        if (!$this->relatedType) return '';
        return $form->field($this, 'menuRelatedLevel')->dropDownList($this->menuRelatedLevelItems(), ['onchange' => $this->onchangeRelateArticle]);
    }

    public function renderRelatedIdField($form)
    {
        if (!$this->relatedType) return '';
        return $form->field($this, 'relatedId')->dropDownList($this->relatedIdItems());
    }

    public function requestCreate()
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $article = new Article();
            $article->setAttributes($this->getActiveAttributes());

            if ($article->save()) {
                if ($this->relatedType) {
                    $articleRelate = new ArticleRelate([
                        'article_id' => $article->id,
                        'type_id' => $this->relatedType,
                        'related_id' => $this->relatedId,
                    ]);

                    if ($articleRelate->save()) {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        }

        return false;
    }

    public function requestEdit(&$article)
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $article->setAttributes($this->getActiveAttributes());

            if ($article->save()) {
                if ($this->relatedType) {
                    if (!$article->articleRelate) {
                        $articleRelate = new ArticleRelate([
                            'article_id' => $article->id,
                            'type_id' => $this->relatedType,
                            'related_id' => $this->relatedId,
                        ]);
                    } else {
                        $articleRelate = $article->articleRelate;
                        $articleRelate->related_id = $this->relatedId;
                        $articleRelate->type_id = $this->relatedType;
                    }

                    if ($articleRelate->save()) {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        }

        return false;
    }

    public function requestDelete()
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $article = Article::findOne($this->id);

            if ($article && $article->delete()) {
                ArticleRelate::deleteAll(['article_id' => $this->id]);
                return true;
            }
        }

        return false;
    }

    public function attributeLabels()
    {
        $article = new Article();
        return array_merge($article->attributeLabels(), [
            'relatedType' => Module::t('main', 'Related type'),
            'menuRelatedType' => Module::t('main', 'Menu type'),
            'menuRelatedLevel' => Module::t('main', 'Menu level'),
            'relatedId' => Module::t('main', 'Relate with'),
        ]);
    }
}
