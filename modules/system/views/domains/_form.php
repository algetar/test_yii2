<?php
declare(strict_types=1);

use app\components\tools\yii\CTHtml;
use yii\bootstrap\ActiveForm;
use app\modules\system\models\Domains;

/* @var $this yii\web\View */
/* @var $model app\modules\system\models\Domains */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="domains-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'cid')->dropDownList(Domains::unregisteredDomainsList()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'native_cid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'index')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= CTHtml::submitBtn('@save') ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
