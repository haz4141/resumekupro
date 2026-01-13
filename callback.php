<?php
/**
 * ResumeKu Pro - Payment Callback Handler
 * Receives payment status from ToyyibPay
 */

// Log all callbacks for debugging
$logFile = __DIR__ . '/payment_logs.txt';
$logData = date('Y-m-d H:i:s') . ' | ' . json_encode($_POST) . "\n";
file_put_contents($logFile, $logData, FILE_APPEND);

// Get callback data from ToyyibPay
$refNo = $_POST['refno'] ?? '';
$status = $_POST['status'] ?? '';
$reason = $_POST['reason'] ?? '';
$billCode = $_POST['billcode'] ?? '';
$orderId = $_POST['order_id'] ?? $_POST['billExternalReferenceNo'] ?? '';
$amount = $_POST['amount'] ?? '';
$transactionId = $_POST['transaction_id'] ?? '';

// Validate required fields
if (empty($refNo) || empty($status) || empty($billCode)) {
    http_response_code(400);
    echo 'Invalid callback data';
    exit;
}

// Payment status codes:
// 1 = Success
// 2 = Pending
// 3 = Failed

$paymentSuccess = ($status == '1');

// Here you would typically:
// 1. Verify the callback signature (if ToyyibPay provides one)
// 2. Check if the payment amount matches your expected amount
// 3. Update your database with the payment status
// 4. Trigger any post-payment actions (send email, generate PDF, etc.)

// For this demo, we'll store the result in a file
// In production, use a proper database

$paymentRecord = [
    'order_id' => $orderId,
    'bill_code' => $billCode,
    'ref_no' => $refNo,
    'transaction_id' => $transactionId,
    'amount' => $amount,
    'status' => $paymentSuccess ? 'success' : 'failed',
    'reason' => $reason,
    'timestamp' => date('Y-m-d H:i:s')
];

$recordsFile = __DIR__ . '/payments.json';
$records = file_exists($recordsFile) ? json_decode(file_get_contents($recordsFile), true) : [];
$records[$orderId] = $paymentRecord;
file_put_contents($recordsFile, json_encode($records, JSON_PRETTY_PRINT));

// Respond to ToyyibPay
http_response_code(200);
echo 'OK';
?>
