<?php namespace Jabba\Upload;

class Form implements UploadInterface
{
	function getName() 
    {
        return $_FILES['qqfile']['name'];
    }
    
    function getSize() 
    {
        return $_FILES['qqfile']['size'];
    }
    
    function save($path) 
    {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        
        return true;
    }
}