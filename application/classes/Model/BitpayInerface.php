<?php

interface Model_BitpayInerface 
{
    const STATUS_PAID       = 'paid';

    const STATUS_CONFIRMED  = 'confirmed';

    const STATUS_COMPLETE   = 'complete';

    const STATUS_EXPIRED    = 'expired';

    const STATUS_INVALID    = 'invalid';

    const STATUS_NEW        = 'new';

    const PARAM_IS_ROUND    =  0.0005;

    const BITPAY_URL_INVOICE = 'https://bitpay.com/invoice?id=';
}