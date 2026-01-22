<?php

namespace App\Models;

use CodeIgniter\Model;

class PetEnclosureModel extends Model
{
    protected $table            = 'pet_enclosures';
    protected $primaryKey       = 'pet_enclosure_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_id',
        'pet_id',
        'pet_enclosure_number',
        'check_in_date',
        'check_out_date',
        'daily_rate',
        'deposit',
        'emergency_limit',
        'pet_enclosure_note',
        'pet_enclosure_status'
    ];

    protected $useTimestamps = false;

    /**
     * Get all pet enclosures with pagination
     */
    public function getPetEnclosuresPaginated($limit = 10, $offset = 0)
    {
        return $this->orderBy('pet_enclosure_id', 'DESC')
                    ->findAll($limit, $offset);
    }

    /**
     * Get pet enclosure count by month
     */
    public function getPetEnclosureCountByMonth($year, $month)
    {
        return $this->where('YEAR(check_in_date)', $year)
                    ->where('MONTH(check_in_date)', $month)
                    ->countAllResults();
    }

    /**
     * Get pet enclosure count by date
     */
    public function getPetEnclosureCountByDate($date)
    {
        return $this->where('check_in_date', $date)
                    ->countAllResults();
    }

    /**
     * Get check-in/check-out stats for chart
     */
    public function getCheckinCheckoutStats($days = 7)
    {
        $db = \Config\Database::connect();
        
        // Get check-ins
        $checkinsResult = $db->table($this->table)
                            ->select('DATE(check_in_date) AS visit_date, COUNT(*) AS count', false)
                            ->where('check_in_date >=', date('Y-m-d', strtotime("-$days days")))
                            ->groupBy('DATE(check_in_date)')
                            ->orderBy('visit_date', 'ASC')
                            ->get()
                            ->getResultArray();

        $checkins = [];
        foreach ($checkinsResult as $row) {
            $checkins[$row['visit_date']] = (int)$row['count'];
        }

        // Get check-outs
        $checkoutsResult = $db->table($this->table)
                              ->select('DATE(check_out_date) AS visit_date, COUNT(*) AS count', false)
                              ->where('check_out_date IS NOT NULL')
                              ->where('check_out_date >=', date('Y-m-d', strtotime("-$days days")))
                              ->groupBy('DATE(check_out_date)')
                              ->orderBy('visit_date', 'ASC')
                              ->get()
                              ->getResultArray();

        $checkouts = [];
        foreach ($checkoutsResult as $row) {
            $checkouts[$row['visit_date']] = (int)$row['count'];
        }

        // Normalize data for all days
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i day"));
            $data[$date] = [
                'checkin' => $checkins[$date] ?? 0,
                'checkout' => $checkouts[$date] ?? 0,
            ];
        }

        return $data;
    }
}
