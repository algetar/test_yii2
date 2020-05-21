<?php
declare(strict_types=1);

namespace app\modules\system\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "roles".
 *
 * @property int $id Ид записи
 * @property string $title Имя
 * @property int $domain_id Модуль роли
 *
 * @property Domains $domain
 * @property Users[] $users
 */
class TblRoles extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'roles';
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
            [['title', 'domain_id'], 'required'],
            [['domain_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['domain_id'], 'exist', 'skipOnError' => true, 'targetClass' => Domains::class, 'targetAttribute' => ['domain_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'Ид роли',
            'title' => 'Наименование',
            'domain_id' => 'Модуль',
        ];
    }

    /**
     * Gets query for [[Domain]].
     *
     * @return ActiveQuery
     */
    public function getDomain(): ActiveQuery
    {
        return $this->hasOne(Domains::class, ['id' => 'domain_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery
     */
    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(Users::class, ['role_id' => 'id']);
    }
}
