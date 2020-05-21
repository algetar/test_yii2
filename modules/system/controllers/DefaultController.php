<?php
declare(strict_types = 1);

namespace app\modules\system\controllers;

use yii\web\Controller;

/**
 * Default controller for the `system` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
