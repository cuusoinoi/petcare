<?php

namespace App\Models;

use CodeIgniter\Model;

class TreatmentSessionModel extends Model
{
    protected $table            = 'treatment_sessions';
    protected $primaryKey       = 'treatment_session_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'treatment_course_id',
        'doctor_id',
        'treatment_session_datetime',
        'temperature',
        'weight',
        'pulse_rate',
        'respiratory_rate',
        'overall_notes'
    ];

    protected $useTimestamps = false;

    /**
     * Get treatment sessions by course ID with pagination
     */
    public function getSessionsByCourseIdPaginated($courseId, $limit, $offset)
    {
        return $this->where('treatment_course_id', $courseId)
            ->orderBy('treatment_session_id', 'DESC')
            ->findAll($limit, $offset);
    }

    /**
     * Count sessions by course ID
     */
    public function countSessionsByCourseId($courseId)
    {
        return $this->where('treatment_course_id', $courseId)->countAllResults();
    }
}
