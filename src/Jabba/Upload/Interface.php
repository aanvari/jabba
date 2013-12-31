<?php namespace Jabba\Upload;

interface UploadInterface
{
    /**
     * Returns file name 
     */
    public function getName();
    
    /**
     * Returns file size 
     */
    public function getSize();
    
    /**
     * Saves the file to the specified path 
     */
    public function save($path);
}