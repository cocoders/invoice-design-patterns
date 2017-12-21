<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase;

use Invoice\Application\TransactionManager;
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
    /**
     * @var TransactionManager
     */
    private $transactionManager;

    public function __construct(
        TransactionManager $transactionManager,
        UserRepository $userRepository,
        ProfileFactory $profileFactory
    ) {
        $this->userRepository = $userRepository;
        $this->profileFactory = $profileFactory;
        $this->responder = new DefaultResponder();
        $this->transactionManager = $transactionManager;
    }

    public function execute(EditProfile\Command $command)
    {
        $this->transactionManager->begin();

        try {
            $user = $this->userRepository->getByEmail(new Email($command->email()));
        } catch (UserNotFound $exception) {
            $this->transactionManager->rollback();
            $this->responder->userNotFound(new Email($command->email()));

            return;
        }

        try {
            $user->changeProfile($this->profileFactory->create(
                $command->name(),
                $command->vatIdNumber(),
                $command->address()
            ));

            $this->transactionManager->commit();
        } catch (\Exception $e) {
            $this->transactionManager->rollback();

            throw $e;
        }
        $this->responder->userEditedSuccesfully($user);
    }

    public function registerResponder(Responder $responder): void
    {
        $this->responder = $responder;
    }
}