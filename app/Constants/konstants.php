<?php

namespace App\Constants;

class Konstants
{
    // inputs keys
    const FULLNAME = "fullname";
    const EMAIL = "email";
    const PWORD = "password";
    const PHONE = "phone";
    const A_TOK = 'auth-token';
    const ACC_NO = "account_no";
    const ACC_NAME = "account_name";
    const BANK = "bank";

    const DEFAULT = "none";

    // Roles
    const ROLE_OWNER = "owner";
    const ROLE_ADMIN = "admin";
    const ROLE_USER = "user";

    const USER_ROLE_ADMIN = 1;
    const USER_ROLE_USER = 2;

    // Credentials Error MSG
    const ERR_INVALID_CRED = "Invalid Email or Password";
    const ERR_INVALID_EMAIL = "Email does not match any in our records";
    const ERR_LACK_AUTH = "Lacking appropriate authorization";

    // Request Status Code
    const STATUS_ERROR = 500;
    const STATUS_BAD_CRED = 406;
    const STATUS_NOT_FOUND = 400;
    const STATUS_OK = 200;
    const STATUS_401 = 401;

    const MSG_OK = "success";
    const MSG_404 = "Resource not found";
    const MSG_500 = "Unexpected Server Error";
    const MSG_401 = "Forbidden";

    const MESSAGE_CHECK_MAIL = "Please Check your email for details on how to proceed";
    const MESSAGE_CEHECK_MAIL_LINK = "A verifiaction link has been sent to your email. Check and verify your account";
    const MESSAGE_ADMIN_CHECK_MAIL = "Admin should check email and verify account";


    // URLS
    const URL_BASE = 'https://crypto-ex.netlify.app/';
    const URL_VETTED = 'http://Crypto-ex.netlify.app/email/verification/when/now?verify=verified';
}
