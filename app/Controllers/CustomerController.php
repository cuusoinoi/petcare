<?php

namespace App\Controllers;

use App\Models\GeneralSettingModel;
use App\Models\ServiceTypeModel;
use App\Models\DoctorModel;

class CustomerController extends BaseController
{
    protected $settingModel;
    protected $serviceTypeModel;
    protected $doctorModel;

    public function __construct()
    {
        $this->settingModel = new GeneralSettingModel();
        $this->serviceTypeModel = new ServiceTypeModel();
        $this->doctorModel = new DoctorModel();
    }

    /**
     * Trang chủ
     */
    public function index()
    {
        $settings = $this->settingModel->getSettings();
        $services = $this->serviceTypeModel->findAll();
        $doctors = $this->doctorModel->findAll();

        $data = [
            'title' => 'UIT Petcare - Phòng khám thú y & Spa thú cưng',
            'settings' => $settings,
            'services' => $services,
            'doctors' => $doctors
        ];

        return view('customer/home', $data);
    }

    /**
     * Trang dịch vụ
     */
    public function services()
    {
        $services = $this->serviceTypeModel->findAll();
        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Dịch vụ - UIT Petcare',
            'services' => $services,
            'settings' => $settings
        ];

        return view('customer/services', $data);
    }

    /**
     * Trang liên hệ
     */
    public function contact()
    {
        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Liên hệ - UIT Petcare',
            'settings' => $settings
        ];

        return view('customer/contact', $data);
    }
}
