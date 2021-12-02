<?php

namespace App\Constants;

class Konstants
{
    const USER_ROLE_ADMIN = 1;
    const USER_ROLE_USER = 2;

    // Credentials Error MSG
    const INVALID_CRED = "Invalid Email or Password";
    const INVALID_EMAIL = "Email does not match any in our records";

    // Request Status Code
    const STATUS_ERROR = 500;
    const STATUS_BAD_CRED = 406;
    const STATUS_NOT_FOUND = 400;
    const STATUS_OK = 200;

    // URLS
    const URL_FRONTEND = 'https://cryptoex.netlify.app/#/';
}
