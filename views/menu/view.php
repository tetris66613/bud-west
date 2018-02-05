<?php

use yii\helpers\Html;

$this->title = $menu['title'];

?>

<?php if ($articleUnique) : ?>
<h2><?= $articleUnique['title'] ?></h2>

<?= $articleUnique['content'] ?>
<?php endif; ?>

<?php foreach ($articleList as $article) : ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::a($article['title'], ['article/view', 'id' => $article['id']]) ?></h3>
        </div>
        <div class="panel-body">
            <?= $article['description'] ?>
        </div>
    </div>
<?php endforeach; ?>
