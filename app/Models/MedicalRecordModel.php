<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicalRecordModel extends Model
{
    protected $table            = 'medical_records';
    protected $primaryKey       = 'medical_record_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_id',
        'pet_id',
        'doctor_id',
        'medical_record_type',
        'medical_record_visit_date',
        'medical_record_summary',
        'medical_record_details'
    ];

    protected $useTimestamps = false;

    /**
     * Get all medical records with pagination
     */
    public function getMedicalRecordsPaginated($limit = 10, $offset = 0)
    {
        return $this->orderBy('medical_record_id', 'DESC')
                    ->findAll($limit, $offset);
    }

    /**
     * Get medical record count by month
     */
    public function getMedicalRecordCountByMonth($year, $month)
    {
        return $this->where('YEAR(medical_record_visit_date)', $year)
                    ->where('MONTH(medical_record_visit_date)', $month)
                    ->countAllResults();
    }

    /**
     * Get medical record count by date
     */
    public function getMedicalRecordCountByDate($date)
    {
        return $this->where('medical_record_visit_date', $date)
                    ->countAllResults();
    }

    /**
     * Get medical records by day for chart
     */
    public function getMedicalRecordsByDay($days = 7)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        $result = $builder->select('DATE(medical_record_visit_date) AS visit_date, COUNT(*) AS total', false)
                         ->where('medical_record_visit_date >=', date('Y-m-d', strtotime("-$days days")))
                         ->where('medical_record_visit_date <=', date('Y-m-d'))
                         ->groupBy('DATE(medical_record_visit_date)')
                         ->orderBy('visit_date', 'ASC')
                         ->get()
                         ->getResultArray();

        // Normalize data for all days
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $data[$date] = 0;
        }

        foreach ($result as $row) {
            $data[$row['visit_date']] = (int)$row['total'];
        }

        return $data;
    }
}
