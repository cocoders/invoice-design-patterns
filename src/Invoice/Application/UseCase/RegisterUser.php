<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase;

use Invoice\Domain\Email;
use Invoice\Domain\UserFactory;
use Invoice\Domain\UserRepository;

class RegisterUser
{
    private $userRepository;
    private $userFactory;
    /**
     * @var RegisterUser\Responder
     */
    private $responder;

    public function __construct(
        UserRepository $userRepository,
        UserFactory $userFactory
    ) {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->responder = new RegisterUser\DefaultResponder();
    }

    public function execute(RegisterUser\Command $command): void
    {
        $user = $this
            ->userFactory
            ->create($command->email(), $command->password())
        ;

        if ($this->userRepository->has($user)) {
            $this->responder->userWithSameEmailAlreadyExists(
                new Email($command->email())
            );
            return;
        }

        $this->userRepository->add($user);
        $this->responder->userWasRegistered($user);
    }

    public function registerResponder(
        RegisterUser\Responder $responder
    ): void {
        $this->responder = $responder;
    }
}