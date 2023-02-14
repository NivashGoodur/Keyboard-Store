<?php
 
namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
 
 
class Cart
{
    private $stack;
    private $entityManager;
    public $numberProducts;
 
    public function __construct(RequestStack $stack, EntityManagerInterface $entityManager)
 
    {
        $this->stack = $stack;
        $this->entityManager = $entityManager;
    }
 
    public function add($id)
    {
 
        $session = $this->stack->getSession();
        $cart = $session->get('cart', []);
 
        if(!empty($cart[$id])){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
 
 
        $session->set('cart', $cart);
    }
 
    public function get()
    {
        $methodget = $this->stack->getSession();
        return $methodget->get('cart');
    }
 
    public function remove(){
 
        $session = $this->stack->getSession();
        
        $methodremove = $this->stack->getSession();
        $session->set('numberProducts', '0');
        return $methodremove->remove('cart');
    }

    public function delete($id)
    {
        $session = $this->stack->getSession();
        $cart = $session->get('cart', []);
        unset($cart[$id]);
        return $session->set('cart',$cart);
    }
 
    public function decrease($id)
    {
        $session = $this->stack->getSession();
        $cart = $session->get('cart', []);
        if ($cart[$id] > 1) {
            $cart[$id]--;
            //retirer une quantité
        } else {
            unset($cart[$id]);
        }
        
        
        return $session->set('cart',$cart);
       //verifier si la quantité de notre produit = 1
    }

    public function getFull() 
    {

        $cartComplete = [];

        if ($this->get()) {
            foreach((array)$this->get() as $id => $quantity) {
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

                if (!$product_object) {
                    $this->delete($id);
                    continue;
                }

                $cartComplete[] = [
                    'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }

    public function getNumberProducts()
    {
        $session = $this->stack->getSession();

        foreach((array)$this->get() as $id => $quantity) {
            $this->numberProducts += $quantity;
        }

        $session->set('numberProducts', $this->numberProducts);
    }
}