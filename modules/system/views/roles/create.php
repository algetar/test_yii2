<?php
declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $model app\modules\system\models\Roles */
/* @var $controller app\modules\system\controllers\UsersController */
$controller = $this->context;

$this->title = 'Добавление роли';
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);
?>
<div class="roles-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
