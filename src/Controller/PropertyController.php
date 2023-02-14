<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    #[Route('/privacy-policy', name: 'app_property_privacy')]
    public function privacy(): Response
    {
        return $this->render('property/privacy.html.twig');
    }

    #[Route('/terms-conditions', name: 'app_property_terms')]
    public function terms(): Response
    {
        return $this->render('property/terms.html.twig');
    }
}
