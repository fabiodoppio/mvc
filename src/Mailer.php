<?php

/**
 *
 *  MVC
 *  Model View Controller (MVC) design pattern for simple web applications.
 *
 *  @see     https://github.com/fabiodoppio/mvc
 *
 *  @author  Fabio Doppio (Developer) <hallo@fabiodoppio.de>
 *  @license https://opensource.org/license/mit/ MIT License
 *
 */


namespace MVC;

/**
 *
 *  Mailer Class
 *
 *  The Mailer class provides functionality for sending emails using the PHPMailer library.
 *  It supports sending HTML emails with optional attachments.
 *
 */
class Mailer {

    /**
     *
     *  Send an email with the specified subject, recipient, HTML template, and optional attachment.
     *
     *  @since  3.0             Adjusted timeout from 120 to 60 seconds, added multiple attachments.
     *  @since  2.4             Added optional reply-to parameter.
     *  @since  2.3.1           Removed base64_decode() from password, added timeout, added specific error message
     *  @since  2.0
     *  @param  string          $subject            The subject of the email.
     *  @param  string          $recipient          The recipient's email address.
     *  @param  string          $template           The HTML content of the email.
     *  @param  string|null     $replyTo            (Optional) Reply-To address.
     *  @param  array|null      $attachments        (Optional) Attachment content.
     *
     *
     */
	public static function send(string $subject, string $recipient, string $template, ?string $replyTo = null, ?array $attachments = null) {
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer();

            $mail->isSMTP();
            $mail->isHTML(true);
            $mail->CharSet    = 'UTF-8';
            $mail->Encoding   = 'base64';
            $mail->Host       = App::get("MAIL_HOST");
            $mail->SMTPAuth   = true;
            $mail->Username   = App::get("MAIL_USERNAME");
            $mail->Password   = App::get("MAIL_PASSWORD");
            $mail->SMTPSecure = App::get("MAIL_ENCRYPT");
            $mail->Port       = App::get("MAIL_PORT");
            $mail->Timeout    = 60;
            $mail->XMailer    = ' ';

            if ($replyTo)
                $mail->addReplyTo($replyTo);

            $mail->setFrom(App::get("MAIL_SENDER"), App::get("APP_NAME"));
            $mail->addAddress($recipient);
            $mail->Subject    = $subject;
            $mail->Body       = $template;

            if ($attachments)
                foreach($attachments as $attachment) {
                    [$name, $content] = $attachment;
                    $mail->addStringAttachment($content, $name);
                }

            $mail->send();
        }
        catch(\PHPMailer\PHPMailer\Exception $exception) {
            throw new Exception(_("Email could not be sent: ".$exception->getMessage()), 1200);
        }
	}

}

?>