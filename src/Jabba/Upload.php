<?php namespace Jabba;

class Upload
{
    protected $_data;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760)
    {
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->setAllowedExtensions($allowedExtensions);
        $this->setSizeLimit($sizeLimit);

        $this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->setHandler(new Upload\Xhr());
        } elseif (isset($_FILES['qqfile'])) {
            $this->setHandler(new Upload\Form());
        } else {
            $this->setHandler(false);
        }
    }

    private function checkServerSettings()
    {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->getSizeLimit() || $uploadSize < $this->getSizeLimit()) {
            $size = max(1, $this->getSizeLimit() / 1024 / 1024) . 'M';
            throw new \Exception("Server error: increase post_max_size and upload_max_filesize to $size");
        }
    }

    private function toBytes($str)
    {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Handles the file upload process
     *
     * @param  string $newFileName Use this value (if not null) as the uploaded file's name
     *
     * @return Jabba_File
     * @throws Exception
     */
    public function handle()
    {
        $path = $this->getDirectory()->getPath();

        if (!is_writable($path)) {
            throw new \Exception("Server error: upload directory isn't writable.");
        }

        if (!$this->getHandler()) {
            throw new \Exception('No files were uploaded.');
        }

        $size = $this->getHandler()->getSize();

        if ($size == 0) {
            throw new \Exception('File is empty');
        }

        if ($size > $this->getSizeLimit()) {
            throw new \Exception('File is too large');
        }

        $pathinfo = pathinfo($this->getHandler()->getName());
        $fileName = $pathinfo['filename'];
        $ext = $pathinfo['extension'];

        if ($this->getAllowedExtensions() && !in_array(strtolower($ext), $this->getAllowedExtensions())) {
            $these = implode(', ', $this->getAllowedExtensions());
            throw new \Exception('File has an invalid extension, only files of type ' . $these . ' are allowed.');
        }

        $length = rand(7, 9);
        $uniqueId = File::getRandomString($length);
        $resource = File\Image::getPathByName($path, $uniqueId, 3, true) . $uniqueId . '.' . $ext;
        while (file_exists($resource)) {
            $resource = File\Image::getPathByName($path, $uniqueId, 3, true) . File::getRandomString($length) . '.' . $ext;
        }

        if ($this->getHandler()->save($resource)) {
            if (in_array($ext, array('jpg', 'jpeg', 'gif', 'png'))) {
                $file = new File\Image($resource);
            } else {
                $file = new File($resource);
            }

            return $file->setOriginalName($fileName);
        } else {
            throw new \Exception('Could not save the uploaded file');
        }
    }

    public function setAllowedExtensions($val)
    {
        $this->_data['allowed_extensions'] = $val;

        return $this;
    }

    public function setSizeLimit($val)
    {
        $this->_data['size_limit'] = $val;

        return $this;
    }

    public function setHandler($val)
    {
        $this->_data['handler'] = $val;

        return $this;
    }

    public function setDirectory($val)
    {
        $this->_data['directory'] = $val;

        return $this;
    }

    public function getAllowedExtensions()
    {
        return $this->_data['allowed_extensions'];
    }

    public function getSizeLimit()
    {
        return $this->_data['size_limit'];
    }

    public function getHandler()
    {
        return $this->_data['handler'];
    }

    public function getDirectory()
    {
        return $this->_data['directory'];
    }
}