<?php
declare(strict_types=1);

namespace app\modules\customer\controllers;

use Yii;
use yii\web\Controller;
use app\modules\customer\models\Menu;

/**
 * Default controller for the `customer` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $model = new Menu();

        if ($model->selector->load(Yii::$app->request->post())) {
            $model->findDishes();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
