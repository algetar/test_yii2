<?php
declare(strict_types=1);

namespace app\modules\system;

use yii\base\Module;

/**
 * system module definition class
 */
class System extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\system\controllers';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();
    }
}
