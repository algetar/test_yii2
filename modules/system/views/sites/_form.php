<?php
declare(strict_types=1);

use app\components\tools\yii\CTHtml;
use app\modules\system\models\Sites;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\system\models\Sites */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="sites-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'cid')->dropDownList(Sites::getControllersList()) ?>

    <?= $form->field($model, 'index')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_root')->checkbox()->label('Корневой контроллер') ?>

    <div class="form-group">
        <?= CTHtml::submitBtn('@save') ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
