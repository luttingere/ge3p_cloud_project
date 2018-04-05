<?php

namespace App\Util;
/**
 * Created by PhpStorm.
 * User: Eduardo Luttinger
 * Date: 20/03/2016
 * Time: 03:48 PM
 */
class GE3PApiMessages
{

    public static $SUCCESS_CODE = 0;
    public static $SUCCESS_MESSAGE = 'SUCCESSFUL REQUEST';
    public static $SUCCESS_SAVE_MESSAGE = 'SAVED SUCCESSFULLY';
    public static $SUCCESS_DELETED_MESSAGE = 'DELETED SUCCESSFULLY';
    public static $SUCCESS_UPDATED_MESSAGE = 'UPDATED SUCCESSFULLY';
    public static $SUCCESS_ACCESS = 'ACCESS GRANTED';


    public static $INVALID_JSON_OBJECT_CODE = 01;
    public static $INVALID_JSON_OBJECT_MESSAGE = 'Invalid Json Object';

    public static $INVALID_PARAMETERS_CODE = 02;
    public static $INVALID_PARAMETERS_MESSAGE = 'Invalid Parameters received, please checkout the api documentation';

    public static $INTERNAL_SERVER_ERROR_CODE = 03;
    public static $INTERNAL_SERVER_ERROR_MESSAGE = 'Internal Server Error, Please contact with the api administrator';

    public static $INVALID_JSON_HEADER_CODE = 04;
    public static $INVALID_JSON_HEADER_MESSAGE = 'Invalid Reaxium Json';

    public static $INVALID_USER_ACCESS_CODE = 05;
    public static $INVALID_USER_ACCESS_MESSAGE = 'The username that you\'ve entered doesn\'t match any account.';

    public static $INVALID_USER_STATUS_CODE = 06;
    public static $INVALID_USER_STATUS_MESSAGE = 'Invalid Status User';

    public static $DEVICE_ALREADY_CONFIGURED_CODE = 07;
    public static $DEVICE_ALREADY_CONFIGURED_MESSAGE = 'Device id already configured in a device';

    public static $DEVICE_NOT_CONFIGURED_CODE = 8;
    public static $DEVICE_NOT_CONFIGURED_MESSAGE = 'Device id not configured in a device';

    public static $INVALID_STATUS_CODE = 9;
    public static $INVALID_STATUS_MESSAGE = 'Device with invalid status in system';


    public static $GENERAL_ERROR_CODE = 500;


    public static $NOT_FOUND_CODE = 404;
    public static $CANNOT_SAVE = 101;

    public static $CODE_VALIDATE_STATUS = 1;

    public static $USER_TYPE_SUPER_ADMIN = 1;
    public static $USER_TYPE_ADMIN_SCHOOL = 5;
    public static $USER_TYPE_ADMIN_CALL_CENTER = 6;
    public static $ACTIVE_MENU_FOR_TYPE_USER = 1;

    public static $CITATION_TYPE_OFFENDER = 1;
    public static $CITATION_TYPE_AUTOMOBILE = 2;
    public static $CITATION_TYPE_HOME_OR_BUSINESS = 3;

    public static $EMAILS = ['reaxiumSystem@t2ss.com'];

    public static $APLICATION_CITATION_ID = 2;
    //Production
    public static $URL_SYSTEM_MESSAGE_REAXIUM = "http://34.208.166.161//reaxium_data_sender/EmailService/send";
    //Development
    //public static $URL_SYSTEM_MESSAGE_REAXIUM = "http://54.202.10.0//reaxium_data_sender/EmailService/send";
}