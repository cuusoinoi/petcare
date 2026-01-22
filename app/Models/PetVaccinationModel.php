<?php

namespace App\Models;

use CodeIgniter\Model;

class PetVaccinationModel extends Model
{
    protected $table            = 'pet_vaccinations';
    protected $primaryKey       = 'pet_vaccination_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'vaccine_id',
        'customer_id',
        'pet_id',
        'doctor_id',
        'vaccination_date',
        'next_vaccination_date',
        'notes'
    ];

    protected $useTimestamps = false;

    /**
     * Get pet vaccinations with pagination
     */
    public function getPetVaccinationsPaginated($limit, $offset)
    {
        return $this->orderBy('pet_vaccination_id', 'DESC')
            ->findAll($limit, $offset);
    }
}
