
<p>Order shipped successfully</p>

<p>Order ID : {{ $order['id'] }}</p>

<p>Order Price : {{ $order['price'] }}</p>

<p>Product : {{ $order['product']['name'] }}</p>

<img src="{{ $message->embed(public_path('static/media/logo.png')) }}" alt="Logo">
