<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11/14/13
 * Time: 11:55 AM
 */

namespace Orangehill\Photon\Library\Support;


class Breadcrumb
{
    protected $title;
    protected $url;

    public function __construct($title = null, $url = null)
    {
        $this->title = $title;
        $this->url   = $url;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }


} 