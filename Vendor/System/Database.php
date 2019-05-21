<?php

namespace System;

use PDO;
use PDOException;

class Database
{
    private $app;

    private static $connection;

    private $table;

    private $data = [];

    private $bindings = [];

    private $wheres = [];

    private $selects = [];

    private $joins = [];

    private $limit;

    private $offset;
   
    private $rows;

    private $orderBy = [];

    private $lastId;

    public function __construct(Application $app)
    {
        $this->app = $app;

        if (! $this->isConnected()) {
            $this->connect();
        }
    }

    private function  isConnected()
    {
        return static::$connection instanceof PDO;
    }

    private function connect()
    {
        $data = $this->app->file->call($this->app->file->to('config/database', '.php'));

        extract($data);
        
        try {
            static::$connection  = new PDO('mysql:host=' . $server . ';dbname=' . $dbname, $dbuser, $dbpass);

            static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            
            static::$connection->exec('SET NAMES utf8');

        } catch (PDOException $e) {

            die($e->getMessage());
        }
    }

    public function connection()
    {
        return static::$connection;
    }

    public function table($table)
    {
        $this->table = $table;
        
        return $this;

    }

    public function select($select = '*')
    {
        $this->selects[] = $select;
  
        return $this;
    }

    public function join($join, $localId = null, $forginId = null)
    {
        if (! $localId) {
            $localId =  'id';
        }
  
        if (! $forginId) {
            $forginId =  rtrim($this->table, 's') . '_id';
        }

        $sql = $join . ' ON ' . $this->table . '.' . $localId . ' = ' . $join . '.' . $forginId;
    
        $this->joins[] = $sql;

        return $this;
    }

    public function where(...$bindings)
    {
        $sql = array_shift($bindings);

        if (is_array($bindings[0])) $bindings = $bindings[0];
        
        $this->addToBindings($bindings);

        $this->wheres[] = $sql;

        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->limit = $limit;

        $this->offset = $offset;

        return $this;
    }

    public function rows()
    {
        return $this->rows;
    }

    public function orderBy($orderBy, $sort = 'ASC')
    {
        $this->orderBy = [$orderBy, $sort];
        
        return $this;
    }

    public function fetch($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = $this->fetchStatment();
        
        $query = $this->query($sql, $this->bindings);

        $result = $query->fetch();

        $this->rows = $query->rowCount();
        
        return $result;
    }

    public function fetchAll($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = $this->fetchStatment();
        
        $query = $this->query($sql, $this->bindings);
        
        $results = $query->fetchall();

        $this->rows = $query->rowCount();

        return $results;
    }

    private function fetchStatment()
    {
        $sql = 'SELECT ';

        if ( $this->selects) {

            $sql .= implode(', ', $this->selects);

        } else {
            $sql .= '*';
        }
 
        $sql .= ' FROM ' . $this->table . ' ';

        if ($this->joins) {

            $sql .= 'LEFT JOIN ' . implode(' ', $this->joins);
        }
        
        if ($this->wheres) {

            $sql .= ' WHERE ' . implode(' ', $this->wheres);
        }
        
        if ($this->limit) {

            $sql .= ' LIMIT ' . implode (' ', $this->limit);
        }
        
        if ($this->offset) {

            $sql .= ' OFFSET ' . implode (' ', $this->offset);
        }
        
        if ($this->orderBy) {

            $sql .= ' ORDER BY ' . implode (' ', $this->orderBy);
        }

        return $sql;
    }

    public function lastId()
    {
        return $this->lastId;
    }

    public function from($table)
    {
        return $this->table($table);
    }

    public function data($key, $value = null)
    {
        if (is_array($key)) {
      
            $this->data = array_merge($this->data, $key);

            $this->addToBindings($key);
       
        } else {
      
            $this->data[$key] = $value;

            $this->addToBindings($value);

        }
        
        return $this;
    }

    public function insert($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = 'INSERT INTO ' . $this->table . ' SET ';

        $sql .= $this->setField();

        $this->query($sql, $this->bindings);

        $this->lastId = $this->connection()->lastInsertId();

        return $this;
    }

    public function update($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = 'UPDATE ' . $this->table . ' SET ';

        $sql .= $this->setField();
        
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode('', $this->wheres);
        }
        
        $this->query($sql, $this->bindings);

        return $this;
    }

    public function delete($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = 'DELETE FROM ' . $this->table . ' ';
        
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode('', $this->wheres);
        }
        
        $this->query($sql, $this->bindings);

        return $this;
    }

    private function setField()
    {
        $sql = '';

        foreach($this->data as $key => $value) {

            $sql .= '`' . $key . '` = ? ,';
        }
        
        $sql = rtrim($sql, ' ,');

        return $sql;
    }

    private function addToBindings($value)
    {
        if (is_array($value)) {
            $this->bindings = array_merge($this->bindings, array_values($value));
        } else {
            
            $this->bindings[] = $value;
        }
    }

    public function query(...$bindings)
    {
        $sql = array_shift($bindings);

        if (count($bindings) == 1 AND is_array($bindings[0])) {
            $bindings = $bindings[0];
        }

        try {
            $query = $this->connection()->prepare($sql);

            foreach ($bindings as $key => $value) {
                $query->bindValue($key + 1, _e($value));
            }

            $query->execute();

            $this->reset();

            return $query;

        } catch (PDOException $e) {

            die($e->getMessage());
        }
    }

    private function reset()
    {
        $this->table = null;

        $this->data = [];
    
        $this->bindings = [];
    
        $this->wheres = [];
    
        $this->selects = [];
    
        $this->joins = [];
    
        $this->limit = null;
    
        $this->offset = 0;

        $this->rows = 0;
    
        $this->orderBy = [];
    
    }
}