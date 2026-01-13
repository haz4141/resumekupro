<?php
/**
 * ResumeKu Pro - Payment Failed Page
 */

session_start();

$refNo = $_GET['refno'] ?? '';
$statusId = $_GET['status_id'] ?? '';
$reason = $_GET['reason'] ?? $_GET['msg'] ?? 'Pembayaran tidak berjaya';
$orderId = $_SESSION['order_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Gagal - ResumeKu Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .fail-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .fail-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 48px; max-width: 500px; width: 100%; text-align: center; }
        .fail-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
        .fail-icon svg { width: 40px; height: 40px; color: white; }
        .fail-card h1 { font-size: 28px; margin-bottom: 12px; }
        .fail-card .subtitle { color: var(--text-secondary); margin-bottom: 32px; }
        .error-box { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--radius-md); padding: 20px; margin-bottom: 32px; }
        .error-box p { color: #ef4444; font-size: 14px; margin: 0; }
        .help-section { background: rgba(0,0,0,0.2); border-radius: var(--radius-md); padding: 24px; margin-bottom: 32px; text-align: left; }
        .help-section h3 { font-size: 16px; margin-bottom: 16px; }
        .help-section ul { margin: 0; padding-left: 20px; color: var(--text-secondary); font-size: 14px; }
        .help-section li { margin-bottom: 8px; }
        .btn-group { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
    </style>
</head>
<body>
    <div class="fail-page">
        <div class="fail-card">
            <div class="fail-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </div>
            
            <h1>Pembayaran Tidak Berjaya</h1>
            <p class="subtitle">Jangan risau, tiada caj dikenakan. Sila cuba lagi.</p>
            
            <?php if ($reason): ?>
            <div class="error-box">
                <p><strong>Sebab:</strong> <?php echo htmlspecialchars($reason); ?></p>
            </div>
            <?php endif; ?>
            
            <div class="help-section">
                <h3>💡 Tips untuk cuba lagi</h3>
                <ul>
                    <li>Pastikan baki akaun bank mencukupi</li>
                    <li>Cuba kaedah pembayaran lain (FPX, Kad, e-Wallet)</li>
                    <li>Semak sambungan internet anda</li>
                    <li>Jangan tutup browser semasa pembayaran</li>
                    <li>Hubungi bank anda jika masalah berterusan</li>
                </ul>
            </div>
            
            <div class="btn-group">
                <a href="index.php#builder" class="btn btn-primary btn-large">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="1 4 1 10 7 10"/>
                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                    </svg>
                    Cuba Lagi
                </a>
                <a href="mailto:sokongan@resumeku.my" class="btn btn-secondary btn-large">
                    Hubungi Sokongan
                </a>
            </div>
            
            <p style="margin-top: 24px; color: var(--text-muted); font-size: 13px;">
                No. Rujukan: <?php echo htmlspecialchars($refNo ?: $orderId ?: 'N/A'); ?>
            </p>
        </div>
    </div>
</body>
</html>
