<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceDetailModel extends Model
{
    protected $table            = 'invoice_details';
    protected $primaryKey       = 'invoice_detail_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice_id',
        'service_type_id',
        'quantity',
        'unit_price',
        'total_price'
    ];

    protected $useTimestamps = false;

    /**
     * Get invoice details by invoice ID
     */
    public function getInvoiceDetailsByInvoiceId($invoiceId)
    {
        return $this->where('invoice_id', $invoiceId)->findAll();
    }

    /**
     * Get details by invoice ID (alias)
     */
    public function getDetailsByInvoiceId($invoiceId)
    {
        return $this->getInvoiceDetailsByInvoiceId($invoiceId);
    }
}
