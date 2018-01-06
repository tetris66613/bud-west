<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use app\widgets\Glyphicon;

?>

<?= GridView::widget([
    'dataProvider' => $menuData,
    'columns' => [
        'id',
        [
            'attribute' => 'type',
            'value' => function ($data) {
                return $data->renderType();
            },
        ],
        [
            'attribute' => 'level',
            'value' => function ($data) {
                return $data->renderLevel();
            },
        ],
        'parent',
        [
            'attribute' => 'parent',
            'value' => function ($data) {
                return $data->renderParentTitle();
            }
        ],
        'order',
        'title',
        [
            'attribute' => 'enabled',
            'value' => function ($data) {
                return $data->renderEnabled();
            },
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{edit}',
            'header' => Html::a(Glyphicon::icon('plus') . ' Create', ['create'], ['class' => 'btn btn-sm btn-success']),
            'buttons' => [
                'edit' => function($url, $model) {
                    return Html::a(Glyphicon::icon('pencil') . ' Edit', ['edit', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']);
                },
            ],
        ],
    ],
]) ?>
