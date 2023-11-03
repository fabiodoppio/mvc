<?php

/**
 * mvc
 * Model View Controller (MVC) design pattern for simple web applications.
 *
 * @see     https://github.com/fabiodoppio/mvc
 *
 * @author  Fabio Doppio (Developer) <hallo@fabiodoppio.de>
 * @license https://opensource.org/license/mit/ MIT License
 */


namespace MVC;

/**
 * Email Class
 *
 * The Email class provides functionality for sending emails using the PHPMailer library.
 * It supports sending HTML emails with optional attachments.
 */
class Email {

    /**
     * Send an email with the specified subject, recipient, HTML template, and optional attachment.
     *
     * @param   string          $subject The subject of the email.
     * @param   string          $recipient The recipient's email address.
     * @param   string          $template The HTML content of the email.
     * @param   string|null     $attachment (Optional) The attachment content as a string.
     * @param   string|null     $attachment_name (Optional) The name of the attachment file.
     * @throws                  Exception If the email sending process fails.
     */
	public static function send(string $subject, string $recipient, string $template, ?string $attachment = null, ?string $attachment_name = null) {
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        
        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->CharSet    = 'UTF-8';
        $mail->Encoding   = 'base64';
        $mail->Host       = App::get("MAIL_HOST");
        $mail->SMTPAuth   = true;
        $mail->Username   = App::get("MAIL_USERNAME");
        $mail->Password   = base64_decode(App::get("MAIL_PASSWORD"));
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom(App::get("MAIL_SENDER"), App::get("APP_NAME"));
        $mail->addAddress($recipient);
        $mail->Subject    = $subject;
        $mail->Body       = $template;

        if ($attachment && $attachment_name)
            $mail->addStringAttachment($attachment, $attachment_name);
        if(!$mail->send())
           throw new Exception(_("Email could not be sent."));
	}

}

?>