<?php


namespace App\Services;

use DateTime;


class DateActions
{
    /**
     * @param $date
     * @return false|string
     */
    public function getNumberWeekOfDate($date)
    {
        $dates=explode('-',$date);

        return date('W',mktime(0,0,0,$dates[1],$dates[2],$dates[0]));
    }

    /**
     * @param string $annee
     * @param string $semaine
     * @param string $format
     * @return array
     */
    public function week2day($annee="", $semaine="", $format = 'Y-m-d')
    {
        $annee = (isset($annee) && !empty($annee)) ? $annee : date("Y");
        $semaine = (isset($semaine) && !empty($semaine)) ? $semaine : date("W");

        $dateObjet = new DateTime();
        $dateObjet->setISOdate($annee, $semaine);
        $dateDebut = $dateObjet->format($format);
        date_modify($dateObjet , '+6 day');
        $dateFin = $dateObjet->format($format);

        return array(
            "startDate" => $dateDebut,
            "endDate" => $dateFin
        );
    }
}