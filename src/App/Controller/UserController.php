<?php

declare(strict_types=1);

namespace App\Controller;

use Invoice\Application\UseCase\EditProfile;
use Invoice\Domain\Email;
use Invoice\Domain\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function editProfile(EditProfile $editProfile, Users $users, Request $request): Response
    {
        $user = $users->get(new Email($this->getUser()->getUsername()));

        if ($request->isMethod('post')) {
            $editProfile->execute(new EditProfile\Command(
                (string) $user->email(),
                (string) $request->request->get('vat_id_number'),
                (string) $request->request->get('name'),
                (string) $request->request->get('address')
            ));
            return $this->redirectToRoute('edit_profile');
        }

        return $this->render('user/editProfile.html.twig', ['user' => $user]);
    }
}
