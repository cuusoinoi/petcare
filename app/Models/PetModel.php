<?php

namespace App\Models;

use CodeIgniter\Model;

class PetModel extends Model
{
    protected $table            = 'pets';
    protected $primaryKey       = 'pet_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_id',
        'pet_name',
        'pet_species',
        'pet_gender',
        'pet_dob',
        'pet_weight',
        'pet_sterilization',
        'pet_characteristic',
        'pet_drug_allergy'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
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
     * Get all pets with pagination
     */
    public function getPetsPaginated($limit = 10, $offset = 0)
    {
        return $this->orderBy('pet_id', 'DESC')
                    ->findAll($limit, $offset);
    }

    /**
     * Get pets by customer ID
     */
    public function getPetsByCustomerId($customerId)
    {
        return $this->where('customer_id', $customerId)
                    ->findAll();
    }

    /**
     * Get total count of pets
     */
    public function getPetCount()
    {
        return $this->countAllResults();
    }
}
