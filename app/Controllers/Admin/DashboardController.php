<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\MedicalRecordModel;
use App\Models\PetEnclosureModel;
use App\Models\InvoiceModel;

class DashboardController extends BaseController
{
    protected $customerModel;
    protected $petModel;
    protected $medicalRecordModel;
    protected $petEnclosureModel;
    protected $invoiceModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->medicalRecordModel = new MedicalRecordModel();
        $this->petEnclosureModel = new PetEnclosureModel();
        $this->invoiceModel = new InvoiceModel();
    }

    /**
     * Dashboard page
     */
    public function index()
    {
        // Set timezone to Vietnam
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $currentYear = date('Y');
        $currentMonth = date('m');

        // Get counts
        $customerCount = $this->customerModel->getCustomerCount();
        $petCount = $this->petModel->getPetCount();
        $medicalRecordCount = $this->medicalRecordModel->getMedicalRecordCountByMonth($currentYear, $currentMonth);
        $petEnclosureCount = $this->petEnclosureModel->getPetEnclosureCountByMonth($currentYear, $currentMonth);
        $invoiceRevenue = $this->invoiceModel->getInvoiceRevenueByYear($currentYear);

        // Calculate percentage changes
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $medicalYesterday = $this->medicalRecordModel->getMedicalRecordCountByDate($yesterday);
        $medicalToday = $this->medicalRecordModel->getMedicalRecordCountByDate($today);
        $medicalPercentChange = $this->calculatePercentChange($medicalYesterday, $medicalToday);

        $enclosureYesterday = $this->petEnclosureModel->getPetEnclosureCountByDate($yesterday);
        $enclosureToday = $this->petEnclosureModel->getPetEnclosureCountByDate($today);
        $enclosurePercentChange = $this->calculatePercentChange($enclosureYesterday, $enclosureToday);

        $lastMonth = date('m', strtotime('-1 month'));
        $lastMonthYear = date('Y', strtotime('-1 month'));
        $lastMonthRevenue = $this->invoiceModel->getInvoiceRevenueByMonth($lastMonthYear, $lastMonth);
        $thisMonthRevenue = $this->invoiceModel->getInvoiceRevenueByMonth($currentYear, $currentMonth);
        $revenuePercentChange = $this->calculatePercentChange($lastMonthRevenue, $thisMonthRevenue);

        // Get chart data
        $medicalRecordsData = $this->medicalRecordModel->getMedicalRecordsByDay(7);
        $dates = array_keys($medicalRecordsData);
        $counts = array_values($medicalRecordsData);

        $checkinCheckoutData = $this->petEnclosureModel->getCheckinCheckoutStats(7);
        $monthlyRevenueStats = $this->invoiceModel->getMonthlyRevenueStats();
        $revenueByService = $this->invoiceModel->getRevenueByServiceTypeAndYear($currentYear);
        $serviceNames = array_column($revenueByService, 'service_name');
        $serviceRevenues = array_column($revenueByService, 'total_revenue');

        // Helper function for formatting numbers
        function formatNumberShort($num) {
            if ($num >= 1000000000) {
                return round($num / 1000000000, 1) . 'B';
            } elseif ($num >= 1000000) {
                return round($num / 1000000, 1) . 'M';
            } elseif ($num >= 1000) {
                return round($num / 1000, 1) . 'K';
            } else {
                return $num;
            }
        }

        $data = [
            'title' => 'Tổng quan - UIT Petcare',
            'customerCount' => $customerCount,
            'petCount' => $petCount,
            'medicalRecordCount' => $medicalRecordCount,
            'petEnclosureCount' => $petEnclosureCount,
            'invoiceRevenue' => $invoiceRevenue,
            'medicalPercentChange' => $medicalPercentChange,
            'enclosurePercentChange' => $enclosurePercentChange,
            'revenuePercentChange' => $revenuePercentChange,
            'medicalRecordsData' => $medicalRecordsData,
            'dates' => $dates,
            'counts' => $counts,
            'checkinCheckoutData' => $checkinCheckoutData,
            'monthlyRevenueStats' => $monthlyRevenueStats,
            'serviceNames' => $serviceNames,
            'serviceRevenues' => $serviceRevenues
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Calculate percentage change
     */
    private function calculatePercentChange($oldValue, $newValue)
    {
        if ($oldValue == 0 && $newValue > 0) {
            return 100;
        } elseif ($oldValue > 0) {
            return (($newValue - $oldValue) / $oldValue) * 100;
        }
        return 0;
    }
}
