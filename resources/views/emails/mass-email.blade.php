<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $emailSubject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 5px;
        }

        .content {
            margin-bottom: 30px;
        }

        .signature {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="content">
            <h2>Hello {{ $user->name }},</h2>

            <div>
                {!! $emailContent !!}
            </div>
        </div>
        <div class="footer">
            <p>Thank You and Best Regards,<br>
                @php
                $senderName = App\Models\EmailSettings::getValue('name', 'SMKN 1 Manokwari');
                echo $senderName;
                @endphp
            </p>

            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>

</html>