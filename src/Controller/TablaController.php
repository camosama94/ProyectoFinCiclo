<?php
// src/Controller/TablaController.php
namespace App\Controller;

use App\Entity\Competicion;
use App\Entity\Equipo;
use App\Entity\Jugador;
use App\Entity\Partido;
use App\Entity\PeticionRol;
use App\Entity\User;
use App\Enum\EstadosPeticionesRol;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\DoctrineConfig;

class TablaController extends AbstractController
{

    #[Route('/tabla/competiciones', name: 'tabla_competiciones')]
    public function competiciones(ManagerRegistry $doctrine): Response
    {
        $competiciones = $doctrine->getRepository(Competicion::class)->findBy(['admin' => $this->getUser()->getId()]);
        return $this->render('tablas/competiciones.html.twig', ['competiciones' => $competiciones]);
    }

    #[Route('/tabla/equipos', name: 'tabla_equipos')]
    public function equipos(ManagerRegistry $doctrine, Security $security): Response
    {
        $competiciones = $doctrine->getRepository(Competicion::class)->findBy(['admin' => $this->getUser()->getId()]);
        $usuario = $security->getUser();
        $equipos = $doctrine->getRepository(Equipo::class)->createQueryBuilder('e')
            ->join('e.competicion', 'c')
            ->where('c.admin = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();
        return $this->render('tablas/equipos.html.twig', ['equipos' => $equipos, 'competiciones' => $competiciones]);
    }

    #[Route('/tabla/jugadores', name: 'tabla_jugadores')]
    public function jugadores(ManagerRegistry $doctrine, Security $security): Response
    {
        $usuario = $security->getUser();
        $equipos = $doctrine->getRepository(Equipo::class)->createQueryBuilder('e')
            ->join('e.competicion', 'c')
            ->where('c.admin = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();

        $jugadores = $doctrine->getRepository(Jugador::class)->createQueryBuilder('j')
            ->join('j.idEquipo', 'e')
            ->join('e.competicion', 'c')
            ->where('c.admin = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();

        return $this->render('tablas/jugadores.html.twig', ['jugadores' => $jugadores, 'equipos' => $equipos]);
    }

    #[Route('/tabla/partidos', name: 'tabla_partidos')]
    public function partidos(ManagerRegistry $doctrine, Security $security): Response
    {
        $usuario = $security->getUser();
        $equiposLocal = $doctrine->getRepository(Equipo::class)->createQueryBuilder('e')
            ->join('e.competicion', 'c')
            ->where('c.admin = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();

        $equiposVisitante = $doctrine->getRepository(Equipo::class)->createQueryBuilder('e')
            ->join('e.competicion', 'c')
            ->where('c.admin = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();

        $competiciones = $doctrine->getRepository(Competicion::class)->findBy(['admin' => $this->getUser()->getId()]);

        $users = $doctrine->getRepository(User::class)->createQueryBuilder('u')
            ->distinct()
            ->join('u.competicionesEstadista', 'c')
            ->join('c.admin', 'admin')
            ->where('admin = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();

        $partidos = $doctrine->getRepository(Partido::class)->createQueryBuilder('p')
            ->join('p.competicion', 'c')
            ->where('c.admin = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();

        return $this->render('tablas/partidos.html.twig', ['partidos' => $partidos, 'equiposLocal' => $equiposLocal, 'equiposVisitante' => $equiposVisitante, 'users' => $users, 'competiciones' => $competiciones]);
    }

    #[Route('/tabla/usuarios', name: 'tabla_usuarios')]
    public function usuarios(ManagerRegistry $doctrine, Security $security): Response
    {
        $usuario = $security->getUser();

        $estadistas = $doctrine->getRepository(User::class)->createQueryBuilder('u')
            ->distinct()
            ->join('u.competicionesEstadista', 'c')
            ->join('c.admin', 'admin')
            ->where('admin = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();

        $peticionesPendientes = $doctrine->getRepository(PeticionRol::class)->createQueryBuilder('p')
            ->join('p.competicion', 'c')
            ->where('p.status = :estado')
            ->andWhere('p.rol = :rol')
            ->andWhere('c.admin = :usuario')
            ->setParameter('estado', EstadosPeticionesRol::PENDING)
            ->setParameter('rol', 'ROLE_STATS')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();

        return $this->render('tablas/usuarios.html.twig', ['usuarios' => $estadistas, 'peticionesPendientes' => $peticionesPendientes]);
    }
}
