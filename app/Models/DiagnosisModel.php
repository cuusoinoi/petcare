<?php

namespace App\Models;

use CodeIgniter\Model;

class DiagnosisModel extends Model
{
    protected $table            = 'diagnoses';
    protected $primaryKey       = 'diagnosis_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'treatment_session_id',
        'diagnosis_name',
        'diagnosis_type',
        'clinical_tests',
        'notes'
    ];

    protected $useTimestamps = false;

    /**
     * Get diagnoses by session ID
     */
    public function getDiagnosesBySessionId($sessionId)
    {
        return $this->where('treatment_session_id', $sessionId)->findAll();
    }

    /**
     * Get single diagnosis by session ID
     */
    public function getDiagnosisBySessionId($sessionId)
    {
        return $this->where('treatment_session_id', $sessionId)->first();
    }
}
