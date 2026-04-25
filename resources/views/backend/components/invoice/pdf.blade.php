@php
    $isPreview = $isPreview ?? false;
    $logoPath = public_path('frontend/assets/images/image.png');
    $logoSrc = null;

    if (file_exists($logoPath)) {
        $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoSrc = 'data:image/' . $logoType . ';base64,' . $logoData;
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #202225;
            background: #f4f4f4;
        }

        .page {
            position: relative;
            width: 100%;
            min-height: 100vh;
            background: #ffffff;
            overflow: hidden;
            padding: 56px 64px 220px;
        }

        .topbar {
            width: 100%;
            margin-bottom: 34px;
        }

        .logo-box {
            float: left;
            width: 42%;
        }

        .logo-box img {
            max-width: 200px;
            max-height: 72px;
        }

        .logo-text {
            font-size: 22px;
            line-height: 1.15;
            letter-spacing: 0.08em;
        }

        .invoice-meta {
            float: right;
            width: 42%;
            text-align: right;
            font-size: 17px;
            letter-spacing: 0.08em;
            padding-top: 10px;
        }

        .clearfix {
            clear: both;
        }

        .headline {
            font-size: 84px;
            font-weight: 900;
            line-height: 0.95;
            letter-spacing: 0.01em;
            margin: 26px 0 22px;
        }

        .date-line {
            font-size: 18px;
            margin-bottom: 34px;
        }

        .date-line strong {
            font-weight: 800;
            margin-right: 8px;
        }

        .party-row {
            margin-bottom: 34px;
        }

        .party-box {
            width: 46%;
            float: left;
        }

        .party-box.right {
            float: right;
        }

        .party-title {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .party-text {
            font-size: 15px;
            line-height: 1.55;
            color: #32363d;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .invoice-table thead th {
            background: #ececef;
            color: #22252a;
            font-size: 14px;
            font-weight: 500;
            padding: 14px 18px;
            text-align: left;
        }

        .invoice-table thead th.right,
        .invoice-table tbody td.right {
            text-align: right;
        }

        .invoice-table tbody td {
            padding: 14px 18px;
            font-size: 15px;
            border-bottom: 1px solid #ececef;
        }

        .totals-box {
            width: 36%;
            margin-left: auto;
            border-top: 1px solid #e5e5e8;
            border-bottom: 1px solid #e5e5e8;
            padding: 14px 0;
        }

        .total-row {
            margin-bottom: 8px;
            font-size: 15px;
        }

        .total-row:last-child {
            margin-bottom: 0;
            font-weight: 800;
            font-size: 17px;
        }

        .total-label {
            float: left;
            width: 55%;
            text-align: right;
            padding-right: 14px;
        }

        .total-value {
            float: right;
            width: 45%;
            text-align: right;
        }

        .footer-meta {
            margin-top: 42px;
            font-size: 16px;
            line-height: 1.8;
        }

        .footer-meta strong {
            font-weight: 800;
            margin-right: 8px;
        }

        .footer-panel {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 170px;
            background: linear-gradient(135deg, #0f7f59 0%, #1bd886 55%, #0ea86d 100%);
        }

        .footer-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(255,255,255,0.04), rgba(255,255,255,0));
        }

        .footer-content {
            position: absolute;
            left: 64px;
            right: 64px;
            bottom: 38px;
            z-index: 4;
            color: #ffffff;
        }

        .footer-grid {
            width: 100%;
        }

        .footer-col {
            width: 48%;
            float: left;
        }

        .footer-col.right {
            float: right;
            text-align: right;
        }

        .footer-label {
            font-size: 12px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.78);
            margin-bottom: 10px;
        }

        .footer-value {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.55;
        }

        .preview-wrap {
            max-width: 960px;
            margin: 32px auto;
            box-shadow: 0 18px 60px rgba(0, 0, 0, 0.12);
        }
    </style>
</head>
<body>
    <div class="{{ $isPreview ? 'preview-wrap' : '' }}">
        <div class="page">
            <div class="topbar">
                <div class="logo-box">
                    @if($logoSrc)
                        <img src="{{ $logoSrc }}" alt="Logo">
                    @else
                        <div class="logo-text">YOUR<br>LOGO</div>
                    @endif
                </div>

                <div class="invoice-meta">
                    NO. {{ $invoice->invoice_number }}
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="headline">INVOICE</div>

            <div class="date-line">
                <strong>Date:</strong> {{ optional($invoice->issue_date)->format('d F, Y') }}
            </div>

            <div class="party-row">
                <div class="party-box">
                    <div class="party-title">Billed to:</div>
                    <div class="party-text">
                        {{ $invoice->client_name }}<br>
                        {{ $invoice->client_address ?: 'No address provided' }}<br>
                        {{ $invoice->client_email ?: ($invoice->client_phone ?: '-') }}
                    </div>
                </div>

                <div class="party-box right">
                    <div class="party-title">From:</div>
                    <div class="party-text">
                        {{ config('app.name', 'Portfolio Admin') }}<br>
                        {{ $settings?->address ?: 'No address added' }}<br>
                        {{ $settings?->email ?: ($settings?->phone ?: '-') }}
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="right">Quantity</th>
                        <th class="right">Price</th>
                        <th class="right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(($invoice->items ?? []) as $item)
                        <tr>
                            <td>{{ $item['description'] ?? '-' }}</td>
                            <td class="right">{{ number_format((float) ($item['qty'] ?? 0), 0) }}</td>
                            <td class="right">${{ number_format((float) ($item['rate'] ?? 0), 2) }}</td>
                            <td class="right">${{ number_format((float) ($item['amount'] ?? 0), 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals-box">
                <div class="total-row">
                    <div class="total-label">Subtotal</div>
                    <div class="total-value">${{ number_format((float) $invoice->subtotal, 2) }}</div>
                    <div class="clearfix"></div>
                </div>
                <div class="total-row">
                    <div class="total-label">Discount</div>
                    <div class="total-value">${{ number_format((float) $invoice->discount, 2) }}</div>
                    <div class="clearfix"></div>
                </div>
                <div class="total-row">
                    <div class="total-label">Total</div>
                    <div class="total-value">${{ number_format((float) $invoice->total, 2) }}</div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="footer-meta">
                <div><strong>Status:</strong> {{ ucfirst($invoice->status) }}</div>
                @if($invoice->due_date)
                    <div><strong>Due Date:</strong> {{ optional($invoice->due_date)->format('d F, Y') }}</div>
                @endif
                @if($invoice->notes)
                    <div><strong>Note:</strong> {{ $invoice->notes }}</div>
                @endif
            </div>

            <div class="footer-panel"></div>

            <div class="footer-content">
                <div class="footer-grid">
                    <div class="footer-col">
                        <div class="footer-label">Phone</div>
                        <div class="footer-value">
                            01721413821
                        </div>
                    </div>

                    <div class="footer-col right">
                        <div class="footer-label">Email</div>
                        <div class="footer-value">
                            clickzabd@gmail.com
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</body>
</html>
