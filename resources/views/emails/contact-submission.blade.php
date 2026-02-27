<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #000;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #555;
        }
        .field-value {
            margin-top: 5px;
            padding: 10px;
            background-color: #fff;
            border-left: 3px solid #000;
        }
        .message-box {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
        }
        .metadata {
            font-size: 11px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Form Submission</h1>
    </div>
    
    <div class="content">
        <p>You have received a new contact form submission from the Tarqumi website.</p>
        
        <div class="field">
            <div class="field-label">Name:</div>
            <div class="field-value">{{ $submission->name }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Email:</div>
            <div class="field-value">
                <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
            </div>
        </div>
        
        @if($submission->phone)
        <div class="field">
            <div class="field-label">Phone:</div>
            <div class="field-value">{{ $submission->phone }}</div>
        </div>
        @endif
        
        @if($submission->subject)
        <div class="field">
            <div class="field-label">Subject:</div>
            <div class="field-value">{{ $submission->subject }}</div>
        </div>
        @endif
        
        <div class="field">
            <div class="field-label">Message:</div>
            <div class="field-value message-box">{{ $submission->message }}</div>
        </div>
        
        <div class="metadata">
            <strong>Submission Details:</strong><br>
            Date/Time: {{ $submission->created_at->format('Y-m-d H:i:s') }}<br>
            Language: {{ strtoupper($submission->language) }}<br>
            IP Address: {{ $submission->ip_address }}<br>
            Submission ID: #{{ $submission->id }}
        </div>
    </div>
    
    <div class="footer">
        <p>To reply to this inquiry, please respond directly to <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></p>
        <p>To view all submissions, visit your <a href="{{ env('FRONTEND_URL') }}/admin/contact">Admin Panel</a></p>
        <p style="margin-top: 20px; font-size: 10px;">This is an automated message from the Tarqumi CRM system.</p>
    </div>
</body>
</html>
