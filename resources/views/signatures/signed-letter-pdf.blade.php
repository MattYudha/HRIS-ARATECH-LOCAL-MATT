<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20mm 20mm 15mm 20mm; }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            font-size: 11px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #555;
        }
        .letter-meta {
            margin-bottom: 12px;
        }
        .letter-meta table td {
            padding: 1px 5px 1px 0;
            vertical-align: top;
            border: none;
            font-size: 11px;
        }
        .letter-meta table td:first-child {
            font-weight: bold;
            width: 80px;
        }
        .subject {
            font-weight: bold;
            margin-bottom: 12px;
            text-decoration: underline;
            font-size: 11px;
        }
        .content {
            text-align: justify;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .closing {
            margin-top: 20px;
            text-align: right;
            padding-right: 30px;
        }
        .closing .date {
            margin-bottom: 8px;
        }
        .footer {
            margin-top: 25px;
            font-size: 8px;
            border-top: 1px solid #ccc;
            padding-top: 6px;
            color: #999;
        }
        .footer table td {
            border: none;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $config->company_name ?? 'PT Aratech Indonesia' }}</h1>
        <p>{!! nl2br(e($config->company_address ?? 'Jl. Gatot Subroto No. 1, Jakarta')) !!}</p>
        <p>Telepon: {{ $config->company_phone ?? '-' }} | Email: {{ $config->company_email ?? '-' }}</p>
    </div>

    <div class="letter-meta">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;"></td>
                <td style="text-align: right; font-weight: bold;">
                    Nomor : {{ $letter->letter_number ?? '-' }}
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Tanggal</td>
                <td>: {{ $letter->approved_date ? $letter->approved_date->format('d F Y') : ($letter->created_date ? $letter->created_date->format('d F Y') : now()->format('d F Y')) }}</td>
            </tr>
        </table>
    </div>

    <div class="subject">
        Perihal : {{ $letter->subject }}
    </div>

    <div class="content">
        {!! nl2br($letter->formatted_content) !!}
    </div>

    <div class="closing">
        <div class="date">
            Jakarta, {{ $letter->approved_date ? $letter->approved_date->format('d F Y') : ($letter->created_date ? $letter->created_date->format('d F Y') : now()->format('d F Y')) }}
        </div>
    </div>

    <div class="footer">
        <table style="width: 100%;">
            <tr>
                <td style="width: 70%; vertical-align: bottom; text-align: left; font-size: 8px; color: #777;">
                    @if(!empty($config->letterhead_footer))
                        <div style="margin-bottom: 5px; font-style: italic;">
                            {!! nl2br(e($config->letterhead_footer)) !!}
                        </div>
                    @endif
                    <div style="border-left: 2px solid #ddd; padding-left: 8px;">
                        <strong>Dokumen Digital HRIS</strong><br>
                        Sistem Informasi Sumber Daya Manusia<br>
                        {{ $config->company_name ?? 'PT Aratech Indonesia' }}
                    </div>
                </td>
                <td style="width: 30%; text-align: right; vertical-align: top;">
                    <div style="display: inline-block; text-align: center;">
                        <img
                            alt="QR Verifikasi"
                            src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(80)->margin(0)->errorCorrection('M')->generate($verificationUrl)) }}"
                            width="80" height="80"
                            style="border: 1px solid #eee; padding: 2px;"/>
                        <p style="font-size: 7px; color: #aaa; margin: 3px 0 0 0;">Scan untuk Verifikasi</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
