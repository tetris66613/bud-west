<?php

use yii\grid\GridView;

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
    ],
]) ?>
