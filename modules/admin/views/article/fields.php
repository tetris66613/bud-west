<?= $model->renderFormFields($form, isset($attributes) ? $attributes : [], [
    '<relatedType' => ['div', ['id' => 'update-article-model']],
    '>relatedId' => ['div'],
]) ?>
