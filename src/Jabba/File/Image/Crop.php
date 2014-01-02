<?php namespace Jabba\File\Image;

class Crop
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
     * @return \Jabba\File\Image\Crop
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
     * Crops the image and saves it to the original image's path
     *      
     * @param integer $x
     * @param integer $y
     * @param integer $width
     * @param integer $height
     * 
     * @return string Generated name for the cropped image
     * @throws \Exception
     */
    public function handle($x, $y, $width, $height)
    {   
        if (!in_array($this->getImage()->getExtension(), array('jpg', 'jpeg', 'png', 'gif'))) {
            throw new \Exception('Image format is not supported.');
        }
        
        $image = $this->getImage();
        
        $src = $image->getPath();

        $uniqueId = \Jabba\File::getRandomString();
        $dest = \Jabba\Jabba::getInstance()->getPath() . \Jabba\File\Image::getPathByName($image->getDirectory()->getPath(), $uniqueId, 3, true) . $uniqueId . '.' . $image->getExtension();;

        $this->_handle($src, $dest, $x, $y, $width, $height);
                
        return new \Jabba\File\Image($dest);
    }
    
    /**
     * Crops the image and saves it to the original image's path
     * 
     * @param string $src
     * @param string $dest
     * @param integer $x
     * @param integer $y
     * @param integer $width
     * @param integer $height
     * 
     * @return string Generated name for the cropped image
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
     * Handles cropping images of type "jpg"
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
        $image1 = imagecreatetruecolor($width, $height);        
        $image2 = imagecreatefromjpeg($src);
        imagecopyresampled($image1, $image2, 0, 0, $x, $y, $width, $height, $width, $height);
        imagejpeg($image1, $dest, 85);
        
        return $this;
    }
    
    /**
     * Handles cropping and saving images of type "gif"
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
        $image1 = imagecreatetruecolor($width, $height);        
        $image2 = imagecreatefromgif($src);
        imagecopyresampled($image1, $image2, 0, 0, $x, $y, $width, $height, $width, $height);
        imagegif($image1, $dest);
        
        return $this;
    }
    
    /**
     * Handles cropping and saving images of type "png"
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
        $image1 = imagecreatetruecolor($width, $height);
        imagealphablending($image1, false);
        imagesavealpha($image1, true);  

        $image2 = imagecreatefrompng($src);
        imagealphablending($image2, true);

        imagecopyresampled($image1, $image2, 0, 0, $x, $y, $width, $height, $width, $height);

        imagepng($image1, $dest, 9);
        
        return $this;
    }
}