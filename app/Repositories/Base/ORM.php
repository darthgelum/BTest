<?php
namespace App\Repositories\Base;
use Exception;
use FrameWork\Kernel;

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
        $sql = "SELECT *  FROM {$this->table} {$conditions};";
        $query = $this->db->prepare($sql);

        $query->execute();
        $objects = [];
        while ($row = $query->fetch())
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