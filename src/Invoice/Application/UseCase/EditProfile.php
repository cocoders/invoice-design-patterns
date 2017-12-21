<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase;

use Invoice\Application\UseCase\EditProfile\DefaultResponder;
use Invoice\Application\UseCase\EditProfile\Responder;
use Invoice\Domain\Email;
use Invoice\Domain\Exception\UserNotFound;
use Invoice\Domain\ProfileFactory;
use Invoice\Domain\UserRepository;

class EditProfile
{
    private $userRepository;
    private $profileFactory;

    /**
     * @var Responder
     */
    private $responder;

    public function __construct(UserRepository $userRepository, ProfileFactory $profileFactory)
    {
        $this->userRepository = $userRepository;
        $this->profileFactory = $profileFactory;
        $this->responder = new DefaultResponder();
    }

    public function execute(EditProfile\Command $command)
    {
        try {
            $user = $this->userRepository->getByEmail(new Email($command->email()));
        } catch (UserNotFound $exception) {
            $this->responder->userNotFound(new Email($command->email()));

            return;
        }

        $user->changeProfile($this->profileFactory->create(
            $command->name(),
            $command->vatIdNumber(),
            $command->address()
        ));

        $this->userRepository->add($user);
        $this->responder->userEditedSuccesfully($user);
    }

    public function registerResponder(Responder $responder): void
    {
        $this->responder = $responder;
    }
}