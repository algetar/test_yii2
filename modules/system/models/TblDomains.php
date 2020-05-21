<?php
declare(strict_types=1);

namespace app\modules\system\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "domains".
 *
 * @property int $id Ид записи
 * @property string $title Наименование
 * @property string $cid Модуль
 * @property string $native_cid Контроллер
 * @property string $index Действие
 *
 * @property Roles[] $roles
 * @property Sites[] $sites
 * @property UsersDomains[] $usersDomains
 * @property Users[] $users
 */
class TblDomains extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'domains';
    }

    /**
     * @return Connection|Object the database connection used by this AR class.
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
            [['cid', 'title', 'native_cid', 'index'], 'required'],
            [['cid'], 'string', 'max' => 20],
            [['title', 'native_cid', 'index'], 'string', 'max' => 255],
            [['cid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'Ид записи',
            'title'      => 'Наименование',
            'cid'        => 'Модуль',
            'native_cid' => 'Контроллер',
            'index'      => 'Действие',
        ];
    }

    /**
     * Gets query for [[Roles]].
     *
     * @return ActiveQuery
     */
    public function getRoles(): ActiveQuery
    {
        return $this->hasMany(Roles::class, ['domain_id' => 'id']);
    }

    /**
     * Gets query for [[Sites]].
     *
     * @return ActiveQuery
     */
    public function getSites(): ActiveQuery
    {
        return $this->hasMany(Sites::class, ['domain_id' => 'id']);
    }

    /**
     * Gets query for [[UsersDomains]].
     *
     * @return ActiveQuery
     */
    public function getUsersDomains(): ActiveQuery
    {
        return $this->hasMany(UsersDomains::class, ['domain_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(Users::class, ['id' => 'user_id'])->viaTable('users_domains', ['domain_id' => 'id']);
    }
}
