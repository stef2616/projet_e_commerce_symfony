<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

final class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_user')]
    public function index(UserRepository $user): Response
    {
        $user = $user->findAll();
        return $this->render('user/index.html.twig', ['user' => $user]);
    }

    #[Route('/admin/user/{id}/to/editor', name: 'app_user_to_editor')]
    public function changeRole(EntityManagerInterface $em ,User $user): Response
    {
        $user->setRoles(['ROLE_EDITOR', 'ROLE_USER']);
        $em->flush();
        $this->addFlash('info' , 'User Role Changed to Editor');
        return $this->redirectToRoute('app_user' , ['user' => $user]);
    }
    #[Route('/admin/user/{id}/remove/editor', name: 'app_user_remove_editor')]
    public function removeRole(EntityManagerInterface $em ,User $user): Response
    {
        $user->setRoles([]);
        $em->flush();
        $this->addFlash('danger' , 'User role editor has removed');
        return $this->redirectToRoute('app_user' , ['user' => $user]);
    }
    
    #[Route('/admin/user/remove/{id}', name: 'app_user_remove')]
public function removeUser(EntityManagerInterface $em, User $user): Response
{
    // Supprimer les enregistrements liés dans reset_password_request
    $resetPasswordRequests = $user->getResetPasswordRequests();
    foreach ($resetPasswordRequests as $request) {
        $em->remove($request);
    }

    // Supprimer l'utilisateur
    $em->remove($user);
    $em->flush();

    $this->addFlash('danger', 'User has been removed');
    return $this->redirectToRoute('app_user');
}

}
