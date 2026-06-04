<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 297mm;
            height: 210mm;
            font-family: Georgia, 'Times New Roman', serif;
            background: #fff;
            overflow: hidden;
        }

        .certificate-page {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #fff;
        }

        /* Decorative outer border */
        .outer-border {
            position: absolute;
            inset: 10mm;
            border: 3px solid #002244;
        }

        /* Inner decorative border */
        .inner-border {
            position: absolute;
            inset: 13mm;
            border: 1px solid #b8860b;
        }

        /* Gold corner ornaments */
        .corner {
            position: absolute;
            width: 20mm;
            height: 20mm;
            font-size: 24pt;
            color: #b8860b;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .corner.tl { top: 10mm; left: 10mm; }
        .corner.tr { top: 10mm; right: 10mm; }
        .corner.bl { bottom: 10mm; left: 10mm; }
        .corner.br { bottom: 10mm; right: 10mm; }

        /* Content Area */
        .content {
            position: absolute;
            inset: 14mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 6mm 22mm;
        }

        /* Logo + Brand row */
        .brand-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4mm;
            margin-bottom: 1mm;
        }

        .brand-logo {
            width: 12mm;
            height: 12mm;
            object-fit: contain;
        }

        .brand-name {
            font-family: Arial, sans-serif;
            font-size: 16pt;
            font-weight: 900;
            color: #002244;
            letter-spacing: 0.25em;
            text-transform: uppercase;
        }

        /* Top decorative line */
        .top-line {
            width: 80mm;
            height: 2px;
            background: linear-gradient(to right, transparent, #b8860b, transparent);
            margin: 3mm auto;
        }

        .cert-heading {
            font-size: 9pt;
            letter-spacing: 0.4em;
            text-transform: uppercase;
            color: #64748b;
            font-family: Arial, sans-serif;
            font-weight: 400;
            margin-bottom: 2mm;
        }

        .cert-title {
            font-size: 28pt;
            font-weight: 700;
            color: #002244;
            margin-bottom: 2mm;
            font-family: Georgia, serif;
        }

        .of-completion {
            font-size: 14pt;
            color: #475569;
            font-style: italic;
            margin-bottom: 5mm;
        }

        .presents-to {
            font-size: 9pt;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: #94a3b8;
            font-family: Arial, sans-serif;
            margin-bottom: 3mm;
        }

        .student-name {
            font-size: 30pt;
            font-weight: 700;
            color: #002244;
            font-family: Georgia, serif;
            margin-bottom: 2mm;
            border-bottom: 2px solid #b8860b;
            padding-bottom: 3mm;
        }

        .completed-text {
            font-size: 10pt;
            color: #475569;
            font-family: Arial, sans-serif;
            margin-top: 4mm;
            margin-bottom: 2mm;
        }

        .course-title {
            font-size: 15pt;
            font-weight: 700;
            color: #002244;
            font-style: italic;
            margin-bottom: 6mm;
        }

        /* Bottom details row */
        .details-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 100%;
            margin-top: 5mm;
        }

        .detail-block {
            text-align: center;
            flex: 1;
        }

        .detail-label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #94a3b8;
            font-family: Arial, sans-serif;
            margin-bottom: 1mm;
        }

        .detail-line {
            width: 40mm;
            height: 1px;
            background: #002244;
            margin: 0 auto 1mm;
        }

        .detail-value {
            font-size: 9pt;
            color: #1e293b;
            font-family: Arial, sans-serif;
            font-weight: 600;
        }

        .detail-subvalue {
            font-size: 7pt;
            color: #64748b;
            font-family: Arial, sans-serif;
        }

        /* UID watermark */
        .uid-badge {
            position: absolute;
            bottom: 16mm;
            left: 50%;
            transform: translateX(-50%);
            font-size: 7pt;
            color: #94a3b8;
            font-family: Arial, sans-serif;
            letter-spacing: 0.15em;
        }

        /* Background watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 60pt;
            color: rgba(0, 34, 68, 0.025);
            font-family: Georgia, serif;
            font-weight: 700;
            white-space: nowrap;
            pointer-events: none;
        }

        /* Gold seal */
        .seal {
            position: absolute;
            bottom: 22mm;
            right: 22mm;
            width: 30mm;
            height: 30mm;
            border-radius: 50%;
            background: #002244;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #b8860b;
            flex-direction: column;
            gap: 1mm;
        }

        .seal-logo {
            width: 10mm;
            height: 10mm;
            object-fit: contain;
            border-radius: 50%;
            background: white;
            padding: 1mm;
        }

        .seal-text {
            color: #ffc32b;
            font-size: 5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-family: Arial, sans-serif;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="certificate-page">
    <!-- Watermark -->
    <div class="watermark">CERTLY</div>

    <!-- Borders -->
    <div class="outer-border"></div>
    <div class="inner-border"></div>

    <!-- Corners -->
    <div class="corner tl">✦</div>
    <div class="corner tr">✦</div>
    <div class="corner bl">✦</div>
    <div class="corner br">✦</div>

    <!-- Main Content -->
    <div class="content">

        <!-- Logo + Brand Name -->
        <div class="brand-row">
            <img class="brand-logo"
                 src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/certly-logo.png'))) }}"
                 alt="Certly Logo">
            <span class="brand-name">Certly</span>
        </div>

        <div class="top-line"></div>

        <div class="cert-heading">This certificate is proudly awarded to</div>

        <div class="cert-title">Certificate</div>
        <div class="of-completion">of Completion</div>

        <div class="presents-to">Presented To</div>

        <div class="student-name">{{ $student->name }}</div>

        <div class="completed-text">
            For successfully completing the course and passing the Final Examination of
        </div>

        <div class="course-title">{{ $course->title }}</div>

        <!-- Details Row -->
        <div class="details-row">
            <div class="detail-block">
                <div class="detail-label">Issued On</div>
                <div class="detail-line"></div>
                <div class="detail-value">{{ $date }}</div>
            </div>

            <div class="detail-block">
                <div class="detail-label">Authorized By</div>
                <div class="detail-line"></div>
                <div class="detail-value">Certly Platform</div>
                <div class="detail-subvalue">Certification Authority</div>
            </div>

            <div class="detail-block">
                <div class="detail-label">Certificate ID</div>
                <div class="detail-line"></div>
                <div class="detail-value" style="font-size:7.5pt; color:#002244;">{{ $certificate->certificate_uid }}</div>
            </div>
        </div>
    </div>

    <!-- Seal with Logo -->
    <div class="seal">
        <img class="seal-logo"
             src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/certly-logo.png'))) }}"
             alt="Certly">
        <div class="seal-text">CERTLY<br>VERIFIED</div>
    </div>

    <!-- Bottom UID -->
    <div class="uid-badge">Certificate ID: {{ $certificate->certificate_uid }}</div>
</div>

</body>
</html>
