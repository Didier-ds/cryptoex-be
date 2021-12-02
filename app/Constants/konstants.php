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

    const MSG_OK = "success";
    const MSG_404 = "Resource not found";
    const MSG_500 = "Unexpected Server Error";
    const MSG_401 = "Forbidden";

    const MESSAGE_CHECK_MAIL = "Please Check your email for details on how to proceed";
    const MESSAGE_CEHECK_MAIL_LINK = "A verifiaction link has been sent to your email. Check and verify your account";


    // URLS
    const URL_FRONTEND = 'https://cryptoex.netlify.app/#/';
}
