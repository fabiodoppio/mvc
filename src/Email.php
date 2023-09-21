<?php

namespace Classes;


class Email {

	public static function send(string $subject, string $recipient, string $template, ?string $attachment = null, ?string $attachment_name = null) {
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        
        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->CharSet    = 'UTF-8';
        $mail->Encoding   = 'base64';
        $mail->Host       = App::get("MAIL_HOST");
        $mail->SMTPAuth   = true;
        $mail->Username   = App::get("MAIL_USERNAME");
        $mail->Password   = App::get("MAIL_PASSWORD");
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom(App::get("MAIL_SENDER"), App::get("APP_NAME"));
        $mail->addAddress($recipient);
        $mail->Subject    = $subject;
        $mail->Body       = $template;

        if ($attachment && $attachment_name)
            $mail->addStringAttachment($attachment, $attachment_name);
        if(!$mail->send())
           throw new Exception("E-Mail konnte nicht versendet werden.");
	}

}

?>