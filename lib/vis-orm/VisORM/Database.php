<?php

namespace VisORM;

class Database extends \PDO{

    public function __construct(string $dns, string $username, string $password, array $options = []){
        
        try{
            parent::__construct($dns, $username, $password, $options);
            $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $e){
            throw new DbException('sss');
        }
    }
    
    public function query(string $sql, array $params = []){
        try{
            $stm = $this->prepare($sql);
            $stm->execute($params);
            return $stm;
        }catch (\PDOException $e){
            throw new QueryException($e->getMessage());
        }
    }
    
    public function insert(string $tableName, array $columns){
        
        $sql = 'INSERT INTO ' . $tableName . ' (' . join(',', array_keys($columns)) . ') VALUES (';
        
        foreach($columns as $column => $value){
            if($value instanceof DbFunction){
                $sql .= $value->execute() . ',';
                unset($columns[$column]);
            }else{
                $sql .= ':' . $column. ',';
            }
        }
        
        $sql = trim($sql, ',') . ')';
        
        return $this->query($sql, $columns)->rowCount();
    }
}
