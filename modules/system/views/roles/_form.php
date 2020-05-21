<?php
declare(strict_types=1);

use app\components\tools\yii\CTHtml;
use app\modules\system\models\Domains;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\system\models\Roles */
/* @var $form yii\widgets\ActiveForm */

$js = "
$('#roles-domain_id').on('change', function () {
    let a = $(\"a[class^='btn']\");
    let url0 = '%s', url1 = '%s', url;
    
    if ($(this).prop('value') === '') {
        url = url0;
        a.attr('class', 'btn btn-success');
        a.html('<span class=\"glyphicon glyphicon-plus\"></span>')
    } else {
        url = url1.replace('_id', $(this).prop('value'));
        a.attr('class', 'btn btn-primary');
        a.html('<span class=\"glyphicon glyphicon-pencil\"></span>')
    }
    a.prop('href', url);
});
";
$js = sprintf($js, Url::to(['domains/create', '_node' => 0]), Url::to(['domains/update', 'id' => '_id', '_node' => 0]));

$this->registerJs($js);
?>
<div class="roles-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php

    $btn = (null === $model->domain_id) ?
        CTHtml::aBtn('@create:', ['domains/create', '_node' => 0]) :
        CTHtml::aBtn('@update:', ['domains/update', 'id' => $model->domain_id, '_node' => 0]);

    echo $form->field($model, 'domain_id')
        ->dropDownList(Domains::domainsList(), ['prompt' => '---',])
        ->hint($btn, ['style'=>['margin' => 0, 'padding-left' => 0]]);
    ?>

    <div class="form-group">
        <?= CTHtml::submitBtn('@save') ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
