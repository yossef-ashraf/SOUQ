<?php
return [
    #PAYMOB
    'PAYMOB_API_KEY' => env('PAYMOB_API_KEY'),
    'PAYMOB_INTEGRATION_ID' => env('PAYMOB_INTEGRATION_ID'),
    'PAYMOB_IFRAME_ID' => env('PAYMOB_IFRAME_ID'),
    'PAYMOB_HMAC' => env('PAYMOB_HMAC'),
    'PAYMOB_CURRENCY'=> env('PAYMOB_CURRENCY',"EGP"),

    #HYPERPAY
    'HYPERPAY_BASE_URL' => env('HYPERPAY_BASE_URL', "https://eu-test.oppwa.com"),
    'HYPERPAY_URL' => env('HYPERPAY_URL', env('HYPERPAY_BASE_URL') . "/v1/checkouts"),
    'HYPERPAY_TOKEN' => env('HYPERPAY_TOKEN'),
    'HYPERPAY_CREDIT_ID' => env('HYPERPAY_CREDIT_ID'),
    'HYPERPAY_MADA_ID' => env('HYPERPAY_MADA_ID'),
    'HYPERPAY_APPLE_ID' => env('HYPERPAY_APPLE_ID'),
    'HYPERPAY_CURRENCY' => env('HYPERPAY_CURRENCY', "SAR"),


    #KASHIER
    'KASHIER_ACCOUNT_KEY' => env('KASHIER_ACCOUNT_KEY'),
    'KASHIER_IFRAME_KEY' => env('KASHIER_IFRAME_KEY'),
    'KASHIER_TOKEN' => env('KASHIER_TOKEN'),
    'KASHIER_URL' => env('KASHIER_URL', "https://checkout.kashier.io"),
    'KASHIER_MODE' => env('KASHIER_MODE', "test"), //live or test
    'KASHIER_CURRENCY'=>env('KASHIER_CURRENCY',"EGP"),
    'KASHIER_WEBHOOK_URL'=>env('KASHIER_WEBHOOK_URL'),


    #FAWRY
    'FAWRY_URL' => env('FAWRY_URL', "https://atfawry.fawrystaging.com/"),//https://www.atfawry.com/ for production
    'FAWRY_SECRET' => env('FAWRY_SECRET'),
    'FAWRY_MERCHANT' => env('FAWRY_MERCHANT'),
    'FAWRY_DISPLAY_MODE' => env('FAWRY_DISPLAY_MODE',"POPUP"),//required allowed values [POPUP, INSIDE_PAGE, SIDE_PAGE , SEPARATED]
    'FAWRY_PAY_MODE'=>env('FAWRY_PAY_MODE',"CARD"),//allowed values ['CashOnDelivery', 'PayAtFawry', 'MWALLET', 'CARD' , 'VALU']

    #PayPal
    'PAYPAL_CLIENT_ID' => env('PAYPAL_CLIENT_ID'),
    'PAYPAL_SECRET' => env('PAYPAL_SECRET'),
    'PAYPAL_CURRENCY' => env('PAYPAL_CURRENCY', "USD"),
    'PAYPAL_MODE' => env('PAYPAL_MODE',"sandbox"),//sandbox or live


    #THAWANI
    'THAWANI_API_KEY' => env('THAWANI_API_KEY', ''),
    'THAWANI_URL' => env('THAWANI_URL', "https://uatcheckout.thawani.om/"),
    'THAWANI_PUBLISHABLE_KEY' => env('THAWANI_PUBLISHABLE_KEY', ''),

    #TAP
    'TAP_CURRENCY' => env('TAP_CURRENCY',"USD"),
    'TAP_SECRET_KEY'=>env('TAP_SECRET_KEY','sk_test_XKokBfNWv6FIYuTMg5sLPjhJ'),
    'TAP_PUBLIC_KEY'=>env('TAP_PUBLIC_KEY','pk_test_EtHFV4BuPQokJT6jiROls87Y'),
    'TAP_LANG_KEY'=>env('TAP_LANG_KEY','ar'),


    #OPAY
    'OPAY_CURRENCY'=>env('OPAY_CURRENCY',"EGP"),
    'OPAY_SECRET_KEY'=>env('OPAY_SECRET_KEY'),
    'OPAY_PUBLIC_KEY'=>env('OPAY_PUBLIC_KEY'),
    'OPAY_MERCHANT_ID'=>env('OPAY_MERCHANT_ID'),
    'OPAY_COUNTRY_CODE'=>env('OPAY_COUNTRY_CODE',"EG"),
    'OPAY_BASE_URL'=>env('OPAY_BASE_URL',"https://sandboxapi.opaycheckout.com"),//https://api.opaycheckout.com for production


    #PAYMOB_WALLET (vodaphone-cash,orange-money,etisalat-cash,we-cash,meza-wallet) - test phone 01010101010 ,PIN & OTP IS 123456
    'PAYMOB_WALLET_INTEGRATION_ID'=>env('PAYMOB_WALLET_INTEGRATION_ID'),

    #Paytabs
    'PAYTABS_PROFILE_ID'  => env('PAYTABS_PROFILE_ID'),
    'PAYTABS_SERVER_KEY' =>  env('PAYTABS_SERVER_KEY'),
    'PAYTABS_BASE_URL' =>   env('PAYTABS_BASE_URL',"https://secure-egypt.paytabs.com"),
    'PAYTABS_CHECKOUT_LANG' => env('PAYTABS_CHECKOUT_LANG',"AR"),
    'PAYTABS_CURRENCY'=>env('PAYTABS_CURRENCY',"EGP"),


    #Binance
    'BINANCE_API'=>env('BINANCE_API'),
    'BINANCE_SECRET'=>env('BINANCE_SECRET'),



    #NowPayments
    'NOWPAYMENTS_API_KEY'=>env('NOWPAYMENTS_API_KEY'),


    #Payeer
    'PAYEER_MERCHANT_ID'=>env('PAYEER_MERCHANT_ID'),
    'PAYEER_API_KEY'=>env('PAYEER_API_KEY'),
    'PAYEER_ADDITIONAL_API_KEY'=>env('PAYEER_ADDITIONAL_API_KEY'),


    #Perfectmoney
    /*
    *please 
    *1- create POST route /payments/verify/{payment} and put it before your verify route 
    *2- put it into app/Http/Middleware/VerifyCsrfToken.php middleware inside except array
    */
    'PERFECT_MONEY_ID'=>env('PERFECT_MONEY_ID','UXXXXXXX'),
    'PERFECT_MONEY_PASSPHRASE'=>env('PERFECT_MONEY_PASSPHRASE'),

    'VERIFY_ROUTE_NAME' => "verify-payment",
    'APP_NAME'=>env('APP_NAME'),



    #TELR
    'TELR_MERCHANT_ID'=>env('TELR_MERCHANT_ID'),
    'TELR_API_KEY'=>env('TELR_API_KEY'),
    'TELR_MODE'=>env('TELR_MODE','test'),//test,live


    #CLICKPAY
    'CLICKPAY_SERVER_KEY'=>env('CLICKPAY_SERVER_KEY'),
    'CLICKPAY_PROFILE_ID'=>env('CLICKPAY_PROFILE_ID')
];