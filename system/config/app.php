<?php return [

/**
 *--------------------------------------------------------------------------
 * Application Debug Mode
 *--------------------------------------------------------------------------
 *
 * When your application is in debug mode detailed error messages with
 * stack traces will be shown on every error that occurs within your
 * application. If disabled a simple error page is shown.
 */

'debug' => true,

/**
 *--------------------------------------------------------------------------
 * Application Character Set
 *--------------------------------------------------------------------------
 *
 * It's useful for response generation and many case. Default is already
 * defined for you for convenient.
 */

'charset' => 'UTF-8',

/**
 *--------------------------------------------------------------------------
 * Application Language
 *--------------------------------------------------------------------------
 *
 * This will be used for translation in validation and others. We use simple
 * language support provided by Drafterbit\Framework. If you need more,  you can always
 * add you own language library.
 */

'language' => 'indonesia',

/**
 *--------------------------------------------------------------------------
 * Application Encryption Key
 *--------------------------------------------------------------------------
 *
 * This key is required for encryption handling in cookie and session, you
 * need to set it to long, hard-to-guess, unpredictable string, unless you
 * don't care about security.
 */

'key' => 'encryption-key',

/**
 *--------------------------------------------------------------------------
 * Application Start Script
 *--------------------------------------------------------------------------
 *
 * In case that we need to override things we can do it in this file. Start
 * script run after boot just before handling the request.
 */

'startscript' => '',

/**
 *--------------------------------------------------------------------------
 * Aplication Log
 *--------------------------------------------------------------------------
 *
 * Wether you want log the application error or not. If you in development
 * mode, you probably want shut it down by set following value to false.
 * Log will be recorded per day in 'logs' directory.
 */

'error.log' => false

];
