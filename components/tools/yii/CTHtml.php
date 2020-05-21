<?php
declare(strict_types = 1);

namespace app\components\tools\yii;

use app\modules\system\models\Domains;
use app\modules\system\models\Users;
use app\modules\system\models\Modules;
use yii\base\InvalidConfigException;
use yii\bootstrap\Html;
use yii\web\NotFoundHttpException;

/**
 * Утилиты расширяющие \yii\bootstrap\Html
 *
 * @author tga
 */
class CTHtml extends Html
{
    private static array $_templates = [
        'create' => [
            'title' => 'Создать',
            'glyph' => 'plus',
            'btn'   => 'success',
        ],
        'add' => [
            'title' => 'Добавить',
            'glyph' => 'plus',
            'btn'   => 'success',
        ],
        'save' => [
            'title' => 'Сохранить',
            'glyph' => 'ok',
            'btn'   => 'success',
        ],
        'view' => [
            'title' => 'Просмотр',
            'glyph' => 'eye-open',
            'btn'   => 'primary',
        ],
        'update' => [
            'title' => 'Изменить',
            'glyph' => 'pencil',
            'btn'   => 'primary',
        ],
        'delete' => [
            'title' => 'Удалить',
            'glyph' => 'trash',
            'btn'   => 'danger',
            'data' => [
                'confirm' => 'Эта запись будет удалена, продолжить?',
                'method' => 'post',
            ],
        ],
        'remove' => [
            'title' => 'Удалить',
            'glyph' => 'minus',
            'btn'   => 'danger',
        ],
        'back' => [
            'title' => 'Вернуться',
            'glyph' => 'arrow-left',
            'btn'   => 'info',
        ],
        'next' => [
            'title' => 'Далее',
            'glyph' => 'arrow-right',
            'btn'   => 'info',
        ],
    ];

    /**
     * HTML представление булева значения
     * @param bool $value
     * @return string
     */
    public static function bool(bool $value): string
    {
        return  $value ?
            '<span class="glyphicon glyphicon-ok" style="color: green;">Да</span>' :
            '<span class="glyphicon glyphicon-remove" style="color: red;">Нет</span>';
    }

    /**
     * Тег a в виде кнопки с использование шаблонизатора {@see templating}
     * @param string $title
     * @param null $url
     * @param array $options
     *
     * @return string
     */
    public static function aBtn(string $title, $url = null, array $options = []): string
    {
        $options = self::templating($title, $options);

        return static::a($title, $url, $options);
    }

    /**
     * Тоже что {@see aBtn}, но без текста для использования с GridView
     * @param string $action
     * @param null $url
     * @param array $options
     * @return string
     */
    public static function aBtnGV(string $action, $url = null, array $options = []): string
    {
        if (strpos($action, '@') === 0 && substr($action, -1) !== ':') {
            $action .= ':';
        }
        $options = self::templating($action, $options, false);
        return static::a($action, $url, $options);
    }

    /**
     * Тег submitButton в виде кнопки с использование шаблонизатора {@see templating}
     * @param string $title
     * @param array $options
     * @return string
     */
    public static function submitBtn(string $title, array $options = []): string
    {
        $options = self::templating($title, $options);

        return static::tag('div', static::submitButton($title, $options), [
            'class' => 'col-sm-6 col-sm-offset-3',
            'style' => ['text-align' => 'right'],
        ]);
    }

    /**
     * @param $content
     * @param $options
     * @return string
     */
    public static function div($content, $options): string
    {
        return static::tag('div', $content, $options);
    }

    /**
     * @param string $content
     * @param array  $options
     *
     * @return string
     */
    public static function td(string $content, array $options = []): string
    {
        return static::tag('td', $content, $options);
    }
    
    /**
     * @param string $content
     * @param array  $options
     *
     * @return string
     */
    public static function tr(string $content, array $options = []): string
    {
        return static::tag('tr', $content, $options);
    }
    
    /**
     * @param string $content
     * @param array  $options
     *
     * @return string
     */
    public static function table(string $content, array $options = []): string
    {
        return static::tag('table', $content, $options);
    }
    

    /**
     * Заголовок бокового меню
     * @param string $title
     * @param array  $options
     *
     * @return string
     */
    public static function menuTitle(string $title, array $options = []): string
    {
        $spanOptions = [];
        if (array_key_exists('span', $options)) {
            $spanOptions = $options['span'];
            unset($options['span']);
        }
        return static::tag('p', static::tag('span', $title, $spanOptions), $options);
    }

    /**
     * Боковое меню Текущего пользователя
     *
     * @param Users $user
     *
     * @return string
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public static function userMenu(Users $user):string
    {
        return static::menuTitle('Приложения') .
               static::linkedModules($user) .
               static::menuTitle($user->getDomain()->title) .
               static::menuSites($user->getDomain());
    }

    /**
     * Приложения текущего пользователя
     * @param Users $user
     *
     * @return string
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    private static function linkedModules(Users $user): string
    {
        $content = '';
        foreach ($user->domains() as $domain) {
            if ($domain === $user->getDomain()) {
                $content .= static::a($domain->title, '#', ['class' => 'list-group-item active']);
            } else {
                $content .= static::a($domain->title, $domain->url, ['class' => 'list-group-item']);
            }
        }

        return static::tag('div', $content, ['class' => 'list-group']);
    }

    /**
     * Процессы текущего приложения
     * @param Domains $domain

     *
     * @return string
     */
    private static function menuSites(Domains $domain): string
    {
        $content = '';
        if (!empty($domain->sites)) {
            foreach ($domain->sites() as $site) {
                if (!$site->is_root) {
                    continue;
                }
                if ($domain->site === $site) {
                    $content .= static::a($site->title, '#', ['class' => 'list-group-item active']);
                } else {
                    $content .= static::a($site->title, $site->url, ['class' => 'list-group-item']);
                }
            }
        }
        
        return static::tag('div', $content, ['class' => 'list-group']);
    }

    /**
     * Некоторые элементы в опциях могут содержать вспомогательные аттрибуты
     * - 'glyph' => <text> - добавляет к тексту элемента bootstrap glyphicon
     *  <span class="glyphicon glyphicon-<text>"></span>.$content
     * - 'btn' => <text> добавляет класс 'btn btn-<text>'
     * @param string $content
     * @param array $options
     * @param bool|null $isButton
     *
     * @return array
     */
    private static function templating(string &$content, array $options, ?bool $isButton = true): array
    {
        $options = self::templatingTitle($content, $options);

        if (array_key_exists('glyph', $options)) {
            $content = sprintf('<span class="glyphicon glyphicon-%s"></span> %s', $options['glyph'], $content);
            unset($options['glyph']);
        }

        if (array_key_exists('btn', $options)) {
            if ($isButton) {
                $class = sprintf('btn btn-%s', $options['btn']);
                if (array_key_exists('class', $options)) {
                    $options['class'] .= ' ' . $class;
                } else {
                    $options['class'] = $class;
                }
            }

            unset($options['btn']);
        }

        return $options;
    }

    /**
     * Текст некоторых элементов может соде6ржать шаблон в виде:
     * @<template>:text, где
     * <template> - имя из self::$_templates
     * если отсутствует двоеточие ':', то text берется из шаблона,
     * если оканчивается на двоеточие ':', то text = ''
     * @param $content
     * @param $options
     * @return array
     */
    private static function templatingTitle(&$content, $options): array
    {
        if ($content === '') {
            return $options;
        }

        if ($content[0] !== '@') {
            return $options;
        }

        [$key, $title] = strpos($content, ':') !== false ?
            explode(':', substr($content, 1), 2) :
            [substr($content, 1), null];

        if (!array_key_exists($key, self::$_templates)) {
            return $options;
        }

        $template = self::$_templates[$key];

        $title = $title ?? $template['title'];
        unset($template['title']);

        $options = array_merge($options, $template);
        $content = $title;
        return $options;
    }
}
