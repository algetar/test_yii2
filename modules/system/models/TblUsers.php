<?php
declare(strict_types=1);

namespace app\modules\system\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users".
 *
 * @property int $id Ид записи
 * @property string $name Логин
 * @property string $password Пароль
 * @property int $role_id Роль пользователя
 *
 * @property Domains[] $domains
 * @property Roles $role
 * @property UsersDomains[] $usersDomains
 * @property UserOptions[] $userOptions
 */
class TblUsers extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * @return object
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('dbsu');
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'password', 'role_id'], 'required'],
            [['role_id'], 'integer'],
            [['name', 'password'], 'string', 'max' => 255],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'       => 'Ид записи',
            'name'     => 'Логин',
            'password' => 'Пароль',
            'role_id'  => 'Роль',
        ];
    }

    /**
     * Gets query for [[Role]].
     *
     * @return ActiveQuery
     */
    public function getRole(): ActiveQuery
    {
        return $this->hasOne(Roles::class, ['id' => 'role_id']);
    }

    /**
     * Gets query for [[UsersDomains]].
     *
     * @return ActiveQuery
     */
    public function getUsersDomains(): ActiveQuery
    {
        return $this->hasMany(UsersDomains::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Domains]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getDomains(): ActiveQuery
    {
        return $this->hasMany(Domains::class, ['id' => 'domain_id'])->viaTable('users_domains', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserOptions]].
     *
     * @return ActiveQuery
     */
    public function getUserOptions(): ActiveQuery
    {
        return $this->hasMany(UserOptions::class, ['user_id' => 'id']);
    }
}
