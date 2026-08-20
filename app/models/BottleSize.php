<?php

namespace App\Models;

use App\Core\Model;

class BottleSize extends Model
{
    protected $table = 'bottle_sizes';

    public function getActive()
    {
        return $this->db->fetchAll("SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY capacity_ml");
    }

    public function getPricing($sizeId, $quantity)
    {
        $pricing = $this->db->fetch(
            "SELECT * FROM pricing WHERE bottle_size_id = ? AND min_quantity <= ? AND (max_quantity IS NULL OR max_quantity >= ?) ORDER BY min_quantity DESC LIMIT 1",
            [$sizeId, $quantity, $quantity]
        );
        
        return $pricing ? $pricing['price_per_unit'] : 0;
    }
}
