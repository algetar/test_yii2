<?php
declare(strict_types=1);

use app\components\tools\yii\CTHtml;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\administrator\models\Ingredients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ingredients-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'available')->checkbox() ?>

    <div class="form-group">
        <?= CTHtml::submitBtn('@save') ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
