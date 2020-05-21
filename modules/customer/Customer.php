<?php
declare(strict_types=1);

namespace app\modules\customer;

use yii\base\Module;

/**
 * customer module definition class
 */
class Customer extends Module
{
    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();
        $this->controllerNamespace = 'app\modules\customer\controllers';
    }
}
