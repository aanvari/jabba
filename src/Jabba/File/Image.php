<?php namespace Jabba\File;

use Jabba\File;

class Image extends File
{
    public function __construct($path)
    {
        parent::__construct($path);
        $size = getImageSize($this->getPath());
        $this->setWidth($size[0])
            ->setHeight($size[1]);
    }

    /**
     * Sets image width
     *
     * @param integer $val
     *
     * @return \Jabba\File\Image
     */
    public function setWidth($val)
    {
        $this->_data['width'] = $val;

        return $this;
    }

    /**
     * Sets image height
     *
     * @param integer $val
     *
     * @return \Jabba\File\Image
     */
    public function setHeight($val)
    {
        $this->_data['height'] = $val;

        return $this;
    }

    /**
     * Returns image width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->_data['width'];
    }

    /**
     * Returns image height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->_data['height'];
    }

    /**
     * Resizes the image and saves it to the requested path
     * 
     * @param integer $width
     * @param integer $height
     * @param string $path
     * 
     * @return \Jabba\File\Image
     */
    public function resize($width, $height, $path = false)
    {
        $resize = new Image\Resize($this);
	            
        return $resize->handle($width, $height, $path);
    }
    
    /**
     * Crops the image and saves it to the same path
     * 
     * @param integer $x
     * @param integer $y
     * @param integer $width
     * @param integer $height
	 * @param string $path destination
     * 
     * @return \Jabba\File\Image
     */
	public function crop($x, $y, $width, $height, $path)
    {
        $crop = new Image\Crop($this);
        
        return $crop->handle($x, $y, $width, $height, $path);
    }

	/**
	 * Cuts out a portion from the image and saves the result to the given path
	 *
	 * @param integer $x
	 * @param integer $y
	 * @param integer $width
	 * @param integer $height
	 * @param string $path destination
	 *
	 * @return \Jabba\File\Image
	 */
	public function cut($x, $y, $width, $height, $path = false)
	{
		$cut = new Image\Cut($this);

		return $cut->handle($x, $y, $width, $height, $path);
	}

	/**
	 * Compresses the image and saves it to the requested path
	 *	 
	 * @param string $path Destination to write compressed image to
	 *
	 * @return \Jabba\File\Image
	 */
	public function compress($path = false)
	{
		$compress = new Image\Compress($this);
		
		return $compress->handle($path);
	}

    /**
     * Returns the path using a given file name
     *
     * @param  string  $base
     * @param  string  $name
     * @param  integer $length
     * @param  boolean $create (if true creates the necessary path directories)
     * 
     * @return string
     */
    public static function getPathByName($base, $name, $length = 3, $create = false)
    {
        $directories = str_split($name, $length);
        array_pop($directories);

        foreach ($directories as $i => $dir) {
            $path =  $base . implode(DIRECTORY_SEPARATOR, array_slice($directories, 0, $i + 1));
            if ($create && !is_dir($path)) {
                if (!mkdir($path, 0777, true)) {
                    throw new Exception('Failed to create cache directory in ' . $path);
                }
            }
        }

        return $path . DIRECTORY_SEPARATOR;
    }
}
