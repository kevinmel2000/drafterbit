<?php return [

/**
 *--------------------------------------------------------------------------
 * SMTP Configuration
 *--------------------------------------------------------------------------
 *
 * Smtp seems like best choice for now. The following configuration allows
 * us to send email via SMTP Transport. Note that you always can add other
 * transport. Just create another MailServiceProvider.
 */

    'from'      => ['' => ''],
    'smtp.host' => 'smtp.gmail.com',
    'smtp.port' => '465',
    'smtp.user' => "",
    'smtp.pass' => ''
];
