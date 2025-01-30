<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2d3748;
            margin-bottom: 5px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
            color: #666;
        }
        .receipt-details {
            margin-bottom: 30px;
        }
        .receipt-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .receipt-details td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .receipt-details td:first-child {
            font-weight: bold;
            width: 150px;
        }
        .amount {
            font-size: 24px;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #f7fafc;
            border-radius: 4px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .expense {
            color: #e53e3e;
        }
        .income {
            color: #38a169;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Transaction Receipt</h1>
            <div>Receipt #{{ $transaction->transaction_id }}</div>
        </div>

        <div class="company-info">
            <div>Your Bank Name</div>
            <div>123 Banking Street</div>
            <div>Banking City, BC 12345</div>
        </div>

        <div class="amount {{ $transaction->amount < 0 ? 'expense' : 'income' }}">
            {{ $amount }}
        </div>

        <div class="receipt-details">
            <table>
                <tr>
                    <td>Date</td>
                    <td>{{ $date }}</td>
                </tr>
                <tr>
                    <td>Transaction Type</td>
                    <td>{{ $transaction->type }}</td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>{{ $type }}</td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>{{ $transaction->description }}</td>
                </tr>
                <tr>
                    <td>Card</td>
                    <td>**** **** **** {{ $transaction->card_last_four }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for banking with us!</p>
            <p>This is an automatically generated receipt.</p>
        </div>
    </div>
</body>
</html>
