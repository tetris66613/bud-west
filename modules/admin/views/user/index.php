<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\widgets\Glyphicon;

?>

<?= GridView::widget([
    'dataProvider' => $usersData,
    'columns' => [
        'id',
        'username',
        'email',
        [
            'attribute' => 'role',
            'value' => function($data){
                return $data->renderRole();
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Html::a(Glyphicon::icon('plus') . ' Create', ['create'], ['class' => 'btn btn-sm btn-success']),
            'template' => "{edit}",
            'buttons' => [
                'edit' => function($url, $model, $key) {
                    return Html::a(Glyphicon::icon('pencil') . ' Edit', ['edit', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']);
                },
            ],
        ],
    ],
]) ?>
