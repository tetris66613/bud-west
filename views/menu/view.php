<?php

use yii\helpers\Html;

$this->title = $menu['title'];

?>

<?php if ($articleUnique) : ?>
<h2><?= $articleUnique['title'] ?></h2>

<?= $articleUnique['content'] ?>
<?php endif; ?>

<?php foreach ($articleList as $article) : ?>
    <h4><?= Html::a($article['title'], ['article/view', 'id' => $article['id']]) ?></h4>
    <?= $article['description'] ?>
<?php endforeach; ?>
