<?php namespace Jabba\Upload;

class Xhr implements UploadInterface
{
    public function getName() 
    {
        return $_GET['qqfile'];
    }
    
    public function getSize() 
    {
        if (isset($_SERVER['CONTENT_LENGTH'])){
            return (int)$_SERVER['CONTENT_LENGTH'];            
        } else {
            throw new \Exception('Getting content length is not supported.');
        }      
    } 
    
    public function save($path) 
    {    
        $input = fopen('php://input', 'r');
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, 'w');        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
}