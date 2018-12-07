<?php

namespace VisORM;

class EntityManager{
    
    public function find(string $entityName, int $id){
        return new \Models\Document();
    }
}