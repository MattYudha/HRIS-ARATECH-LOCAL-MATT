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
            margin-bottom: 50px;
        }
        .closing .signer {
            border-top: 1px solid #333;
            display: inline-block;
            padding-top: 4px;
            min-width: 160px;
            text-align: center;
        }
        .closing .signer-title {
            font-size: 9px;
            color: #666;
        }
        .footer {
            margin-top: 30px;
            font-size: 8px;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 6px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo e($config->company_name ?? 'PT Aratech Indonesia'); ?></h1>
        <p><?php echo nl2br(e($config->company_address ?? 'Jl. Gatot Subroto No. 1, Jakarta')); ?></p>
        <p>Telepon: <?php echo e($config->company_phone ?? '-'); ?> | Email: <?php echo e($config->company_email ?? '-'); ?></p>
    </div>

    <div class="letter-meta">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;"></td>
                <td style="text-align: right; font-weight: bold;">
                    Nomor : <?php echo e($letter->letter_number ?? '-'); ?>

                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Tanggal</td>
                <td>: <?php echo e($letter->approved_date ? $letter->approved_date->format('d F Y') : ($letter->created_date ? $letter->created_date->format('d F Y') : now()->format('d F Y'))); ?></td>
            </tr>
        </table>
    </div>

    <div class="subject">
        Perihal : <?php echo e($letter->subject); ?>

    </div>

    <div class="content">
        <?php echo nl2br($letter->formatted_content); ?>

    </div>

    <div class="closing">
        <div class="date">
            Jakarta, <?php echo e($letter->approved_date ? $letter->approved_date->format('d F Y') : ($letter->created_date ? $letter->created_date->format('d F Y') : now()->format('d F Y'))); ?>

        </div>
        <div class="signer">
            <strong><?php echo e($letter->approver?->name ?? 'HR Manager'); ?></strong><br>
            <span class="signer-title"><?php echo e($letter->approver?->employee?->role?->title ?? 'Human Resources'); ?></span>
        </div>
    </div>

    <div class="footer">
        <?php if(!empty($config->letterhead_footer)): ?>
            <div style="margin-bottom: 5px; font-style: italic;">
                <?php echo nl2br(e($config->letterhead_footer)); ?>

            </div>
        <?php endif; ?>
        <div>
            Dicetak dari Sistem HRIS <?php echo e($config->company_name ?? 'PT Aratech Indonesia'); ?> |
            Waktu: <?php echo e(now()->format('d F Y H:i')); ?>

        </div>
    </div>
</body>
</html>
<?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/letters/pdf.blade.php ENDPATH**/ ?>