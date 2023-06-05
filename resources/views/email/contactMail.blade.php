<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}" />
    <title>Contact us</title>
</head>
<body>

<div class="card" style="width: 18rem;">
  <div class="card-body">
  <h3 class="card-title">User Need you for contacting with you </h3>
    <h5 class="card-title">Name : {{ $datalis['firstname'] }}</h5>
    <h6 class="card-subtitle mb-2 text-muted">Email :{{ $datalis['email'] }}</h6>
    <p class="card-text">Message :{{ $datalis['message'] }}.</p>
  </div>
</div>
</body>
</html>
