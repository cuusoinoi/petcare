<?php

namespace App\Models;

use CodeIgniter\Model;

class TreatmentCourseModel extends Model
{
    protected $table            = 'treatment_courses';
    protected $primaryKey       = 'treatment_course_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_id',
        'pet_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $useTimestamps = false;

    /**
     * Get treatment courses with pagination
     */
    public function getTreatmentCoursesPaginated($limit, $offset)
    {
        return $this->orderBy('treatment_course_id', 'DESC')
            ->findAll($limit, $offset);
    }

    /**
     * Update treatment course status to completed
     */
    public function completeTreatmentCourse($id, $endDate)
    {
        return $this->update($id, [
            'end_date' => $endDate,
            'status' => 0
        ]);
    }
}
