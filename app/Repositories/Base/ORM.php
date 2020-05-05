<?php


abstract class ORM
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
            $model = $this->getModel();
            foreach ($row as $key=>$val)
            {
                $model->$key = $val;
            }
            $objects[] = $model;
        }
        return $objects;
    }

    abstract protected function getModel();
}