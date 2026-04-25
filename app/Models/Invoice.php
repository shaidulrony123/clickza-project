<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'client_name',
        'client_email',
        'client_phone',
        'client_address',
        'issue_date',
        'due_date',
        'status',
        'items',
        'subtotal',
        'discount',
        'total',
        'notes',
    ];

    protected $casts = [
        'items' => 'array',
        'issue_date' => 'date',
        'due_date' => 'date',
    ];
}
