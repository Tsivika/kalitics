<?php

namespace App\Command;

use App\Manager\UserManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteUserNotVerified extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:delete-user-not-verified';

    /**
     * @var UserManager
     */
    private $em;

    /**
     * DeleteUserNotVerified constructor.
     * @param UserManager $em
     */
    public function __construct(UserManager $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|mixed
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->em->deleteUserNotVerified();
    }
}