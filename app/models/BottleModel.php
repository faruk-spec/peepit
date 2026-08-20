<?php

namespace App\Models;

use App\Core\Model;

class BottleModel extends Model
{
    protected $table = 'bottle_models';

    public function getActive()
    {
        return $this->db->fetchAll("SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name");
    }
}
