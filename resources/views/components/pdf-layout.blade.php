<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Document' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1a202c; background: #fff; }
        .page { padding: 30px 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; border-bottom: 3px solid #1e3a5f; padding-bottom: 20px; }
        .header-agency { flex: 1; }
        .agency-name { font-size: 20px; font-weight: bold; color: #1e3a5f; }
        .agency-info { font-size: 10px; color: #666; margin-top: 6px; line-height: 1.6; }
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 16px; font-weight: bold; color: #1e3a5f; }
        .doc-title .doc-number { font-size: 11px; color: #666; margin-top: 4px; }
        .doc-title .doc-date { font-size: 10px; color: #999; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 11px; }
        th { background: #1e3a5f; color: white; padding: 8px 10px; text-align: left; font-weight: 600; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) td { background: #f9fafb; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background: #f3f4f6; border-top: 2px solid #1e3a5f; }
        .footer { margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 15px; font-size: 9px; color: #999; text-align: center; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .info-block { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px 16px; margin: 12px 0; }
        .info-row { display: flex; margin-bottom: 4px; }
        .info-label { font-weight: 600; width: 140px; color: #4b5563; font-size: 11px; }
        .info-value { flex: 1; font-size: 11px; }
        .amount { font-size: 18px; font-weight: bold; color: #1e3a5f; }
        .signature-block { margin-top: 50px; display: flex; justify-content: space-between; }
        .signature-item { text-align: center; width: 200px; }
        .signature-line { border-top: 1px solid #000; margin-top: 40px; padding-top: 5px; font-size: 10px; }
        .watermark-paid { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 60px; color: rgba(34, 197, 94, 0.1); font-weight: bold; pointer-events: none; }
    </style>
</head>
<body>
    <div class="page">
        {{ $slot }}
    </div>
</body>
</html>
