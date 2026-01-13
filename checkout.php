<?php
/**
 * ResumeKu Pro - Checkout Page
 * Collects order info and redirects to payment
 */

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$product = $_POST['product'] ?? 'resume';
$amount = floatval($_POST['amount'] ?? 15);
$resumeData = $_POST['resume_data'] ?? '';

// Validate
if (empty($resumeData)) {
    header('Location: index.php?error=nodata');
    exit;
}

// Store in session
$_SESSION['resume_data'] = $resumeData;
$_SESSION['amount'] = $amount;
$_SESSION['order_id'] = 'RKP-' . time() . '-' . rand(1000, 9999);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ResumeKu Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .checkout-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .checkout-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 48px; max-width: 500px; width: 100%; }
        .checkout-header { text-align: center; margin-bottom: 32px; }
        .checkout-header h1 { font-size: 28px; margin-bottom: 8px; }
        .checkout-header p { color: var(--text-secondary); }
        .order-summary { background: rgba(0,0,0,0.2); border-radius: var(--radius-md); padding: 24px; margin-bottom: 32px; }
        .order-item { display: flex; justify-content: space-between; margin-bottom: 12px; color: var(--text-secondary); }
        .order-item:last-child { margin-bottom: 0; padding-top: 12px; border-top: 1px solid var(--border-color); font-weight: 600; color: var(--text-primary); font-size: 18px; }
        .checkout-form .form-group { margin-bottom: 20px; }
        .checkout-form label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; color: var(--text-secondary); }
        .checkout-form input { width: 100%; padding: 14px 16px; background: var(--bg-glass); border: 1px solid var(--border-color); border-radius: var(--radius-md); color: var(--text-primary); font-size: 15px; }
        .checkout-form input:focus { outline: none; border-color: var(--primary); }
        .security-note { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); margin-top: 16px; }
        .back-link { display: block; text-align: center; margin-top: 24px; color: var(--text-secondary); font-size: 14px; }
    </style>
</head>
<body>
    <div class="checkout-page">
        <div class="checkout-card">
            <div class="checkout-header">
                <div class="logo" style="justify-content: center; margin-bottom: 16px;">
                    <span class="logo-icon">📄</span>
                    <span class="logo-text">ResumeKu<span class="pro">Pro</span></span>
                </div>
                <h1>Sahkan Pesanan</h1>
                <p>Lengkapkan maklumat untuk pembayaran</p>
            </div>
            
            <div class="order-summary">
                <div class="order-item">
                    <span>Resume Profesional (PDF)</span>
                    <span>1x</span>
                </div>
                <div class="order-item">
                    <span>Template Premium</span>
                    <span>✓</span>
                </div>
                <div class="order-item">
                    <span>Format ATS-Friendly</span>
                    <span>✓</span>
                </div>
                <div class="order-item">
                    <span>Jumlah</span>
                    <span>RM <?php echo number_format($amount, 2); ?></span>
                </div>
            </div>
            
            <form action="toyyibpay.php" method="POST" class="checkout-form">
                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($_SESSION['order_id']); ?>">
                <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                
                <div class="form-group">
                    <label for="buyer_name">Nama Penuh</label>
                    <input type="text" id="buyer_name" name="buyer_name" required placeholder="Nama seperti dalam IC">
                </div>
                
                <div class="form-group">
                    <label for="buyer_email">Alamat Email</label>
                    <input type="email" id="buyer_email" name="buyer_email" required placeholder="email@contoh.com">
                </div>
                
                <div class="form-group">
                    <label for="buyer_phone">No. Telefon</label>
                    <input type="tel" id="buyer_phone" name="buyer_phone" required placeholder="0123456789">
                </div>
                
                <button type="submit" class="btn btn-primary btn-large btn-full">
                    Teruskan ke Pembayaran
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
                
                <div class="security-note">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Pembayaran selamat melalui ToyyibPay
                </div>
            </form>
            
            <a href="index.php" class="back-link">← Kembali ke laman utama</a>
        </div>
    </div>
</body>
</html>
