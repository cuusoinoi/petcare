<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table            = 'invoices';
    protected $primaryKey       = 'invoice_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_id',
        'pet_id',
        'pet_enclosure_id',
        'invoice_date',
        'discount',
        'subtotal',
        'deposit',
        'total_amount'
    ];

    protected $useTimestamps = false;

    /**
     * Get all invoices with pagination
     */
    public function getInvoicesPaginated($limit = 10, $offset = 0)
    {
        return $this->orderBy('invoice_id', 'DESC')
                    ->findAll($limit, $offset);
    }

    /**
     * Get invoice revenue by year
     */
    public function getInvoiceRevenueByYear($year)
    {
        $result = $this->selectSum('total_amount', 'revenue')
                      ->where('YEAR(invoice_date)', $year)
                      ->first();
        return $result['revenue'] ?? 0;
    }

    /**
     * Get invoice revenue by month
     */
    public function getInvoiceRevenueByMonth($year, $month)
    {
        $result = $this->selectSum('total_amount', 'revenue')
                      ->where('YEAR(invoice_date)', $year)
                      ->where('MONTH(invoice_date)', $month)
                      ->first();
        return $result['revenue'] ?? 0;
    }

    /**
     * Get monthly revenue stats
     */
    public function getMonthlyRevenueStats($months = 12)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        $result = $builder->select("DATE_FORMAT(invoice_date, '%Y-%m') AS month, SUM(total_amount) AS total_revenue", false)
                         ->where('invoice_date >=', date('Y-m-d', strtotime("-$months months")))
                         ->groupBy("DATE_FORMAT(invoice_date, '%Y-%m')")
                         ->orderBy('month', 'ASC')
                         ->get()
                         ->getResultArray();

        // Normalize data for all months
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i month"));
            $data[$month] = 0;
        }

        foreach ($result as $row) {
            $month = $row['month'];
            if (isset($data[$month])) {
                $data[$month] = (float)$row['total_revenue'];
            }
        }

        return $data;
    }

    /**
     * Get revenue by service type and year
     */
    public function getRevenueByServiceTypeAndYear($year)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('invoice_details id');
        
        $result = $builder->select('st.service_name, SUM(id.total_price) AS total_revenue', false)
                         ->join('service_types st', 'id.service_type_id = st.service_type_id')
                         ->join('invoices i', 'id.invoice_id = i.invoice_id')
                         ->where('YEAR(i.invoice_date)', $year)
                         ->groupBy('st.service_name')
                         ->orderBy('total_revenue', 'DESC')
                         ->get()
                         ->getResultArray();

        return $result;
    }
}
