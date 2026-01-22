<?php

namespace App\Models;

use CodeIgniter\Model;

class GeneralSettingModel extends Model
{
    protected $table            = 'general_settings';
    protected $primaryKey       = 'setting_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'clinic_name',
        'clinic_address_1',
        'clinic_address_2',
        'phone_number_1',
        'phone_number_2',
        'representative_name',
        'checkout_hour',
        'overtime_fee_per_hour',
        'default_daily_rate',
        'signing_place'
    ];

    protected $useTimestamps = false;

    /**
     * Get general settings (should only have one record)
     */
    public function getSettings()
    {
        return $this->first();
    }

    /**
     * Update settings
     */
    public function updateSettings($data)
    {
        $existing = $this->first();
        if ($existing) {
            return $this->update($existing['setting_id'], $data);
        } else {
            return $this->insert($data);
        }
    }
}
