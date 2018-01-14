<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase;

use Exception;
use Invoice\Application\TransactionManager;
use Invoice\Application\UseCase\EditProfile\NullResponder;
use Invoice\Application\UseCase\EditProfile\Responder;
use Invoice\Domain\Email;
use Invoice\Domain\Exception\UserNotFound;
use Invoice\Domain\User;
use Invoice\Domain\Users;
use Invoice\Domain\VatNumberFactory;

class EditProfile
{
    private $transactionManager;
    private $users;
    private $vatNumberFactory;
    private $responder;

    public function __construct(TransactionManager $transactionManager, Users $users, VatNumberFactory $vatNumberFactory)
    {
        $this->transactionManager = $transactionManager;
        $this->users = $users;
        $this->vatNumberFactory = $vatNumberFactory;
        $this->responder = new NullResponder();
    }

    public function execute(EditProfile\Command $command): void
    {
        $vatNumber = $this->vatNumberFactory->create($command->vatNumber());
        $email = new Email($command->email());
        $profile = new User\Profile(
            $vatNumber,
            $command->name(),
            $command->address()
        );

        $this->transactionManager->begin();

        try {
            $user = $this->users->get($email);
            $user->changeProfile($profile);
            $this->transactionManager->commit();
        } catch (UserNotFound $exception) {
            $this->transactionManager->rollback();

            $this->responder->userNotFound($exception);
            return;
        } catch (Exception $exception) {
            $this->transactionManager->rollback();

            throw $exception;
        }

        $this->responder->profileChanged($email, $profile);
    }

    public function registerResponder(Responder $responder): void
    {
        $this->responder = $responder;
    }
}
