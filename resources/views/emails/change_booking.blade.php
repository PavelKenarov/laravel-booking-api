<h1>New Booking</h1>
<p>
    Booking ID #{{ $booking->id }}<br/>
    Customer ID: {{$booking->customer_id}}<br/>
    Room ID: {{$booking->room_id}}<br/>
    Start date: {{$booking->start_date}}<br/>
    End date: {{$booking->end_date}}<br/>
    Price: {{$booking->price}}
    Created at: {{$booking->created_at}}<br/>
    Updated at: {{$booking->updated_at}}<br/>
</p>
@if(!empty($changes))
<br/>
<h1>Changes</h1>
<p>
    @foreach($changes as $key => $val)
        {{ $key }} : {{ $val }}<br/>
    @endforeach
</p>
@endif
<br/>
<h1>Old Booking</h1>
<p>
    Booking ID #{{ $oldBooking->id }}<br/>
    Customer ID: {{$oldBooking->customer_id}}<br/>
    Room ID: {{$oldBooking->room_id}}<br/>
    Start date: {{$oldBooking->start_date}}<br/>
    End date: {{$oldBooking->end_date}}<br/>
    Price: {{$oldBooking->price}}
    Created at: {{$oldBooking->created_at}}<br/>
    Updated at: {{$oldBooking->updated_at}}<br/>
</p>

