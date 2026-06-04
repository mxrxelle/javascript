<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Certly Certificate</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f4f6f8; font-family: 'Inter', Arial, sans-serif; color: #1e293b; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background: #fff; }
        .header { background: #002244; padding: 36px 40px; text-align: center; }
        .header-logo { font-size: 36px; font-weight: 800; color: #ffc32b; letter-spacing: -1px; }
        .header-tagline { color: rgba(255,255,255,0.65); font-size: 14px; margin-top: 6px; }
        .hero { background: linear-gradient(135deg, #002244 0%, #003580 100%); padding: 50px 40px; text-align: center; }
        .hero-icon { font-size: 72px; margin-bottom: 16px; display: block; }
        .hero-title { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 10px; }
        .hero-subtitle { font-size: 16px; color: rgba(255,255,255,0.75); }
        .body { padding: 40px; }
        .greeting { font-size: 18px; font-weight: 700; margin-bottom: 16px; color: #0f172a; }
        .message { font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 24px; }
        .cert-box { background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 16px; padding: 28px 32px; margin-bottom: 32px; text-align: center; position: relative; }
        .cert-box::before { content: '🏆'; font-size: 32px; position: absolute; top: -18px; left: 50%; transform: translateX(-50%); }
        .cert-course { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 8px; margin-top: 8px; }
        .cert-title { font-size: 22px; font-weight: 800; color: #002244; margin-bottom: 12px; }
        .cert-uid { font-size: 13px; font-weight: 600; color: #94a3b8; font-family: monospace; background: #e2e8f0; padding: 6px 14px; border-radius: 6px; display: inline-block; }
        .cert-date { font-size: 13px; color: #64748b; margin-top: 10px; }
        .cta-section { text-align: center; margin-bottom: 32px; }
        .cta-text { font-size: 14px; color: #64748b; margin-bottom: 14px; }
        .divider { height: 1px; background: #e2e8f0; margin: 32px 0; }
        .footer { background: #f8fafc; padding: 28px 40px; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer-text { font-size: 13px; color: #94a3b8; line-height: 1.6; }
        .footer-brand { font-weight: 800; color: #002244; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">🎓 Certly</div>
            <div class="header-tagline">Professional Learning & Certification Platform</div>
        </div>

        <!-- Hero -->
        <div class="hero">
            <span class="hero-icon">🏅</span>
            <div class="hero-title">Congratulations, {{ $studentName }}!</div>
            <div class="hero-subtitle">You've successfully completed the final exam and earned your certificate.</div>
        </div>

        <!-- Body -->
        <div class="body">
            <div class="greeting">Hi {{ $studentName }},</div>
            <p class="message">
                We are thrilled to inform you that you have <strong>successfully passed</strong> the final exam 
                for <strong>{{ $courseName }}</strong>. Your hard work and dedication have paid off!
                <br><br>
                Your official <strong>Certly Certificate of Completion</strong> is attached to this email as a PDF. 
                You can download it, print it, or share it with your network.
            </p>

            <!-- Certificate info box -->
            <div class="cert-box">
                <div class="cert-course">Certificate of Completion</div>
                <div class="cert-title">{{ $courseName }}</div>
                <div class="cert-uid">{{ $certificateUid }}</div>
                <div class="cert-date">Issued on {{ $issuedAt }}</div>
            </div>

            <p class="message" style="font-size:14px;">
                📎 Your certificate PDF is attached. Keep it safe — it's your proof of achievement!
            </p>

            <div class="divider"></div>

            <p class="message" style="font-size:14px; color:#64748b;">
                If you have any questions or need assistance, please contact your facilitator or reach out to our support team.
                Thank you for being part of the Certly community!
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                This is an automated email from <span class="footer-brand">Certly</span>.<br>
                Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
