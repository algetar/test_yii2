<?php
declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $model app\modules\system\models\Sites */
/* @var $controller app\modules\system\controllers\UsersController */
$controller = $this->context;

$this->title = 'Изменение контроллера: ' . $model->title;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);
?>
<div class="sites-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
