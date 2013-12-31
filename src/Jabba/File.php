<?php namespace Jabba;

class File
{
    protected $_data;
    
    public function __construct($path)
    {
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        $file = array_pop($parts);
        
        $pathinfo = pathinfo($file);
        $name = $pathinfo['filename'];
        $extension = $pathinfo['extension'];
        
        $directory = implode(DIRECTORY_SEPARATOR, $parts);        

        $this->setName($name)
            ->setOriginalName($name)
            ->setExtension($extension)
            ->setDirectory(new Directory($directory));
    }
    
    /**
     * Sets the value for directory where the file is housed in
     * 
     * @param \Jabba\Directory $val
     * 
     * @return \Jabba\File
     */
    public function setDirectory($val)
    {
        $this->_data['directory'] = $val;
        
        return $this;
    }
    
    /**
     * Sets file name
     * 
     * @param string $val
     * 
     * @return \Jabba\File
     */
    public function setName($val)
    {
        $this->_data['name'] = $val;
        
        return $this;
    }

    /**
     * Sets original file name
     * 
     * @param string $val
     * 
     * @return \Jabba\File
     */
    public function setOriginalName($val)
    {
        $this->_data['original_name'] = $val;
        
        return $this;
    }
    
    /**
     * Sets file extension
     * 
     * @param string $val
     * 
     * @return \Jabba\File
     */
    public function setExtension($val)
    {
        $this->_data['ext'] = $val;
        
        return $this;
    }
    
    /**
     * Returns the directory object for the file
     * 
     * @return \Jabba\Directory
     */
    public function getDirectory()
    {
        return $this->_data['directory'];
    }
    
    /**
     * Returns the fiel name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->_data['name'];
    }

    /**
     * Returns original file name
     * 
     * @return string
     */
    public function getOriginalName()
    {
        return $this->_data['original_name'];
    }
    
    /**
     * Returns file's extension
     * 
     * @return string
     */
    public function getExtension()
    {
        return $this->_data['ext'];
    }
    
    /**
     * Returns file's path (directory + name + extension)
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->getDirectory()->getPath() . $this->getName() . '.' . $this->getExtension();
    }

    /**
     * Returns a unique identifier used to name files to prevent naming conflict
     * 
     * @param  integer $length Length of the identifier
     * 
     * @return string
     */
    public static function getRandomString($length = 9) {
        $characters = '0123456789';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    /**
     * Deletes the file
     * 
     * @return null
     */
    public function delete()
    {        
        unlink($this->getPath());

        return true;
    }
}
