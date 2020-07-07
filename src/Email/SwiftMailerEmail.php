<?php


namespace App\Email;


use Swift_Message;

class SwiftMailerEmail implements Email
{

    /**
     * @param $subject
     * @param $template
     * @param array $cc
     * @param array $bcc
     * @param null $attachment
     * @param int $priority
     * @return mixed|Swift_Message
     */
    public function createEmailToAdmin($subject, $template, array $cc = [], array $bcc = [], $attachment = null, $priority = 3)
    {
        $to = array(
            /*'xxx@gmail.com' => 'Mr X',
            'yyy@gmail.com' => 'Mr Y',*/
        );
        $bcc = array(
            'fanilo@hightao-mg.com' => 'Fanilo'
        );
        return $this->createEmail($to, $subject, $template, $cc, $bcc, $attachment, $priority);
    }

    /**
     * @param array $to
     * @param string $subject
     * @param $template
     * @param array $cc
     * @param array $bcc
     * @param null $attachment
     * @param int $priority
     * @return mixed|Swift_Message
     */
    public function createEmail(array $to, string $subject, $template, array $cc = [], array $bcc = [], $attachment = null, $priority = 3)
    {
        $email = new Swift_Message($subject);
        $email->setTo($to);
//        $email->setFrom('contact@hiboo.fr');
        $email->setBody($template, 'text/html');

        $bcc['serviceclient@hiboo.fr'] = 'Service client';
        $bcc['support.tech@hiboo.fr'] = 'Support Informatique';
//        $email->setBcc($bcc);

        if ($attachment) $email->attach($attachment);

        return $email;
    }
}