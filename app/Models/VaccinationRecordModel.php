<?php

namespace App\Models;

use CodeIgniter\Model;

class VaccinationRecordModel extends Model
{
    protected $table            = 'vaccination_records';
    protected $primaryKey       = 'medical_record_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'medical_record_id',
        'vaccine_name',
        'batch_number',
        'next_injection_date'
    ];

    protected $useTimestamps = false;

    /**
     * Get vaccination record by medical record ID
     */
    public function getVaccinationByMedicalId($medicalRecordId)
    {
        return $this->where('medical_record_id', $medicalRecordId)->first();
    }
}
