<?php
declare(strict_types=1);

use app\components\tools\models\selector\Selector;
use yii\bootstrap\ActiveForm;
use app\components\tools\yii\CTHtml;
use yii\web\YiiAsset;

/* @var $selector Selector */

$this->registerJsFile('@web/js/selector.js', ['depends' => YiiAsset::class]);
$this->registerJsFile('@web/js/select-restrict.js', ['depends' => YiiAsset::class]);
?>

<div class="users-add-modules">
    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($selector, 'choiceIds')->textInput([
        'maxlength' => true,
        'type'      => 'hidden',
        'id'        => 'choice-ids'
    ])->label(false) ?>

    <?= $form->field($selector, 'choiceLimit')->textInput([
        'type'      => 'hidden',
        'id'        => 'choice-limit'
    ])->label(false) ?>

    <div class="form-group">
        <?= CTHtml::submitBtn('@save:Принять') ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
