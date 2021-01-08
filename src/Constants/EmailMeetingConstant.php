<?php


namespace App\Constants;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EmailMeetingConstant
 * @package App\Constants
 */
class EmailMeetingConstant extends AbstractController
{

    /**
     *
     */
    public const _MESSAGE_TO_SEND_0_ = 'Vous êtes invité.e à une réuniboo';
    public const _MESSAGE_TO_SEND_0_PRESENTER_ = 'Vous avez créé une réuniboo';
    public const _MESSAGE_TO_SEND_1_ = 'Votre réunion va commencer dans 15min : ';
    public const _SUBJECT_TO_SEND_ = 'Invitation à une réunion Iboo';
    public const _SUBJECT_TO_SEND_PRESENTER_ = 'Vous avez créé une réunion Iboo';
    public const _SENDER_NAME_ = 'Iboo';
    
    public const _JOIN_MEETING_ = 'Pour rejoindre la réunion, <br>cliquer sur le bouton suivant :';
    public const _PWD_MEETING_ = 'Mot de passe : ';
    public const _DATE_MEETING_ = 'Date : ';
    public const _SIGNATURE_ = '<i>Merci d\'utiliser Iboo !<br>Une question? <a href="https://iboo.live/contact"  class="link-style" style="color: #00c9ae; text-decoration: none;">Contactez-nous</a> sur iboo.live</i>';
}
