<?php
declare(strict_types=1);

namespace app\components\tools\helpers;

use app\modules\system\models\UserOptions;
use Yii;
use yii\web\IdentityInterface;
use app\modules\system\models\Users;

/**
 * Class CTUser
 * Текущий пользователь
 */
class CTUser
{
    /**
     * Текущий пользователь
     * @return Users|IdentityInterface
     */
    public static function user()
    {
        return Yii::$app->user->identity;
    }

    /**
     * @return array|string
     */
    public static function home()
    {
        return self::user()->home;
    }

    /**
     * @param string $owner
     * @param string $name
     * @return UserOptions
     */
    public static function option(string $owner, string $name): UserOptions
    {
        return self::user()->getOption($owner, $name);
    }
}