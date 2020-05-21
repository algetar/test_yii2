<?php
declare(strict_types=1);

use app\components\tools\urls\CrumbsNodes;
use app\components\tools\yii\CTHtml;
use app\modules\system\models\Users;
use app\modules\system\models\Domains;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Users */
/* @var $controller app\modules\system\controllers\UsersController */
$controller = $this->context;

$this->title = $model->name;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);

$this->registerCssFile('@web/css/view.css');
YiiAsset::register($this);

$dataProvider = new ActiveDataProvider([
    'query' => $model->getDomains(),
]);
?>
<div class="domains-view">

    <div class="btn-group" role="group" aria-label="...">
        <?php
        echo CTHtml::aBtn('@back', $controller->getUrl());
        if (!($controller->activePage->status & CrumbsNodes::PROVIDER)) {
            echo CTHtml::aBtn('@delete', ['delete', 'id' => $model->id]);
            echo CTHtml::aBtn('@update', ['update', 'id' => $model->id]);
        }
        ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'name',
                'captionOptions' => ['style' => ['width' => '200px']],
            ],
            [
                'attribute' => 'role_id',
                'format' => 'raw',
                'value' => static function (Users $model) {
                    return $model->role->title;
                }
            ],
            [
                'label' => 'Контроллер',
                'format' => 'raw',
                'value' => static function (Users $model) {
                    return Html::a($model->homeDomain->url, $model->homeDomain->url);
                }
            ],
        ],
    ]) ?>

    <p>
        <?= CTHtml::aBtn('@add', ['add-domains', 'id' => $model->id]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class,
                'contentOptions' => ['style' => 'width: 30px; text-align: center;'],
            ],

            'title',
            'url:html',

            ['class' => ActionColumn::class,
                'contentOptions' => ['style' => 'width: 120px; text-align: center;'],
                'template' => '{view} {update} {delete} {remove}',
                'buttons' => [
                    'view' => static function ($url, Domains $domains, $key) {
                        $uri = ['domains/view', 'id' => $domains->id, '_node' => 0];
                        return CTHtml::aBtnGV('@view', $uri);
                    },
                    'update' => static function ($url, Domains $domains, $key) {
                        $uri = ['domains/update', 'id' => $domains->id, '_node' => 0];
                        return CTHtml::aBtnGV('@update', $uri);
                    },
                    'delete' => static function ($url, Domains $domains, $key) {
                        $uri = ['domains/delete', 'id' => $domains->id, '_node' => 0];
                        return CTHtml::aBtnGV('@delete', $uri);
                    },
                    'remove' => static function ($url, Domains $domains, $key) use ($model) {
                        $uri = ['delete-domain', 'uid' => $model->id, 'id' => $domains->id];
                        return CTHtml::aBtnGV('@remove', $uri);
                    },
                ],
            ],
        ],
    ]) ?>

</div>
