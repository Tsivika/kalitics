<?php


namespace App\Constants;


class SubscriptionConstant
{
    /**
     * Mode subscription
     */
    public const _MODE_SUBSCRIPTION_ = [
        'Gratuit' => 'free',
        'Payant' => 'paying',
    ];

    /**
     * Duration subscription
     */
    public const _DURATION_SUBSCRIPTION_ = [
        'Gratuit' => '0',
        '3 mois' => '3',
        '6 mois' => '6',
        '9 mois' => '9',
        '12 mois' => '12',
    ];
}