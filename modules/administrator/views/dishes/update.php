<?php
declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $model app\modules\administrator\models\Dishes */
/* @var $controller app\modules\system\controllers\UsersController */
$controller = $this->context;

$this->title = 'Изменить: ' . $model->title;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);
?>
<div class="dishes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
