<?php
declare(strict_types=1);

namespace app\modules\system\models;

/**
 * Опции пользователя
 * Class UserOptions
  */
class UserOptions extends TblUserOptions
{
    /**
     * Сохраняет значение объектом.
     *  Перед сохранение6м значение преобразуется функцией serialize.
     *  null не преобразуется, а сохраняется в виде пустого значения.
     * @param mixed $value
     */
    public function set($value): void
    {
        $this->value = (null === $value) ? '' : serialize($value);
        $this->save();
    }

    /**
     * Читает ранее записанное значение объектом {@see set}.
     *  Если ключ отсутствует и $default не null, то создается запись с дефолтным значением.
     * @param null $default значение по умолчанию
     *
     * @return mixed
     */
    public function get($default = null)
    {
        if ($this->isNewRecord) {
            if (null === $default) {
                return $default;
            }

            $this->set($default);
        }

        return ($this->value === '') ? null : unserialize($this->value, false);
    }
}
