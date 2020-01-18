@extends('layouts.app')

@section('content')
    Booking

    <div id="paypal-buttons"></div>
@endsection

@section('scripts-footer')
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.key') }}"></script>
    <script>
        paypal.Buttons({
            createOrder : function (data, actions) {
                return actions.request.post('/paypal/order');
            },
            onApprove : function (data, actions) {
                return actions.order.capture().then(function (details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                    // Call your server to save the transaction
                    return fetch('/paypal-transaction-complete', {
                        method : 'post',
                        headers : {
                            'content-type' : 'application/json'
                        },
                        body : JSON.stringify({
                            orderID : data.orderID
                        })
                    });
                });
            }
        }).render('#paypal-buttons');
    </script>
@endsection