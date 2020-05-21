<?php
declare(strict_types=1);

use app\components\tools\yii\CTHtml;
use app\modules\system\models\Roles;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $controller app\modules\system\controllers\UsersController */
$controller = $this->context;

$this->title =  $controller->activePage->title;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs();
?>
<div class="roles-index">

    <p>
        <?= CTHtml::aBtn('@add', ['create']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width: 30px; text-align: center;'],
            ],
            'title',
            [
                'attribute' => 'domain_id',
                'format'    => 'raw',
                'content'   => static function (Roles $model) {
                    return CTHtml::a($model->domain->title, $model->domain->url);
                },
                'contentOptions' => ['style' => 'width: 210px'],
            ],

            ['class' => ActionColumn::class,
                'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>


</div>
