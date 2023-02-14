<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'app_stripe_create_session')]
    public function index(EntityManagerInterface $entityManager,Cart $cart, $reference): Response
    {

        $producs_for_stripe = [];
        $YOUR_DOMAIN = 'https://keyboard-store.herokuapp.com';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if(!$order){
            return $this->redirectToRoute('app_order');
        }

        foreach($order->getOrderDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$product_object->getIllustration()],
                    ],
                    ],
                    'quantity' => $product->getQuantity(),
            ];
        }

        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN]
                ],
            ],
                'quantity' => 1,
        ];


        Stripe::setApiKey('sk_test_51MH5s1EopZv0KhqCh3bpfLj0pvlaoCAQKjrN8SvLjHQ1oiAlvy7mtylSz3wU5ZmkeaRhEjdN5C4YREa5wYrlBXrY00qk70HQS2');


        $checkout_session = Session::create([
        'customer_email' => $this->getUser()->getEmail(),
        'line_items' => [[
            $products_for_stripe
        ]],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/commande/merci?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $YOUR_DOMAIN . '/commande/erreur?session_id={CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return $this->redirect($checkout_session->url);
    }
}
