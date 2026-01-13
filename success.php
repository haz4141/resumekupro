<?php
/**
 * ResumeKu Pro - Payment Success Page
 */

session_start();

// Get payment reference from ToyyibPay redirect
$refNo = $_GET['refno'] ?? $_GET['billcode'] ?? '';
$statusId = $_GET['status_id'] ?? $_GET['status'] ?? '';
$billCode = $_GET['billcode'] ?? '';
$orderId = $_GET['order_id'] ?? $_SESSION['order_id'] ?? '';

// Verify payment status
$paymentSuccess = ($statusId == '1');

// Get resume data from session
$resumeData = isset($_SESSION['resume_data']) ? json_decode($_SESSION['resume_data'], true) : null;
$buyerEmail = $_SESSION['buyer_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berjaya - ResumeKu Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .success-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; background: var(--bg-dark); }
        .success-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 48px; max-width: 600px; width: 100%; text-align: center; }
        .success-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; animation: bounceIn 0.6s ease; }
        .success-icon svg { width: 40px; height: 40px; color: white; }
        .success-card h1 { font-size: 28px; margin-bottom: 12px; color: var(--text-primary); }
        .success-card .subtitle { color: var(--text-secondary); margin-bottom: 32px; font-size: 16px; }
        .order-details { background: rgba(0,0,0,0.2); border-radius: var(--radius-md); padding: 24px; margin-bottom: 32px; text-align: left; }
        .order-details h3 { font-size: 14px; color: var(--text-muted); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-color); color: var(--text-secondary); }
        .detail-row:last-child { border-bottom: none; }
        .detail-row span:last-child { color: var(--text-primary); font-weight: 500; }
        .download-section { margin-bottom: 32px; }
        .download-section h3 { font-size: 18px; margin-bottom: 16px; }
        .next-steps { background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: var(--radius-md); padding: 20px; text-align: left; margin-bottom: 24px; }
        .next-steps h4 { color: var(--primary-light); margin-bottom: 12px; font-size: 14px; }
        .next-steps ul { margin: 0; padding-left: 20px; color: var(--text-secondary); font-size: 14px; }
        .next-steps li { margin-bottom: 8px; }
        @keyframes bounceIn { 0% { transform: scale(0); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
        .confetti { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; overflow: hidden; z-index: 1000; }
        .confetti-piece { position: absolute; width: 10px; height: 20px; top: -20px; animation: confettiFall 3s ease-out forwards; }
        @keyframes confettiFall { to { transform: translateY(100vh) rotate(720deg); opacity: 0; } }
    </style>
</head>
<body>
    <div class="confetti" id="confetti"></div>
    
    <div class="success-page">
        <div class="success-card">
            <div class="success-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            
            <h1>Pembayaran Berjaya! 🎉</h1>
            <p class="subtitle">Terima kasih! Resume profesional anda sudah sedia untuk dimuat turun.</p>
            
            <div class="order-details">
                <h3>Butiran Pesanan</h3>
                <div class="detail-row">
                    <span>No. Pesanan</span>
                    <span><?php echo htmlspecialchars($orderId); ?></span>
                </div>
                <div class="detail-row">
                    <span>No. Rujukan</span>
                    <span><?php echo htmlspecialchars($refNo); ?></span>
                </div>
                <div class="detail-row">
                    <span>Produk</span>
                    <span>Resume Profesional (PDF)</span>
                </div>
                <div class="detail-row">
                    <span>Jumlah Dibayar</span>
                    <span>RM 15.00</span>
                </div>
                <div class="detail-row">
                    <span>Status</span>
                    <span style="color: #10b981;">✓ Berjaya</span>
                </div>
            </div>
            
            <div class="download-section">
                <h3>Muat Turun Resume Anda</h3>
                <button id="downloadPdf" class="btn btn-primary btn-large">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Muat Turun PDF
                </button>
            </div>
            
            <div class="next-steps">
                <h4>📋 Langkah Seterusnya</h4>
                <ul>
                    <li>Semak resume anda dan pastikan semua maklumat betul</li>
                    <li>Muat naik ke portal kerja seperti JobStreet, LinkedIn, atau WOBB</li>
                    <li>Hantar terus kepada majikan melalui email</li>
                    <li>Simpan salinan di Google Drive atau iCloud untuk akses mudah</li>
                </ul>
            </div>
            
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">
                Resit anda telah dihantar ke <strong><?php echo htmlspecialchars($buyerEmail); ?></strong>
            </p>
            
            <a href="index.php" class="btn btn-secondary">← Kembali ke Laman Utama</a>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        // Resume data from session
        const resumeData = <?php echo $resumeData ? json_encode($resumeData) : 'null'; ?>;
        
        // Confetti animation
        function createConfetti() {
            const container = document.getElementById('confetti');
            const colors = ['#6366f1', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#ec4899'];
            
            for (let i = 0; i < 100; i++) {
                const piece = document.createElement('div');
                piece.className = 'confetti-piece';
                piece.style.left = Math.random() * 100 + '%';
                piece.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                piece.style.animationDelay = Math.random() * 2 + 's';
                piece.style.animationDuration = (Math.random() * 2 + 2) + 's';
                container.appendChild(piece);
            }
            
            setTimeout(() => container.innerHTML = '', 5000);
        }
        createConfetti();
        
        // Download PDF
        document.getElementById('downloadPdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            if (!resumeData) {
                alert('Data resume tidak ditemui. Sila hubungi sokongan.');
                return;
            }
            
            const fullName = resumeData.fullName || 'Nama Anda';
            const jobTitle = resumeData.jobTitle || '';
            const email = resumeData.email || '';
            const phone = resumeData.phone || '';
            const location = resumeData.location || '';
            const summary = resumeData.summary || '';
            const skills = resumeData.skills || '';
            const languages = resumeData.languages || '';
            
            let y = 20;
            
            // Header
            doc.setFontSize(24);
            doc.setFont('helvetica', 'bold');
            doc.text(fullName, 105, y, { align: 'center' });
            y += 8;
            
            doc.setFontSize(12);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(100);
            doc.text(jobTitle, 105, y, { align: 'center' });
            y += 8;
            
            // Contact
            const contact = [email, phone, location].filter(Boolean).join(' | ');
            doc.setFontSize(10);
            doc.text(contact, 105, y, { align: 'center' });
            y += 12;
            
            // Line
            doc.setDrawColor(99, 102, 241);
            doc.setLineWidth(0.5);
            doc.line(20, y, 190, y);
            y += 10;
            
            doc.setTextColor(0);
            
            // Summary
            if (summary) {
                doc.setFontSize(12);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(99, 102, 241);
                doc.text('RINGKASAN PROFESIONAL', 20, y);
                y += 7;
                doc.setFont('helvetica', 'normal');
                doc.setTextColor(0);
                doc.setFontSize(10);
                const lines = doc.splitTextToSize(summary, 170);
                doc.text(lines, 20, y);
                y += lines.length * 5 + 8;
            }
            
            // Skills
            if (skills) {
                doc.setFontSize(12);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(99, 102, 241);
                doc.text('KEMAHIRAN', 20, y);
                y += 7;
                doc.setFont('helvetica', 'normal');
                doc.setTextColor(0);
                doc.setFontSize(10);
                const lines = doc.splitTextToSize(skills, 170);
                doc.text(lines, 20, y);
                y += lines.length * 5 + 8;
            }
            
            // Languages
            if (languages) {
                doc.setFontSize(12);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(99, 102, 241);
                doc.text('BAHASA', 20, y);
                y += 7;
                doc.setFont('helvetica', 'normal');
                doc.setTextColor(0);
                doc.setFontSize(10);
                doc.text(languages, 20, y);
            }
            
            // Save
            doc.save('Resume_' + fullName.replace(/\s+/g, '_') + '.pdf');
        });
    </script>
</body>
</html>
