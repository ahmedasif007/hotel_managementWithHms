@extends('emails.layout')

@section('content')
    <h2>Reservation Confirmed</h2>
    
    <p>Dear {{ $guest->first_name }} {{ $guest->last_name }},</p>
    
    <p>Your reservation has been confirmed. Here are the details:</p>
    
    <table style="width: 100%; border-collapse: collapse;">
        <tr style="background-color: #f5f5f5;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Room Type</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $room->type->name }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Room Number</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $room->room_number }}</td>
        </tr>
        <tr style="background-color: #f5f5f5;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Check-In Date</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $reservation->check_in_date->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Check-Out Date</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $reservation->check_out_date->format('M d, Y') }}</td>
        </tr>
        <tr style="background-color: #f5f5f5;">
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Number of Nights</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $reservation->number_of_nights }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;"><strong>Price per Night</strong></td>
            <td style="padding: 10px; border: 1px solid #ddd;">${{ number_format($room->price_per_night, 2) }}</td>
        </tr>
    </table>
    
    <p style="margin-top: 20px;">We look forward to your stay!</p>
    
    <p>Best regards,<br>{{ config('app.name') }} Team</p>
@endsection
