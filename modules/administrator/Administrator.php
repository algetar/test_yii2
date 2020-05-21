<?php
declare(strict_types=1);

namespace app\modules\administrator;

use yii\base\Module;

/**
 * administrator module definition class
 */
class Administrator extends Module
{
    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();
        $this->controllerNamespace = 'app\modules\administrator\controllers';
    }
}
