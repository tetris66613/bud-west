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
        'type',
        'level',
        'parent',
        'order',
        'title',
        'enabled',
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
