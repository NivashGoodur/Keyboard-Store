<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte', name: 'app_account')]
    public function account(): Response
    {
        return $this->render('account/account.html.twig');
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_account_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $hasher, UserRepository $repository): Response
    {
        $notification = null;

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();

            if ($hasher->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $password = $hasher->hashPassword($user, $new_pwd);

                $user->setPassword($password);
                $this->entityManager->flush();
                $notification = "Votre mot de passe a bien été à jour";
            }else {
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
        }

        return $this->render('account/account_password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }

    #[Route('/compte/adresses', name: 'app_account_address')]
    public function address(AddressRepository $repository): Response
    {
        
        return $this->render('account/account_address.html.twig');
    }

    #[Route('/compte/ajouter-une-adresse', name: 'app_account_add_address')]
    public function addAddress(Request $request, AddressRepository $repository, Cart $cart): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(AddressType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $address->setUser($user);
            
            $repository->save($form->getData(), true);

            if ($cart->get()) {
                return $this->redirectToRoute('app_order');
            }else {
                return $this->redirectToRoute('app_account_address');
            }
        }

        return $this->render('account/account_form_address.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/modifier-une-adresse/{id}', name: 'app_account_edit_address')]
    public function editAddress(Request $request, AddressRepository $repository, $id): Response
    {

        $address = $repository->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            
            $repository->save($form->getData(), true);

            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/account_form_address.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/supprimer-une-adresse/{id}', name: 'app_account_delete_address')]
    public function deleteAddress(AddressRepository $repository, $id): Response
    {

        $address = $repository->findOneById($id);

        if ($address && $address->getUser() == $this->getUser()) {
            $repository->remove($address, true);
        }

        return $this->redirectToRoute('app_account_address');

    }

    #[Route('/compte/mes-commandes', name: 'app_account_order')]
    public function myOrders(OrderRepository $repository): Response
    {

        $orders = $repository->findSuccessOrders($this->getUser());



        return $this->render('account/account_order.html.twig', [
            'orders' => $orders
        ]);

    }

    #[Route('/compte/mes-commandes/{reference}', name: 'app_account_order_show')]
    public function myOrderShow(OrderRepository $repository, $reference): Response
    {

        $order = $repository->findOneByReference($reference);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_order');
        }



        return $this->render('account/account_order_show.html.twig', [
            'order' => $order
        ]);

    }
}
