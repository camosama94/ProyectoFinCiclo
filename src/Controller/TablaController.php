<?php
// src/Controller/TablaController.php
namespace App\Controller;

use App\Entity\Equipo;
use App\Entity\Jugador;
use App\Entity\Partido;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\DoctrineConfig;

class TablaController extends AbstractController
{
    #[Route('/tabla/equipos', name: 'tabla_equipos')]
    public function equipos(ManagerRegistry $doctrine): Response
    {
        $equipos = $doctrine->getRepository(Equipo::class)->findAll();
        return $this->render('tablas/equipos.html.twig', ['equipos' => $equipos]);
    }

    #[Route('/tabla/jugadores', name: 'tabla_jugadores')]
    public function jugadores(ManagerRegistry $doctrine): Response
    {
        $jugadores = $doctrine->getRepository(Jugador::class)->findAll();
        $equipos = $doctrine->getRepository(Equipo::class)->findAll();
        return $this->render('tablas/jugadores.html.twig', ['jugadores' => $jugadores, 'equipos' => $equipos]);
    }

    #[Route('/tabla/partidos', name: 'tabla_partidos')]
    public function partidos(ManagerRegistry $doctrine): Response
    {
        $equiposLocal = $doctrine->getRepository(Equipo::class)->findAll();
        $equiposVisitante = $doctrine->getRepository(Equipo::class)->findAll();

        $connection = $doctrine->getConnection();
        $sql = "SELECT * FROM user WHERE JSON_CONTAINS(roles, '\"ROLE_STATS\"')";
        $stmt = $connection->prepare($sql);
        $result = $stmt->executeQuery();
        $users = $result->fetchAllAssociative();

        $partidos = $doctrine->getRepository(Partido::class)->findAll();
        return $this->render('tablas/partidos.html.twig', ['partidos' => $partidos, 'equiposLocal' => $equiposLocal, 'equiposVisitante' => $equiposVisitante, 'users' => $users]);
    }

    #[Route('/tabla/usuarios', name: 'tabla_usuarios')]
    public function usuarios(ManagerRegistry $doctrine): Response
    {
        $usuarios = $doctrine->getRepository(User::class)->findAll();
        return $this->render('tablas/usuarios.html.twig', ['usuarios' => $usuarios]);
    }
}
