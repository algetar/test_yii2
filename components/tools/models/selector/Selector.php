<?php
declare(strict_types=1);


namespace app\components\tools\models\selector;

use yii\base\Model;

/**
 * Class Selector
 */
class Selector extends Model
{
    /* список id выбранных записей */
    public string $choiceIds = '';

    /* ограничение количества выбранных записей */
    public int $choiceLimit = 0;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['choiceIds'], 'required'],
            [['choiceIds'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'choiceIds'   => 'Выбранные элементы',
            'choiceLimit' => 'Ограничение выбранных элементов',
        ];
    }
}
