<?php

namespace App\Models;

use CodeIgniter\Model;

class PrescriptionModel extends Model
{
    protected $table            = 'prescriptions';
    protected $primaryKey       = 'prescription_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'treatment_session_id',
        'medicine_id',
        'treatment_type',
        'dosage',
        'unit',
        'frequency',
        'status',
        'notes'
    ];

    protected $useTimestamps = false;

    /**
     * Get prescriptions by session ID
     */
    public function getPrescriptionsBySessionId($sessionId)
    {
        return $this->where('treatment_session_id', $sessionId)->findAll();
    }

    /**
     * Get prescriptions by session ID with medicine info
     */
    public function getPrescriptionsWithMedicine($sessionId)
    {
        return $this->select('prescriptions.*, medicines.medicine_name, medicines.medicine_route')
            ->join('medicines', 'medicines.medicine_id = prescriptions.medicine_id', 'left')
            ->where('treatment_session_id', $sessionId)
            ->findAll();
    }
}
