<?php namespace Jabba\File\Image;

class Cut
{   
    protected $_data;
    
    public function __construct($image)
    {
        $this->setImage($image);
    }
    
    /**
     * Sets the image object for client
     * 
     * @param \Jabba\File\Image $val
     * 
     * @return \Jabba\File\Image\Cut
     */
    public function setImage($val)
    {
        $this->_data['image'] = $val;
        
        return $this;
    }
    
    /**
     * Returns the image to be re-sized
     * 
     * @return \Jabba\File\Image
     */
    public function getImage()
    {
        return $this->_data['image'];
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
     * @return string generated name for the cut image
     * @throws \Exception
     */
    public function handle($x, $y, $width, $height, $path)
    {
		if (!in_array($this->getImage()->getExtension(), array('jpg', 'jpeg', 'png', 'gif'))) {
			throw new \Exception('Image format is not supported.');
		}

		$image = $this->getImage();
		$src = $image->getPath();

		if (!$path || $path == $image->getDirectory()->getPath()) {
			$dest = $src;
		} else {
			$uniqueId = \Jabba\File::getRandomString();
			$dest = \Jabba\File\Image::getPathByName($path, $uniqueId, 3, true) . $uniqueId . '.' . $image->getExtension();
		}
		
		$this->_handle($src, $dest, $x, $y, $width, $height);

		return new \Jabba\File\Image($dest);
    }
    
    /**
     * Cuts out a portion from the image and saves the result to the given path
     * 
     * @param string $src
     * @param string $dest
     * @param integer $x
     * @param integer $y
     * @param integer $width
     * @param integer $height
     * 
     * @return string Generated name for the cut image
     * @throws \Exception
     */
    protected function _handle($src, $dest, $x, $y, $width, $height)
    {   
        switch ($this->getImage()->getExtension()) {
            case 'jpg':
            case 'jpeg':
                $this->_handleJpeg($src, $dest, $x, $y, $width, $height);
                break;
            case 'gif':
                $this->_handleGif($src, $dest, $x, $y, $width, $height);
                break;
            case 'png':
                $this->_handlePng($src, $dest, $x, $y, $width, $height);
                break;
        }
    }
    
    /**
     * Handles cutting images of type "jpg"
     * 
     * @param string $src
     * @param string $dest
     * @param integer $x
     * @param integer $y
     * @param integer $width
     * @param integer $height
     * 
     * @return \Jabba\File\Image\Resize
     */
    protected function _handleJpeg($src, $dest, $x, $y, $width, $height)
    {
        // to be implemented
        
        return $this;
    }
    
    /**
     * Handles cutting and saving images of type "gif"
     * 
     * @param string $src
     * @param string $dest
     * @param integer $x
     * @param integer $y
     * @param integer $width
     * @param integer $height
     * 
     * @return \Jabba\File\Image\Resize
     */
    protected function _handleGif($src, $dest, $x, $y, $width, $height)
    {
        // to be implemented
        
        return $this;
    }
    
    /**
     * Handles cutting and saving images of type "png"
     * 
     * @param string $src
     * @param string $dest
     * @param integer $x
     * @param integer $y
     * @param integer $width
     * @param integer $height
     * 
     * @return \Jabba\File\Image\Resize
     */
    protected function _handlePng($src, $dest, $x, $y, $width, $height)
    {
		$image1 = imagecreatefrompng($src);
		imagealphablending($image1, false);
		imagesavealpha($image1, true);

		$transparent = imagecolorallocatealpha($image1, 0, 0, 0, 127);
		imagefilledrectangle($image1, $x, $y, $x + $width, $y + $height, $transparent);

        imagepng($image1, $dest, 9);
        
        return $this;
    }
}