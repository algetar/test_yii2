<?php
declare(strict_types=1);

namespace app\modules\system\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users_domains".
 *
 * @property int $user_id Ид пользователя
 * @property int $domain_id Ид модуля
 *
 * @property Users $user
 * @property Domains $domain
 */
class TblUsersDomains extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users_domains';
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
            [['user_id', 'domain_id'], 'required'],
            [['user_id', 'domain_id'], 'integer'],
            [['user_id', 'domain_id'], 'unique', 'targetAttribute' => ['user_id', 'domain_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
            [['domain_id'], 'exist', 'skipOnError' => true, 'targetClass' => Domains::class, 'targetAttribute' => ['domain_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'user_id' => 'Ид пользователя',
            'domain_id' => 'Ид модуля',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
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
}
