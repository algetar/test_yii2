<?php
declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $model app\modules\system\models\Users */
/* @var $controller app\modules\system\controllers\UsersController */
$controller = $this->context;

$this->title = 'Добавление пользователя';
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);
?>
<div class="users-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
