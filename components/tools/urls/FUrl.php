<?php
declare(strict_types=1);

namespace app\components\tools\urls;

use app\modules\system\models\Domains;
use app\modules\system\models\Sites;
use Yii;

/**
 * Class CTUrlHelper
 */
class FUrl
{
    /**
     * @param string $domain
     * @param string $site
     * @param string $page
     *
     * @return string
     */
    public static function createRoute(string $domain, string $site, string $page): string
    {
        return sprintf('/%s/%s/%s', $domain, $site, $page);
    }

    /**
     * Создает экземпляр класса CTUrl
     * @param $url
     * @param string|null $title
     * @param bool|string $template
     *
     * @return CTUrl
     */
    public static function url($url = null, ?string $title = null, $template = false): CTUrl
    {
        if (null === $url) {
            $instance = self::fromNode([]);
        } elseif ($url instanceof Domains || $url instanceof Sites) {
            $instance = self::fromNode($url->node);
            $title = $title ?? $url->title;
        } else {
            $instance = self::fromNode(self::parseUrl($url));
        }

        if (null !== $title) {
            $instance->title = $title;
        }

        if (null !== $title) {
            $instance->template = $template;
        }

        return $instance;
    }

    /**
     * @param array $node
     *
     * @return CTUrl
     */
    public static function fromNode(array $node = []): CTUrl
    {
        $node['domain']   = $node['domain'] ?? Yii::$app->controller->module->id;
        $node['site']     = $node['site'] ?? Yii::$app->controller->id;
        $node['page']     = $node['page'] ?? Yii::$app->controller->action->id;
        $node['params']   = $node['params'] ?? Yii::$app->request->get();
        $node['title']    = $node['title'] ?? $node['page'];

        return new CTUrl($node);
    }

    /**
     * Трансформирует $url в $node
     * @param array|string $url Yii url
     *
     * @return array
     *  [
     *      'domain' => string,
     *      'site'   => string,
     *      'page'   => string,
     *      'params' => array
     *  ]
     */
    public static function parseUrl($url): array
    {
        $node = [];
        if (is_array($url)) {
            $route = $url[0];
            unset($url[0]);
            $node['params'] = $url;
        } else {
            $route = $url;
        }

        $route = trim($route, '/');
        self::parseRoute($route, 0, $node);

        return $node;
    }

    /**
     * Раскладывает route часть url'а в массив ['page', 'site', 'domain']
     * @param string $route
     * @param int $step
     * @param $node
     */
    private static function parseRoute(string $route, int $step, &$node): void
    {
        static $items = ['page', 'site', 'domain'];

        if (false === ($pos = strrpos($route, '/'))) {
            $node[$items[$step]] = $route;
            return;
        }

        if ($step === 2) {
            $node[$items[$step]] = $route;
            return;
        }

        $node[$items[$step]] = substr($route, $pos + 1);
        self::parseRoute(substr($route, 0, $pos), $step + 1, $node);
    }
}
