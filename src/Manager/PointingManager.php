<?php


namespace App\Manager;


use App\Entity\Chantier;
use App\Entity\Pointing;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Services\DateConverter;

class PointingManager extends BaseManager
{
    /**
     * PointingManager constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, Pointing::class, $validator);
        $this->em = $em;
    }

    /**
     * @param Pointing $pointing
     * @return bool
     */
    public function savePointing(Pointing $pointing): bool
    {
        $this->saveOrUpdate($pointing);

        return true;
    }

    /**
     * @param array $data
     * @param DateConverter $dateConverter
     * @return object[]
     * @throws \Exception
     */
    public function dailyUser(array $data, DateConverter $dateConverter)
    {
        $reposChantier = $this->em->getRepository(Chantier::class);
        $reposUser = $this->em->getRepository(User::class);

        $chantier = $reposChantier->find($data['chantier']);
        $user = $reposUser->find($data['user']);
        $date = $dateConverter->DateFR2DateSQL($data['date']);

        return $this->findBy(
            [
                'chantier' => $chantier,
                'user' => $user,
                'date' => new \DateTime($date)
            ]
        );
    }
}
