<?php

namespace App\Controller;

use Error;
use Exception;
use App\Classe\Cart;
use App\Entity\Order;
use Stripe\StripeClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci', name: 'app_order_validate')]
    public function index(Cart $cart): Response
    {
        $stripe = new StripeClient('sk_test_51MH5s1EopZv0KhqCh3bpfLj0pvlaoCAQKjrN8SvLjHQ1oiAlvy7mtylSz3wU5ZmkeaRhEjdN5C4YREa5wYrlBXrY00qk70HQS2');

        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($_GET['session_id']);
        
        if(!$order || $order->getUser() !== $this->getUser() || $order->getState() == 0) {
            return $this->redirectToRoute('app_home');
        }

        if ($order->getState() == 1) {
            //Vider la session "cart" si la commande a été payée
            $cart->remove();
        }
        

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }



    //webhook
    #[Route('/webhook', name: 'app_webhook', methods: ['POST'])]
    public function webhook(Cart $cart)
    {

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_gX2th0YZ7pyHLRB82iZsTZqAUq5LcIwh';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        } catch(\UnexpectedValueException $e) {
        // Invalid payload
        return new Response('', 400);
        exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        return new Response('', 400);
        exit();
        }

        // Handle the event

        if($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $sessionId = $session->id;

            $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($sessionId);

            if(!$order) {
                throw new NotFoundHttpException("Page not found");
            }  
    
            //Modifier le statut state payée de notre commande
            if ($order->getState() == 0) {
    
                $order->setState(1);
                $this->entityManager->flush();
            }
        }

        return new Response('', 200);
        
    }
}
