<?php


class ORM
{
    protected $table;
    protected $db;
    public function __construct()
    {
        if(!$this->table)
        {
            throw new Exception("Table not set");
        }
        global $app;
        $this->db = $app->getDB();
    }

    public function selectFullRowsAsObjects($conditions = "")
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} {$conditions}");
        $objects = [];
        while ($row = $stmt->fetch())
        {
            $class =  get_class($this);
            $obj = new $class();
            foreach ($row as $key=>$val)
            {
                $obj->$key = $val;
            }
            $objects[] = $obj;
        }
        return $objects;
    }
}