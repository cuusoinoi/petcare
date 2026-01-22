<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';
    protected $allowedFields = [
        'customer_id', 'pet_id', 'doctor_id', 'service_type_id',
        'appointment_date', 'appointment_type', 'status', 'notes'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    /**
     * Lấy appointments theo customer_id với thông tin liên quan
     */
    public function getAppointmentsByCustomerId($customerId)
    {
        $builder = $this->db->table('appointments');
        return $builder->select('appointments.*, pets.pet_name, doctors.doctor_name, service_types.service_name')
            ->join('pets', 'pets.pet_id = appointments.pet_id', 'left')
            ->join('doctors', 'doctors.doctor_id = appointments.doctor_id', 'left')
            ->join('service_types', 'service_types.service_type_id = appointments.service_type_id', 'left')
            ->where('appointments.customer_id', $customerId)
            ->orderBy('appointments.appointment_date', 'DESC')
            ->get()
            ->getResultArray();
    }
}
