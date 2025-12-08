<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        return $this->first('email', $email);
    }

    public function getOrders($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC",
            [$userId]
        );
    }

    public function getUserAnalytics($userId, $limit = 50)
    {
        return $this->db->fetchAll(
            "SELECT * FROM user_analytics WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }
}
