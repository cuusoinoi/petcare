<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\GeneralSettingModel;

class GeneralSettingController extends BaseController
{
    protected $generalSettingModel;

    public function __construct()
    {
        $this->generalSettingModel = new GeneralSettingModel();
    }

    /**
     * Show settings page
     */
    public function index()
    {
        $settings = $this->generalSettingModel->getSettings();

        $data = [
            'title' => 'Cài đặt chung - UIT Petcare',
            'settings' => $settings
        ];

        return view('admin/settings/settings', $data);
    }

    /**
     * Handle update settings
     */
    public function update()
    {
        $settingsData = [
            'clinic_name' => trim($this->request->getPost('clinic_name')),
            'clinic_address_1' => trim($this->request->getPost('clinic_address_1')),
            'clinic_address_2' => trim($this->request->getPost('clinic_address_2')),
            'phone_number_1' => trim($this->request->getPost('phone_number_1')),
            'phone_number_2' => trim($this->request->getPost('phone_number_2')),
            'representative_name' => trim($this->request->getPost('representative_name')),
            'default_daily_rate' => (int)$this->request->getPost('default_daily_rate'),
            'checkout_hour' => $this->request->getPost('checkout_hour'),
            'overtime_fee_per_hour' => (int)$this->request->getPost('overtime_fee_per_hour'),
            'signing_place' => trim($this->request->getPost('signing_place'))
        ];

        if ($this->generalSettingModel->updateSettings($settingsData)) {
            session()->setFlashdata('success', 'Cập nhật cài đặt thành công!');
        } else {
            session()->setFlashdata('error', 'Có lỗi xảy ra khi cập nhật cài đặt.');
        }

        return redirect()->to('/admin/settings');
    }
}
