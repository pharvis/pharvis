<?php

namespace Models;

class Folder{
    
    /**
     *
     * @Relationship.OneToMany("File", "folder_id")
     */
    protected $files = [];
    

    public function getFiles(){
        
    }
}