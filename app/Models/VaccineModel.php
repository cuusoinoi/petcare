<?php

namespace App\Models;

use CodeIgniter\Model;

class VaccineModel extends Model
{
    protected $table            = 'vaccines';
    protected $primaryKey       = 'vaccine_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'vaccine_name',
        'description'
    ];

    protected $useTimestamps = false;

    /**
     * Get vaccines with pagination
     */
    public function getVaccinesPaginated($limit, $offset)
    {
        return $this->orderBy('vaccine_id', 'DESC')
            ->findAll($limit, $offset);
    }
}
