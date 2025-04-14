<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PruebaController extends AbstractController
{
    #[Route('/pruebaAdmin', name: 'ruta_prueba')]
    public function index(): Response
    {
        return $this->render('admin/admin.html.twig');
    }
}

