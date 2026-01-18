@extends('emails.layout')

@section('content')
    <h2>Your Invoice</h2>
    
    <p>Dear {{ $guest->first_name }} {{ $guest->last_name }},</p>
    
    <p>Please find your invoice details below:</p>
    
    <table style="width: 100%; border-collapse: collapse;">
        <tr style="background-color: #f5f5f5;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Invoice Number</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $invoice->invoice_number }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Issue Date</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $invoice->issue_date->format('M d, Y') }}</td>
        </tr>
        <tr style="background-color: #f5f5f5;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Due Date</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $invoice->due_date->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Subtotal</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">${{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr style="background-color: #f5f5f5;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Tax ({{ $invoice->tax_percentage }}%)</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">${{ number_format($invoice->tax_amount, 2) }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Total</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>${{ number_format($invoice->total, 2) }}</strong></td>
        </tr>
    </table>
    
    <p style="margin-top: 20px;">Payment Status: <strong>{{ ucfirst($invoice->status) }}</strong></p>
    
    <p>Thank you for your business!</p>
    
    <p>Best regards,<br>{{ config('app.name') }} Team</p>
@endsection
