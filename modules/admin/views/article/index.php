<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use app\widgets\Glyphicon;
use app\modules\admin\Module;

?>
<?= GridView::widget([
    'dataProvider' => $articlesData,
    'columns' => [
        'id',
        'title',
        [
            'class' => ActionColumn::className(),
            'template' => '{edit}',
            'header' => Html::a(Glyphicon::icon('plus') . ' ' . Module::t('main', 'Create'), ['create'], ['class' => 'btn btn-sm btn-success']),
            'buttons' => [
                'edit' => function($url, $model) {
                    return Html::a(Glyphicon::icon('pencil') . ' ' . Module::t('main', 'Edit'), ['edit', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']);
                },
            ],
        ],
    ],
]) ?>
