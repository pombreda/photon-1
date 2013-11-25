<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11/20/13
 * Time: 2:36 PM
 */

namespace Orangehill\Photon\Library\Creator\Stubs;


abstract class Stub
{
    protected $args = array();
    protected $content = '';

    public function __construct(array $args = array())
    {
        $this->args = $args;
        $this->initContent();
    }

    protected function initContent()
    {
        $this->content = '';
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param array $args
     */
    public function setArgs($args)
    {
        $this->args = $args;

        return $this;
    }

    public function get()
    {
        return $this->getContent();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function append($string)
    {
        $this->content .= $string;

        return $this;
    }

    public function prepend($string)
    {
        $this->content = $string . $this->content;

        return $this;
    }


}