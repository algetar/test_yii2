<?php
declare(strict_types=1);

namespace app\modules\system\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_options".
 *
 * @property int $user_id Пользователь
 * @property string $key Ключ
 * @property string $value Значение
 *
 * @property Users $user
 */
class TblUserOptions extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user_options';
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
            [['user_id', 'key', 'value'], 'required'],
            [['user_id'], 'integer'],
            [['key', 'value'], 'string', 'max' => 255],
            [['user_id', 'key'], 'unique', 'targetAttribute' => ['user_id', 'key']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'user_id' => 'Пользователь',
            'key'     => 'Ключ',
            'value'   => 'Значение',
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
}
