<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getPaymentHistory() {
        $payments = Payment::all();

        return $payments;
    }
    public function getMyPaymentHistory() {
        $payments = Payment::where('user_id', auth()->user()->id)->get();

        return $payments;
    }
}
