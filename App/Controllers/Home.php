<?php

namespace App\Controllers;

use VisORM\Database;


class Home extends \Core\Mvc\Controller{
    
    public function index(){ 
        /**
        $db = new Database('mysql:host=127.0.0.1;dbname=cloud;charset=utf8', 'syed', 'Yellow77');
        print $db->insert('document', [
            'hash' => new \VisORM\DbFunction('uuid'),
            'name' => 'Syed',
            'is_dir' => true,
            'parent_id' => 0
            
        ]);
         * **/

        $db = new EntityManager('mysql:host=127.0.0.1;dbname=cloud;charset=utf8', 'syed', 'Yellow77');
        
        $db->getRepository('Models.')
    }
}