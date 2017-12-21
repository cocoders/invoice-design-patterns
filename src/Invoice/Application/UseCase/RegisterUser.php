<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase;

use Invoice\Application\TransactionManager;
use Invoice\Domain\Email;
use Invoice\Domain\Exception\EmailIsEmpty;
use Invoice\Domain\Exception\EmailIsNotValid;
use Invoice\Domain\Exception\PasswordIsNotValid;
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
    /**
     * @var TransactionManager
     */
    private $transactionManager;

    public function __construct(
        TransactionManager $transactionManager,
        UserRepository $userRepository,
        UserFactory $userFactory
    ) {
        $this->transactionManager = $transactionManager;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->responder = new RegisterUser\DefaultResponder();
    }

    public function execute(RegisterUser\Command $command): void
    {
        try {
            $user = $this
                ->userFactory
                ->create($command->email(), $command->password());
        } catch (EmailIsEmpty $exception) {
            $this->responder->emailIsEmpty();

            return;
        } catch (EmailIsNotValid $exception) {
            $this->responder->emailIsNotValid();

            return;
        } catch (PasswordIsNotValid $exception) {
            $this->responder->passwordIsNotValid();

            return;
        }

        $this->transactionManager->begin();
        try {
            if ($this->userRepository->has($user)) {
                $this->transactionManager->rollback();
                $this->responder->userWithSameEmailAlreadyExists(
                    new Email($command->email())
                );
                return;
            }

            $this->userRepository->add($user);
            $this->transactionManager->commit();
        } catch (\Throwable $e) {
            $this->transactionManager->rollback();

            throw $e;
        }
        $this->responder->userWasRegistered($user);
    }

    public function registerResponder(
        RegisterUser\Responder $responder
    ): void {
        $this->responder = $responder;
    }
}