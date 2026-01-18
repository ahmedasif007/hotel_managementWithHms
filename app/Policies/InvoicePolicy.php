<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;

class InvoicePolicy
{
    public function view(User $user)
    {
        return $user->hasPermission('view_invoices');
    }

    public function create(User $user)
    {
        return $user->hasPermission('create_invoice');
    }

    public function recordPayment(User $user)
    {
        return $user->hasPermission('record_payment');
    }
}
