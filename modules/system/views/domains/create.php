<?php
declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $model app\modules\system\models\Domains */
/* @var $controller app\modules\system\controllers\UsersController */
$controller = $this->context;

$this->title =  'Добавление модуля';
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);
?>
<div class="domains-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
