<?php
declare(strict_types=1);

/* @var $this yii\web\View */
/* @var $model app\modules\administrator\models\Ingredients */
/* @var $controller app\modules\system\controllers\UsersController */
$controller = $this->context;

$this->title = 'Добавить ингредиент';
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);
?>
<div class="ingredients-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
