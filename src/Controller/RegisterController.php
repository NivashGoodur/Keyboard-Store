<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher, UserRepository $repository): Response
    {
        // création du formulaire
        $form = $this->createForm(RegisterType::class); 
        $form->handleRequest($request);

        // Test la validité du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // cryptage du mot de passe du client
            $form->getData()->setPassword($hasher->hashPassword(
                $form->getData(),
                $form->getData()->getPassword(),
            ));

            // enregistrement du nouveau client dans la base de données
            $repository->save($form->getData(), true);

            $this->addFlash('notice', 'Votre compte a été crée, merci de vous connecter');

            return $this->redirectToRoute('app_login');
            
        }


        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
