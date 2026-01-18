@extends('emails.layout')

@section('content')
    <h2>Check-In Reminder</h2>
    
    <p>Dear {{ $guest->first_name }} {{ $guest->last_name }},</p>
    
    <p>This is a reminder that your check-in is coming up soon!</p>
    
    <table style="width: 100%; border-collapse: collapse;">
        <tr style="background-color: #f5f5f5;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Check-In Date</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $reservation->check_in_date->format('M d, Y \a\t g:i A') }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Room Number</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $reservation->room->room_number }}</td>
        </tr>
        <tr style="background-color: #f5f5f5;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Room Type</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $reservation->room->type->name }}</td>
        </tr>
    </table>
    
    <p style="margin-top: 20px;">Please arrive 30 minutes before your scheduled check-in time. If you have any questions, please contact our front desk.</p>
    
    <p>We're excited to welcome you!</p>
    
    <p>Best regards,<br>{{ config('app.name') }} Team</p>
@endsection
