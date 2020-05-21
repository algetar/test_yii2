<?php
declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $model app\modules\system\models\Users */
/* @var $controller app\modules\system\controllers\UsersController */

$controller = $this->context;
$this->title = 'Изменение пользователя: ' . $model->name;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);
?>
<div class="users-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
