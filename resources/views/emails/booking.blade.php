<h1>New Booking</h1>
<p>
    Booking ID #{{ $booking->id }}<br/>
    Start date: {{$booking->start_date}}<br/>
    End date: {{$booking->end_date}}<br/>
    Price: {{$booking->price}}
</p>
<br/>
<p>
    Customer ID #{{ $booking->customer->id }}<br/>
    Customer name: {{ $booking->customer->name }}<br/>
    Customer email: {{ $booking->customer->email }}<br/>
    Customer phone: {{ $booking->customer->phone }}
</p>
<p>
    Room ID #{{  $booking->room->id }}<br/>
    Room number: {{ $booking->room->number }}<br/>
    Room name: {{ $booking->room->name }}<br/>
    Room price: {{ $booking->room->price }}<br/>
    Room type: {{ $booking->room->type }}
</p>

@if(!empty($booking->payments))
    <h3>Received payment/s:</h3>
    @foreach($booking->payments as $payment)
        <p>
        Payment ID #{{ $payment->id }}<br/>
        Payment date: {{ $payment->payment_date }}<br/>
        Payment amount: {{ $payment->amount }}<br/>
        Payment status: {{ $payment->status }}
        </p>
    @endforeach
@endif
