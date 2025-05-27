<?php

namespace App\Controller;

use App\Entity\Competicion;
use App\Entity\Partido;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{

    #[Route('/partidos', name: 'ruta_partidos')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $partidos = $doctrine->getRepository(Partido::class)->findBy([], ['fecha' => 'DESC'], 8);

        $competiciones = $doctrine->getRepository(Competicion::class)->findAll();

        return $this->render('publico/partidos.html.twig',['partidos' => $partidos,'competiciones' => $competiciones]);
    }

}