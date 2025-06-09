<?php

namespace App\Controller;

use App\Entity\Competicion;
use App\Entity\Partido;
use App\Entity\StatsJugador;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route('/partidos', name: 'ruta_partidos')]
    public function listarPartidos(ManagerRegistry $doctrine): Response
    {
        $partidos = $doctrine->getRepository(Partido::class)->findBy([], ['fecha' => 'DESC'], 8);

        $competiciones = $doctrine->getRepository(Competicion::class)->findAll();

        return $this->render('publico/partidos.html.twig',['partidos' => $partidos,'competiciones' => $competiciones]);
    }

    #[Route('/partido/{idPartido}/estadisticas', name: 'estadisticas_partido')]
    public function estadisticasPartido(ManagerRegistry $doctrine, int $idPartido): Response
    {
        $partido = $doctrine->getRepository(Partido::class)->find($idPartido);
        $estadisticas = $doctrine->getRepository(StatsJugador::class)->findBy(['idPartido' => $partido]);

        return $this->render('publico/estadisticas.html.twig',['estadisticas' => $estadisticas, 'partido' => $partido]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('publico/about.html.twig');
    }

}