<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'password',
        'fullname',
        'avatar',
        'role'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages  = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind       = [];
    protected $beforeDelete    = [];
    protected $afterDelete     = [];

    /**
     * Authenticate user
     */
    public function authenticate($username, $password)
    {
        $user = $this->where('username', $username)
                    ->first();
        
        if ($user && md5($password) === $user['password']) {
            return $user;
        }
        
        return null;
    }

    /**
     * Get user by username
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Get available roles from database ENUM
     */
    public function getAvailableRoles()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SHOW COLUMNS FROM `users` WHERE Field = 'role'");
        $result = $query->getRow();
        
        if ($result && isset($result->Type)) {
            // Extract ENUM values from Type string like: enum('admin','staff','customer')
            preg_match("/enum\('(.+)'\)/i", $result->Type, $matches);
            if (isset($matches[1])) {
                $roles = explode("','", $matches[1]);
                return $roles;
            }
        }
        
        // Fallback to default roles if can't get from database
        return ['admin', 'staff', 'customer'];
    }
}
