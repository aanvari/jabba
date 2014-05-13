<?php namespace Jabba\File\Image;

class Compress
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
     * @return \Jabba\File\Image\Compress
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
     * @param string $path
     * 
     * @return \Jabba\File\Image\Compress
     */
    public function handle($path = false)
    {
        $image = $this->getImage();
        $src = $image->getPath();        
        
        if (!$path || $path == $image->getDirectory()->getPath()) {
            $dest = $src;            
        } else {
            $dest = $path . $image->getName() . '.' . $image->getExtension();
        } 
        
        $this->_handle($src, $dest);
	                    
        return $image->setDirectory(new \Jabba\Directory($dest->getDirectory()->getPath()));
    }
    
    /**
     * Handles the compressing and saving process
     * 
     * @param string $src
     * @param string $dest
     * 
     * @return \Jabba\File\Image\Compress
     */
    protected function _handle($src, $dest)
    {
        switch ($this->getImage()->getExtension()) {
            case 'jpg':
            case 'jpeg':
                $this->_handleJpeg($src, $dest);
                break;
            case 'gif':
                // don't do anything
                break;
            case 'png':
                $this->_handlePng($src);
                break;
        }
        
        return $this;
    }
    
    /**
     * Handles compressing and saving images of type "jpg" using jpegtran
     * 
     * @param string $src
     * @param string $dest
     * 
     * @return \Jabba\File\Image\Compress
     */
    protected function _handleJpeg($src, $dest)
    {
	    $image = $this->getImage();
	    $tmp = '/tmp/' . $image->getName() . '.' . $image->getExtension();
	    
	    // Compress the file and write it to temp folder
        shell_exec('jpegtran -optimize -progressive ' . $src . ' > ' . $tmp);

	    // If the compressed file is smaller than original file replace the original with temp (compressed) file
	    if (filesize($tmp) < filesize($src)) {		    
		    shell_exec('jpegtran -optimize -progressive ' . $tmp . ' > ' . $dest);
	    }
        
        return $this;
    }
        
    /**
     * Handles compressing and saving images of type "png" using pngquant
	 * Note: installing pngquant on Ubuntu:
	 * 
	 * 1. sudo vim /etc/apt/sources.list
	 * 2. add to the end:
	 * 		deb http://us.archive.ubuntu.com/ubuntu raring main universe
	 * 3. sudo apt-get update
	 * 4. sudo apt-get install pngquant
	 * 
     * @param string $src
     * 
     * @return \Jabba\File\Image\Compress
     */
    protected function _handlePng($src)
    {
	    shell_exec('pngquant --quality=60-90 --ext=.png --force ' . $src);
        
        return $this;
    }
}