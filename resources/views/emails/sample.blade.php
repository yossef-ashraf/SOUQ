<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* لون الخلفية */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff; /* لون الخلفية الداخلية */
            border: 1px solid #ccc;
            padding: 30px; /* تباعد العناصر الداخلية */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
            border-radius: 10px;
        }

        h1 {
            font-size: 32px; /* حجم العنوان الرئيسي */
            margin-bottom: 20px;
            color: #333; /* لون العنوان */
        }

        h2 {
            font-weight: bold;
            font-size: 20px; /* حجم العنوان الثانوي */
            margin-bottom: 10px;
            color: #555; /* لون العنوان */
        }

        p {
            margin: 0;
            padding: 5px;
            color: #777; /* لون النص الفاتح */
        }

        .info {
            margin-bottom: 20px; /* تباعد بين العناصر */
        }

        .btn {
            display: inline-block;
            background-color: #ff6600; /* لون الزر */
            color: #fff;
            padding: 10px 20px; /* حجم الزر */
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 18px; /* حجم الزر */
        }

        /* استجابة لأجهزة الشاشة الصغيرة (مثل الهواتف المحمولة) */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 18px;
            }

            .btn {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Email Information</h1>
        <div class="info">
            <h2>User Name:</h2>
            <p>{{ $content['firstname'] }}  {{ $content['lastname'] }}</p>
        </div>
        <div class="info">
            <h2>Message :</h2>
            <p>{{ $content['message'] }}</p>
        </div>
    </div>
</body>
</html>
