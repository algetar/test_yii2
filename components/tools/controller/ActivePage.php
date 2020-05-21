<?php
declare(strict_types=1);

namespace app\components\tools\controller;

use app\components\tools\urls\CTUrl;
use app\modules\system\models\Domains;
use app\modules\system\models\Sites;
use yii\base\BaseObject;
use yii\web\NotFoundHttpException;

/**
 * Class ActivePage
 * Текущее действие контроллера
 *
 * @property string $title
 * @property int $status
 * @property-read string route
 * @property-read bool isDomain
 * @property-read bool isSite
 * @property-read array $node
 */
class ActivePage extends BaseObject
{
    /* модуль */
    public ?Domains $domain;

    /* контроллер */
    public ?Sites $site;

    /* урл действия */
    public CTUrl $url;

    /**
     * ActivePage constructor.
     * @param CTUrl $url
     *
     * @throws NotFoundHttpException
     */
    public function __construct(CTUrl $url)
    {
        parent::__construct();

        $this->domain = Domains::getDomain($url->domain, false);
        if ($this->domain->route === $url->route) {
            $url->title = $this->domain->title;
        }

         $this->site = (null === $this->domain) ? null : $this->domain->getSite($url->site, false);
        if ($this->site->route === $url->route) {
            $url->title = $this->site->title;
        }

        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->url->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->url->title = $title;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->url->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->url->status = $status;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getNode(string $name = 'page'): array
    {
        if ($name === 'domain') {
            return (null === $this->domain) ? [] : $this->domain->node;
        }

        if ($name === 'site') {
            return (null === $this->site) ? [] : $this->site->node;
        }

        return $this->url->node;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->url->route;
    }

    /**
     * @return bool
     */
    public function getIsDomain(): bool
    {
        if (null !== $this->domain) {
            return $this->domain->route === $this->url->route;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function getIsSite(): bool
    {
        if (null !== $this->site) {
            return $this->site->route === $this->url->route;
        }

        return false;
    }
}
