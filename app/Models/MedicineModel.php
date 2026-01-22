<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicineModel extends Model
{
    protected $table            = 'medicines';
    protected $primaryKey       = 'medicine_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'medicine_name',
        'medicine_route'
    ];

    protected $useTimestamps = false;

    /**
     * Get medicines with pagination
     */
    public function getMedicinesPaginated($limit, $offset)
    {
        return $this->orderBy('medicine_id', 'DESC')
            ->findAll($limit, $offset);
    }
}
