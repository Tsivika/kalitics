<?php


namespace App\Command;

use App\Manager\MeetingManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class UpdateStatusMeetingCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:update-status-meeting';

    /**
     * @var MeetingManager
     */
    private $em;

    /**
     * @var ContainerBagInterface
     */
    private $params;

    /**
     * UpdateStatusMeetingCommand constructor.
     * @param MeetingManager $em
     * @param ContainerBagInterface $params
     */
    public function __construct(MeetingManager $em, ContainerBagInterface $params)
    {
        $this->em=$em;
        $this->params = $params;
        $this->urlBbb = $this->params->get('app.bbb_server_base_url');
        $this->secretBbb = $this->params->get('app.bbb_secret');
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $meetings = $this->em->getToUpdateMeeting();
        foreach ($meetings as $meeting) {
            $state = $this->em->getInfoMeeting($meeting, $this->urlBbb, $this->secretBbb);
            $meeting->setState($state);
            if ($state == 2) {
                $meeting->setStatus(true);
            }
            $this->em->saveOrUpdate($meeting);
        }
    }
}