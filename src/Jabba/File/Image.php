<?php namespace Jabba\File;

use Jabba\File;

class Image extends File
{		
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
     * 
     * @return \Jabba\File\Image
     */
	public function crop($x, $y, $width, $height)
    {
        $crop = new Image\Crop($this);
        
        return $crop->handle($x, $y, $width, $height);
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
     * @param  string  $name
     * @param  integer $length
     * @param  boolean $create (if true creates the necessary path directories)
     * 
     * @return string
     */
    public static function getPathByName($name, $length = 3, $create = false)
    {
        $directories = str_split($name, $length);
        array_pop($directories);

        $base = \Jabba\Jabba::getInstance()->getImageDirectory();

        foreach ($directories as $i => $dir) {
            $path =  implode(DIRECTORY_SEPARATOR, array_slice($directories, 0, $i + 1));
            if ($create && !is_dir($base . $path)) {
                if (!mkdir($base . $path, 0777, true)) {
                    throw new Exception('Failed to create cache directory in ' . $path);
                }
            }
        }

        return $path . DIRECTORY_SEPARATOR;
    }
}
