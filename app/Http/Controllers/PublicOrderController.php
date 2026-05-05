<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PublicOrderController extends Controller
{
    /**
     * Home — search form.
     */
    public function index()
    {
        return view('public.home');
    }

    /**
     * Handle search and return result.
     */
    public function search(Request $request)
    {
        $request->validate([
            'customer_number' => 'required|string',
            'invoice_number'  => 'required|string',
        ]);

        $order = Order::active()
            ->where('customer_number', $request->customer_number)
            ->where('invoice_number', $request->invoice_number)
            ->with('statusLogs.user')
            ->first();

        return view('public.home', compact('order'))->with([
            'searched'        => true,
            'customer_number' => $request->customer_number,
            'invoice_number'  => $request->invoice_number,
        ]);
    }
}
