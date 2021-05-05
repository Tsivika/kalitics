<?php


namespace App\Manager;


use App\Entity\Chantier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ChantierManager extends BaseManager
{
    /**
     * ChantierManager constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, Chantier::class, $validator);
        $this->em = $em;
    }

    /**
     * @param Chantier $chantier
     * @return bool
     */
    public function saveChantier(Chantier $chantier): bool
    {
        $this->saveOrUpdate($chantier);

        return true;
    }

    /**
     * @param Chantier $chantier
     * @return \App\Entity\Pointing[]|\Doctrine\Common\Collections\Collection
     */
    public function getListUser(Chantier $chantier)
    {
        return $chantier->getPointings();
    }

    /**
     * @param Chantier $chantier
     * @return int
     */
    public function getTotalUser(Chantier $chantier)
    {
        return count($chantier->getPointings());
    }

    /**
     * @param Chantier $chantier
     * @return int
     */
    public function getTotalHourUser(Chantier $chantier)
    {
        $totalHour = 0;
        foreach ($chantier->getPointings() as $row) {
            $totalHour += $row->getDuration();
        }

        return $totalHour;
    }
}
