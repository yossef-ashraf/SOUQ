<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}" />
    <title>OTP </title>
</head>
<body>

<div class="card" style="width: 18rem;">
  <div class="card-body">
  <h3 class="card-title">User Need you for contacting with you </h3>
    <h5 class="card-title">OTP : {{ $datalis['otp'] }}</h5>
  </div>
</div>
</body>
</html>
