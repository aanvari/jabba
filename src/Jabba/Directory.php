<?php namespace Jabba;

class Directory
{
	protected $_data;
	
	public function __construct($path)
	{
		$this->setPath($path);
        Jabba::getInstance()->setPath($path);
	}
	
    /**
     * Sets directory path
     * 
     * @param string $val
     * 
     * @return \Jabba\Directory
     */
	public function setPath($val)
	{
		$this->_data['path'] = rtrim($val, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		
		return $this;
	}
	
    /**
     * Returns directory's path
     * 
     * @return string
     */
	public function getPath()
	{
		return $this->_data['path'];
	}
	
    /**
     * Uploads a file to the directory
     * 
     * @param array $allowedExtensions
     * @param integer $sizeLimit
     * 
     * @return \Jabba\File
     */
	public function upload($allowedExtensions, $sizeLimit = 10485760)
	{
		$upload = new Upload();
		
		return $upload->setDirectory($this)
			->setAllowedExtensions($allowedExtensions)
			->setSizeLimit($sizeLimit)
			->handle();
	}

    /**
     * Returns true if the directory is empty
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        if (!is_readable($this->getPath())) {
            return null;
        }

        return (count(scandir($this->getPath())) == 2);
    }

    /**
     * Removes the directory
     * 
     * @return null
     */
    public function delete($recursive = true)
    {
        if (!$recursive) {
            if ($this->isEmpty()) {
                throw new \Exception('Directory is not empty');
            }

            rmdir($this->getPath());
        } else {
            $this->_delete($this->getPath());
        }

        return null;
    }

    /**
     * Delete a directory recursively
     * 
     * @param  string $dir
     * 
     * @return boolean
     */
    protected function _delete($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
          (is_dir("$dir/$file")) ? $this->_delete("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir); 
    }
}