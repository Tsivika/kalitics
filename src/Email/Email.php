<?php

namespace App\Email;

interface Email
{
    /**
     * @param $subject
     * @param $template
     * @param array $cc
     * @param array $bcc
     * @param null $attachment
     * @param int $priority
     * @return mixed
     */
    public function createEmailToAdmin($subject, $template, array $cc = [], array $bcc = [], $attachment = null, $priority = 3);

    /**
     * @param array $to
     * @param string $subject
     * @param $template
     * @param array $cc
     * @param array $bcc
     * @param null $attachment
     * @param int $priority
     * @return mixed
     */
    public function createEmail(array $to, string $subject, $template, array $cc = [], array $bcc = [], $attachment = null, $priority = 3);
}