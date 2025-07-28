<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class BrevoMain extends Controller
{
    public function send()
    {
        $request = $this->request->getJSON();

        if (!$request || !isset($request->to) || !isset($request->subject) || (!isset($request->message) && !isset($request->view))) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak lengkap. Diperlukan: to, subject, message.'
            ])->setStatusCode(400);
        }

        $email = \Config\Services::email();

        $email->setFrom(getenv('email.Brand'), 'Pro Laundry');
        $email->setTo($request->to);
        $email->setSubject($request->subject);

        // Support for template rendering
        if (isset($request->view) && isset($request->viewData)) {
            $viewData = is_array($request->viewData) ? $request->viewData : (array) $request->viewData;
            $message = view($request->view, ['data' => $viewData]);
        } else {
            $message = $request->message;
        }
        $email->setMessage($message);

        if ($email->send()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '✅ Email berhasil dikirim.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengirim email.',
                'debug'  => $email->printDebugger(['headers'])
            ])->setStatusCode(500);
        }
    }
}