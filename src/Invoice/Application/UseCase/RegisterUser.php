<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase;

use Invoice\Application\TransactionManager;
use Invoice\Domain\UserFactory;
use Invoice\Domain\Users;
use Throwable;

class RegisterUser
{
    private $transactionManager;
    private $users;
    private $userFactory;

    /**
     * @var RegisterUser\Responder
     */
    private $responder;

    public function __construct(TransactionManager $transactionManager, Users $users, UserFactory $userFactory)
    {
        $this->transactionManager = $transactionManager;
        $this->users = $users;
        $this->userFactory = $userFactory;
        $this->responder = new RegisterUser\NullResponder();
    }

    public function execute(RegisterUser\Command $command): void
    {
        if (!trim($command->email())) {
            $this->responder->emailIsEmpty();
            return;
        }

        $this->transactionManager->begin();

        try {
            $user = $this->userFactory->create($command->email(), $command->password());
            if ($this->users->has($user)) {
                $this->transactionManager->rollback();
                $this->responder->userAlreadyExists($user);
                return;
            }
            $this->users->add($user);
            $this->transactionManager->commit();
        } catch (Throwable $exception) {
            $this->transactionManager->rollback();

            throw $exception;
        }

        $this->responder->userRegistered($user);
    }

    public function registerResponder(RegisterUser\Responder $responder): void
    {
        $this->responder = $responder;
    }
}
