<?php namespace Jabba\File\Image;

class Resize
{
	protected $_data;
    
    public function __construct($image)
    {
        $this->setImage($image);
    }
    
    /**
     * Sets the image opbject for client
     * 
     * @param \Jabba\File\Image $val
     * 
     * @return \Jabba\File\Image\Resize
     */
    public function setImage($val)
    {
        $this->_data['image'] = $val;
        
        return $this;
    }
    
    /**
     * Returns the image to be resized
     * 
     * @return \Jabba\File\Image
     */
    public function getImage()
    {
        return $this->_data['image'];
    }
    
    /**
     * Resizes and saves the new image to the requested path
     * 
     * @param integer $maxWidth
     * @param integer $maxHeight
     * @param string $path
     * 
     * @return \Jabba\File\Image\Resize
     */
    public function handle($maxWidth, $maxHeight, $path = false)
    {
        $image = $this->getImage();
        $src = $image->getPath();        
        
        if (!$path || $path == $image->getDirectory()->getPath()) {
            $dest = $src;            
        } else {
            $dest = $path . $image->getName() . '.' . $image->getExtension();
        }
        
        // Get new dimensions
        list($width, $height) = getimagesize($src);
        
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $ratio = 1;
        } else {
            $ratio = $width > $height
                ? $maxWidth / $width
                : $maxHeight / $height;
        }
        
        $newWidth = $width * $ratio;
        $newHeight = $height * $ratio;
        
        $this->_handle($src, $dest, $newWidth, $newHeight, $width, $height);
                
        return new \Jabba\File\Image($dest);
    }
    
    /**
     * Handles the resizing and saving process
     * 
     * @param string $src
     * @param string $dest
     * @param integer $newWidth
     * @param integer $newHeight
     * @param integer $width
     * @param integer $height
     * 
     * @return \Jabba\File\Image\Resize
     */
    protected function _handle($src, $dest, $newWidth, $newHeight, $width, $height)
    {
        switch ($this->getImage()->getExtension()) {
            case 'jpg':
            case 'jpeg':
                $this->_handleJpeg($src, $dest, $newWidth, $newHeight, $width, $height);
                break;
            case 'gif':
                $this->_handleGif($src, $dest, $newWidth, $newHeight, $width, $height);
                break;
            case 'png':
                $this->_handlePng($src, $dest, $newWidth, $newHeight, $width, $height);
                break;
        }
        
        return $this;
    }
    
    /**
     * Handles resizing and saving images of type "jpg"
     * 
     * @param string $src
     * @param string $dest
     * @param integer $newWidth
     * @param integer $newHeight
     * @param integer $width
     * @param integer $height
     * 
     * @return \Jabba\File\Image\Resize
     */
    protected function _handleJpeg($src, $dest, $newWidth, $newHeight, $width, $height)
    {
        $image1 = imagecreatetruecolor($newWidth, $newHeight);      
        $image2 = imagecreatefromjpeg($src);
        imagecopyresampled($image1, $image2, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagejpeg($image1, $dest, 85);
        
        return $this;
    }
    
    /**
     * Handles resizing and saving images of type "gif"
     * 
     * @param string $src
     * @param string $dest
     * @param integer $newWidth
     * @param integer $newHeight
     * @param integer $width
     * @param integer $height
     * 
     * @return \Jabba\File\Image\Resize
     */
    protected function _handleGif($src, $dest, $newWidth, $newHeight, $width, $height)
    {
        $image1 = imagecreatetruecolor($newWidth, $newHeight);      
        $image2 = imagecreatefromgif($src);
        imagecopyresampled($image1, $image2, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagegif($image1, $dest);
        
        return $this;
    }
    
    /**
     * Handles resizing and saving images of type "png"
     * 
     * @param string $src
     * @param string $dest
     * @param integer $newWidth
     * @param integer $newHeight
     * @param integer $width
     * @param integer $height
     * 
     * @return \Jabba\File\Image\Resize
     */
    protected function _handlePng($src, $dest, $newWidth, $newHeight, $width, $height)
    {
        $image1 = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($image1, false);
        imagesavealpha($image1, true);  

        $image2 = imagecreatefrompng($src);
        imagealphablending($image2, true);

        imagecopyresampled($image1, $image2, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        imagepng($image1, $dest, 9);
        
        return $this;
    }
}