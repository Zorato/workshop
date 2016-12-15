<?php

class Payment {

    public function getOrderPayments($order)
    {
        $db = ServiceLocator::get('db');
        return $db->select('*')->from('payments')->where('order_id = ?', $order->id)->fetchAll();
    }

    public function capture($ccData, $amount, $currency)
    {
        $db = ServiceLocator::get('db');
        $lastInsertId = $db->insert('payments')->values($ccData + ['amount' => $amount, 'currency' => $currency]);
        $gateway = ServiceLocator::get('payment_gateway');
        $result = $gateway->call('authorizeAndCapture', $ccData, "$currency $amount", $lastInsertId);
        $db->update('payments')->set('result', $result)->where('id = ?', $lastInsertId);
        return $result;
    }

    public function createInvoice($paymentId)
    {
        $pdf = ServiceLocator::get('pdf');
        $db = ServiceLocator::get('db');
        $paymentData = $db->select('*')
            ->from('payments')
            ->join('orders', 'orders.id = payments.order_id')
            ->where('id = ?', $paymentId)
            ->fetch();
        unset($paymentData['cvv'], $paymentData['exp']);
        $paymentData['cc_number'] = '**** **** **** ' . substr($paymentData['cc_number'], -4);
        $document = $pdf->render('templates/invoice.tpl', $paymentData);
        $filepath = 'storage/invoices/invoice_' . $paymentId . '.pdf';
        file_put_contents($filepath, $document);
        unset($document);
        $pdf->clear();
        return $filepath;
    }

}