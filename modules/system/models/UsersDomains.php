<?php
declare(strict_types=1);

namespace app\modules\system\models;

use yii\web\NotFoundHttpException;

/**
 * Class UsersDomains
 */
class UsersDomains extends TblUsersDomains
{
    /**
     * @param $uid
     * @param null $did
     *
     * @return UsersDomains
     * @throws NotFoundHttpException
     */
    public static function getUserDomain($uid, $did = null): UsersDomains
    {
        $userDomain = null;
        if (null === $did) {
            $userDomain = new static();
            $userDomain->user_id = $uid;
        } else {
            $userDomain = static::findOne([$uid, $did]);
        }

        if ($userDomain !== null) {
            return $userDomain;
        }

        throw new NotFoundHttpException(sprintf('The requested User.%s Domain.%s does not exist.', $uid, $did));
    }
}