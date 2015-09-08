<?php

class ResponseCode
{
    const SUCCESS = 200; // Use for success
    const BAD_REQUEST = 400; // Use if the request is malformed or the information is invalid (i.e. email is not a valid email)
    const UNAUTHORIZED = 401; // Use if it has anything to deal with authentication (i.e. login, verfication codes)
    const FORBIDDEN = 403; // Not sure lol
    const NOT_FOUND = 404; // Use only for requests that cannot be mapped
    const INTERNAL_SERVER_ERROR = 500; // Use for any issues that stops the request from being processed. (i.e. Thrown errors, missing data, etc)
}