<?php
declare(strict_types=1);

namespace app\modules\system\models;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class Roles
 */
class Roles extends TblRoles
{
    //Роли участников/пользователей
    public const ROLE_SA            = 1;
    public const ROLE_ADMINISTRATOR = 2;
    public const ROLE_CUSTOMER      = 3;

    /**
     * Список ролей для dropDownList
     * @return array
     */
    public static function rolesList(): array
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'title');
    }

    /**
     * @param null $id
     * @return Roles
     * @throws NotFoundHttpException
     */
    public static function prepare($id = null): Roles
    {
        if (null === $id) {
            $model = new self();
        } else {
            $model = self::findOne($id);
        }

        if ($model === null) {
            throw new NotFoundHttpException(sprintf('The requested Role(%s) does not exist.', $id));
        }

        return $model;
    }
}
