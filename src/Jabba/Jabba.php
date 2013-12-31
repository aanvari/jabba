<?php namespace Jabba;

class Jabba
{
    private $path;

    private static $instance;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setPath($val)
    {
        $this->path = $val;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }
}