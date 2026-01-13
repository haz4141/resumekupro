<?php
/**
 * ResumeKu Pro - ToyyibPay Integration
 * Creates payment bill and redirects to ToyyibPay
 */

session_start();

// ToyyibPay API Configuration
define('TOYYIBPAY_SECRET_KEY', 'your-secret-key-here'); // Replace with actual key
define('TOYYIBPAY_CATEGORY_CODE', 'your-category-code'); // Replace with actual code
define('TOYYIBPAY_API_URL', 'https://toyyibpay.com/index.php/api/createBill');
define('SITE_URL', 'https://yourdomain.com'); // Replace with actual domain

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Get form data
$orderId = $_POST['order_id'] ?? $_SESSION['order_id'] ?? '';
$amount = floatval($_POST['amount'] ?? 15);
$buyerName = $_POST['buyer_name'] ?? '';
$buyerEmail = $_POST['buyer_email'] ?? '';
$buyerPhone = $_POST['buyer_phone'] ?? '';

// Validate
if (empty($orderId) || empty($buyerName) || empty($buyerEmail) || empty($buyerPhone)) {
    header('Location: checkout.php?error=missing');
    exit;
}

// Store buyer info in session
$_SESSION['buyer_name'] = $buyerName;
$_SESSION['buyer_email'] = $buyerEmail;
$_SESSION['buyer_phone'] = $buyerPhone;

// Prepare bill data for ToyyibPay
$billData = [
    'userSecretKey' => TOYYIBPAY_SECRET_KEY,
    'categoryCode' => TOYYIBPAY_CATEGORY_CODE,
    'billName' => 'Resume Profesional - ResumeKu Pro',
    'billDescription' => 'Resume Profesional PDF dengan template premium',
    'billPriceSetting' => 1, // Fixed price
    'billPayorInfo' => 1, // Required
    'billAmount' => $amount * 100, // In cents
    'billReturnUrl' => SITE_URL . '/success.php',
    'billCallbackUrl' => SITE_URL . '/callback.php',
    'billExternalReferenceNo' => $orderId,
    'billTo' => $buyerName,
    'billEmail' => $buyerEmail,
    'billPhone' => $buyerPhone,
    'billSplitPayment' => 0,
    'billSplitPaymentArgs' => '',
    'billPaymentChannel' => 0, // All channels
    'billContentEmail' => 'Terima kasih kerana menggunakan ResumeKu Pro. Resume profesional anda akan dihantar selepas pembayaran berjaya.',
    'billChargeToCustomer' => 1, // Customer pays fees
    'billExpiryDate' => date('d-m-Y', strtotime('+1 day')),
    'billExpiryDays' => 1
];

// Create bill via API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, TOYYIBPAY_API_URL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($billData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Parse response
$result = json_decode($response, true);

if ($result && isset($result[0]['BillCode'])) {
    $billCode = $result[0]['BillCode'];
    $_SESSION['bill_code'] = $billCode;
    
    // Redirect to ToyyibPay payment page
    header('Location: https://toyyibpay.com/' . $billCode);
    exit;
} else {
    // Error handling - show error page
    $errorMsg = isset($result[0]['msg']) ? $result[0]['msg'] : 'Ralat tidak diketahui';
    ?>
    <!DOCTYPE html>
    <html lang="ms">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ralat Pembayaran - ResumeKu Pro</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <style>
            .error-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
            .error-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 48px; max-width: 500px; width: 100%; text-align: center; }
            .error-icon { font-size: 64px; margin-bottom: 24px; }
            .error-card h1 { font-size: 24px; margin-bottom: 16px; }
            .error-card p { color: var(--text-secondary); margin-bottom: 24px; }
            .error-details { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--radius-md); padding: 16px; margin-bottom: 24px; color: #ef4444; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class="error-page">
            <div class="error-card">
                <div class="error-icon">❌</div>
                <h1>Ralat Pembayaran</h1>
                <p>Maaf, berlaku masalah semasa memproses pembayaran anda.</p>
                <div class="error-details"><?php echo htmlspecialchars($errorMsg); ?></div>
                <a href="index.php" class="btn btn-primary">Cuba Lagi</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
