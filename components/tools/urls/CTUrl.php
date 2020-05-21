<?php
declare(strict_types=1);

namespace app\components\tools\urls;

use yii\base\BaseObject;

/**
 * Class CTUrl
 * Расширяющий функционал Yii::Url.
 * DTO между {@see KnotControllerTrait}, {@see CrumbsNodes}, {@see CTHtml}
 *
 * @property array|string $url
 * @property string $route
 * @property array $node
 */
class CTUrl extends BaseObject
{
    /* модуль */
    public string $domain;

    /* контроллер */
    public string $site;

    /* имя действия */
    public string $page;

    /* наименование действия */
    public string $title;

    /* параметры действия */
    public array $params = [];

    /* статус действия в контексте KnotControllerTrait */
    public int $status = 0;

    /** @var bool|string|null шаблон CTHml */
    public $template = false;

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return FUrl::createRoute($this->domain, $this->site, $this->page);
    }

    /**
     * @return array|string
     */
    public function getUrl()
    {
        $url = $this->route;
        if ([] !== $this->params) {
            $url = array_merge([$url], $this->params);
        }

        return $url;
    }

    /**
     * @return array
     */
    public function getNode(): array
    {
        return [
            'domain'   => $this->domain,
            'site'     => $this->site,
            'page'     => $this->page,
            'title'    => $this->title,
            'params'   => $this->params,
            'status'   => $this->status,
        ];
    }
}
