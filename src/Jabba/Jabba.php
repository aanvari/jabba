<?php namespace Jabba;

class Jabba
{
    private $imageDirectory;

    private static $instance;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setImageDirectory($val)
    {
        $this->imageDirectory = $val;

        return $this;
    }

    public function getImageDirectory()
    {
        return $this->imageDirectory;
    }
}