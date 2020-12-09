<?php

namespace App\Command;

use App\Manager\ParticipantManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyParticipantsCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:notify-participants';

    /**
     * @var ParticipantManager
     */
    private $em;

    /**
     * NotifyParticipantsCommand constructor.
     * @param ParticipantManager $em
     */
    public function __construct(ParticipantManager $em)
    {
        $this->em=$em;
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em->notifyParticipants();
    }
}
