<?php

namespace App\Controllers;

use App\Libraries\MidtransSnap;
use CodeIgniter\Controller;

class Checkout extends Controller
{
    public function index()
    {
        return view('checkout');
    }

    public function token()
    {
        $snap = new MidtransSnap();

        // Ambil data dari POST (JSON atau form)
        $input = $this->request->getJSON() ?: $this->request->getPost();
        $order_id = $input->order_id ?? $input['order_id'] ?? uniqid();
        $gross_amount = $input->total ?? $input['total'] ?? 150000;
        $first_name = $input->nama ?? $input['nama'] ?? 'Jack Kolor';

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $gross_amount
            ],
            'customer_details' => [
                'first_name' => $first_name,
                'email' => 'jack@example.com',
                'phone' => '081234567890'
            ]
        ];

        $token = $snap->createSnapToken($params);
        return $this->response->setJSON(['snapToken' => $token]);
    }
}