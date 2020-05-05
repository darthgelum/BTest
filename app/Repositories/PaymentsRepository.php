<?php
require_once "app/Models/Payment.php";

class PaymentsRepository extends ORM
{
    protected $table = 'payments';
    protected function getModel()
    {
        return new Payment();
    }

    public function insertPayment($userId=1, $sum=-100)
    {
        try {

            $this->db->beginTransaction();
            //$this->db->exec('LOCK TABLES balances WRITE, payments WRITE;');
            $this->db->exec("INSERT INTO payments (user_id,summ) VALUES ({$userId},{$sum})");
            $this->db->exec("UPDATE balances SET money = money + {$sum} WHERE user_id = {$userId}");
            $this->db->commit();
            $this->db->exec('UNLOCK TABLES');
        }
        catch (Exception $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollback();
                }
                throw $e;
        }
    }
}