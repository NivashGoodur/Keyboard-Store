<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'app_contact')]
    public function index(Request $request, ContactRepository $contactRepository): Response
    {

        $form = $this->createForm(ContactType::class); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contactRepository->save($form->getData(), true);
            $this->addFlash('notice', 'Merci de nous avoir contacté. Notre équipe vous répondra au plus vite');
            return $this->redirect($request->getUri());
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
