<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase;

use Invoice\Application\UseCase\RegisterUser\Command;
use Invoice\Domain\UserFactory;
use Invoice\Domain\UserRepository;

class RegisterUser
{
    private $userRepository;
    private $userFactory;

    public function __construct(
        UserRepository $userRepository,
        UserFactory $userFactory
    ) {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
    }

    public function execute(Command $command): void
    {
        $this->userRepository->add(
            $this->userFactory->create($command->email(), $command->password())
        );
    }
}