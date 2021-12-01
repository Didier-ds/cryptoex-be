<?php

namespace App\Constants;

class Konstants
{
    const USER_ROLE_ADMIN = 1;
    const USER_ROLE_USER = 2;

    // Credentials Error MSG
    const INVALID_CREDENTIALS_ERROR = "Details does not match any User record";

    // Request Status Code
    const STATUS_ERROR = 500;
    const STATUS_NOT_FOUNT = 400;
    const STATUS_OK = 200;

    // URLS
    const URL_FRONTEND = 'https://cryptoex.netlify.app/#/';
}
