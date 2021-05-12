<?php


namespace App\Manager;


use App\Entity\Chantier;
use App\Entity\Pointing;
use App\Entity\User;
use App\Services\DateActions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Services\DateConverter;

class PointingManager extends BaseManager
{
    /**
     * @var DateConverter
     */
    private $dateConverter;

    /**
     * PointingManager constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param DateConverter $dateConverter
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, DateConverter $dateConverter)
    {
        parent::__construct($em, Pointing::class, $validator);
        $this->em = $em;
        $this->dateConverter = $dateConverter;
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
     * @return object[]
     * @throws \Exception
     */
    public function dailyUser(array $data)
    {
        $reposChantier = $this->em->getRepository(Chantier::class);
        $reposUser = $this->em->getRepository(User::class);

        $chantier = $reposChantier->find($data['chantier']);
        $user = $reposUser->find($data['user']);
        $date = $this->dateConverter->DateFR2DateSQL($data['date']);

        return $this->findBy(
            [
                'chantier' => $chantier,
                'user' => $user,
                'date' => new \DateTime($date)
            ]
        );
    }

    public function weeklyUser(array $data, UserManager $userManager, DateActions $dateActions)
    {
        $message = '';
        $hourPerWeekAutored = 35;
        $hoursWeek = 0;
        $user = $userManager->find($data['user']);
        $date = $this->dateConverter->DateFR2DateSQL($data['date']);
        $duration = $data['duration'];
        $list = explode('-', $date);
        $numberWeek = $dateActions->getNumberWeekOfDate($date);
        $dates = $dateActions->week2day($list[0],$numberWeek);
        $totalHourWeek = $this->repository->maxHourPerWeekUser($user, $dates);
        if(null !== $totalHourWeek) {
            $hoursWeek = $totalHourWeek['totalHour'];
        }
        $totalHour = $duration + $hoursWeek;
        if ($totalHour >= $hourPerWeekAutored) {
            $message = 'Avec l\'ajout de votre heure actuelle, vous dépassez les '.$hourPerWeekAutored.'heures autorisées par semaine';
        }

        return $message;
    }
}
