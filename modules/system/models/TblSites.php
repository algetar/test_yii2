<?php
declare(strict_types=1);

namespace app\modules\system\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sites".
 *
 * @property int $id Ид записи
 * @property int $domain_id Модуль
 * @property string $cid Контроллер
 * @property string $index Действие
 * @property string $title Наименование
 * @property int $is_root Корневой процесс
 *
 * @property Domains $domain
 */
class TblSites extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'sites';
    }

    /**
     * @return object
     * @throws InvalidConfigException
     */
    public static function getDb(): object
    {
        return Yii::$app->get('dbsu');
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['domain_id', 'cid', 'title'], 'required'],
            [['domain_id', 'is_root'], 'integer'],
            [['cid'], 'string', 'max' => 20],
            [['index', 'title'], 'string', 'max' => 255],
            [['domain_id'], 'exist', 'skipOnError' => true, 'targetClass' => Domains::class, 'targetAttribute' => ['domain_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'        => 'Ид записи',
            'domain_id' => 'Модуль',
            'cid'       => 'Контроллер',
            'index'     => 'Действие',
            'title'     => 'Наименование',
            'is_root'   => 'Корневой процесс',
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
}
