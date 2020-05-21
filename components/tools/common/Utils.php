<?php
declare(strict_types=1);

namespace app\components\tools\common;

use Yii;
use yii\base\Module;
use yii\db\Query;
use yii\helpers\FileHelper;

/**
 * Class Utils
 */
class Utils
{

    /**
     * @param Module $module
     * @return array
     */
    public static function getModuleControllersFiles(Module $module): array
    {
        $path = Yii::getAlias('@' . str_replace('\\', '/', $module->controllerNamespace));

        return FileHelper::findFiles($path, [
            'only' => [
                '*Controller.php'
            ]
        ]);
    }

    /**
     * Список имен контроллеров, лежащих в $module->controllerNamespace
     * @param Module $module
     * @return array
     */
    public static function getModuleControllersList(Module $module): array
    {
        $result = [];
        foreach (self::getModuleControllersFiles($module) as $file) {
            $result[] = explode('Controller', basename($file))[0] ;
        }

        return $result;
    }

    /**
     * Добавляет имя атрибута в правило модели rules().
     * вид правила: [ 0 => [список атрибутов], ..., правило, ...],
     * например правило $ruleNames = ['string', 'max' => 255]: [['name', 'password'], 'string', 'max' => 255]
     * @param array $rules
     * @param string|array $ruleNames
     * @param string $attribute
     *
     * @return bool
     */
    public static function addRuleAttribute(array &$rules, $ruleNames, string $attribute): bool
    {
        foreach ($rules as $row => $rule) {
            if (self::inArray($ruleNames, $rule, true)) {
                if (is_string($rules[$row][0])) {
                    $rules[$row][0] = [$rules[$row][0]];
                }
                $rules[$row][0][] = $attribute;

                return true;
            }
        }

        return false;
    }

    /**
     * Если $needle массив, то требует наличие всех элементов $needle
     * @param array|string $needles
     * @param array $haystack
     * @param bool $strict
     * @return bool
     */
    public static function inArray($needles, array $haystack, bool $strict): bool
    {
        if (is_array($needles)) {
            $result = true;
            foreach ($needles as $needle) {
                $result = $result && in_array($needle, $haystack, $strict);
            }
        } else {
            $result = in_array($needles, $haystack, $strict);
        }

        return $result;
    }

    /**
     * Показывает SQL запрос
     * @param Query $query
     * @param int $mode режим отображения запроса
     * @return string
     */
    public static function getSQL(Query $query, $mode = 1): string
    {
        if (1 === $mode) {
            return $query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql;
        }

        return $query->prepare(Yii::$app->db->queryBuilder)->createCommand()->sql;
    }
}
