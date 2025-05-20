<?php

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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PruebaController extends AbstractController
{
    #[Route('/pruebaAdmin', name: 'ruta_prueba')]
    public function index(ManagerRegistry $doctrine, Security $security): Response
    {
        $usuario = $security->getUser();

        $peticionesPendientes = $doctrine->getRepository(PeticionRol::class)
            ->createQueryBuilder('pr')
            ->join('pr.competicion', 'c')
            ->where('pr.status = :estado')
            ->andWhere('c.admin = :usuario')
            ->setParameter('estado', EstadosPeticionesRol::PENDING)
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();

        return $this->render('admin/admin.html.twig',['peticionesPendientes' => $peticionesPendientes]);
    }

    #[Route('/equipo/new', name: 'crear_equipo', methods: ['POST'])]
    public function crearEquipo(Request $request, ManagerRegistry $doctrine): Response
    {
        $nombre = $request->request->get('nombreEquipo');
        $entrenador = $request->request->get('nombreEntrenador');
        $idCompeticion = $request->request->get('competicion');
        $competicion = $doctrine->getRepository(Competicion::class)->find($idCompeticion);

        $equipo = new Equipo();
        $equipo->setNombre($nombre);
        $equipo->setEntrenador($entrenador);
        $equipo->setCompeticion($competicion);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($equipo);
        $entityManager->flush();

        return $this->redirect('/pruebaAdmin#equipos');

    }

    #[Route('/equipo/{id}', name: 'ver_equipo', methods: ['GET'])]
    public function verEquipo(Request $request, ManagerRegistry $doctrine): Response
    {
        $id = $request->get('id');
        $entityManager = $doctrine->getManager();
        $equipo = $entityManager->getRepository(Equipo::class)->find($id);
        return $this->json([
            'id' => $equipo->getId(),
            'nombre' => $equipo->getNombre(),
            'entrenador' => $equipo->getEntrenador(),
            'competicion' => $equipo->getCompeticion()->getId()
        ]);
    }

    #[Route('/equipo/{id}/edit', name: 'editar_equipo', methods: ['PUT'])]
    public function editarEquipo(Request $request, ManagerRegistry $doctrine, Equipo $equipo): Response
    {
        $nombre = $request->request->get('nombreEquipo');
        $entrenador = $request->request->get('nombreEntrenador');
        $competicionId = $request->request->get('competicionId');
        $competicion = $doctrine->getRepository(Competicion::class)->find($competicionId);
        $equipo->setNombre($nombre);
        $equipo->setEntrenador($entrenador);
        $equipo->setCompeticion($competicion);
        $entityManager = $doctrine->getManager();
        $entityManager->flush();
        return $this->redirect('/pruebaAdmin#equipos');
    }

    #[Route('/equipo/{id}', name: 'borrar_equipo', methods: ['DELETE'])]
    public function borrarEquipo(Request $request, ManagerRegistry $doctrine,): Response
    {
        $id = $request->get('id');
        $entityManager = $doctrine->getManager();
        $equipo = $entityManager->getRepository(Equipo::class)->find($id);
        $entityManager->remove($equipo);
        $entityManager->flush();
        return $this->redirect('/pruebaAdmin#equipos');
    }


    #[Route('/jugador/new', name: 'crear_jugador', methods: ['POST'])]
    public function crearjugador(Request $request, ManagerRegistry $doctrine): Response
    {
        $nombre = $request->request->get('nombreJugador');
        $idEquipo = $request->request->get('nombreEquipo');
        $equipo = $doctrine->getRepository(Equipo::class)->find($idEquipo);
        $posicion = $request->request->get('posicion');
        $altura = $request->request->get('altura');
        $dorsal = $request->request->get('dorsal');

        $jugador = new Jugador();
        $jugador->setNombre($nombre);
        $jugador->setIdEquipo($equipo);
        $jugador->setPosicion($posicion);
        $jugador->setAltura($altura);
        $jugador->setDorsal($dorsal);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($jugador);
        $entityManager->flush();

        return $this->redirect('/pruebaAdmin#jugadores');

    }


    #[Route('/jugador/{id}', name: 'ver_jugador', methods: ['GET'])]
    public function verJugador(Request $request, ManagerRegistry $doctrine): Response
    {
        $id = $request->get('id');
        $entityManager = $doctrine->getManager();
        $jugador = $entityManager->getRepository(Jugador::class)->find($id);

        return $this->json([
            'id' => $jugador->getId(),
            'nombre' => $jugador->getNombre(),
            'equipo' => $jugador->getIdEquipo()->getId(),
            'posicion' => $jugador->getPosicion(),
            'altura' => $jugador->getAltura(),
            'dorsal' => $jugador->getDorsal(),
        ]);
    }

    #[Route('/jugador/{id}/edit', name: 'editar_jugador', methods: ['PUT'])]
    public function editarjugador(Request $request, ManagerRegistry $doctrine, Jugador $jugador): Response
    {

        $nombre = $request->request->get('nombreJugador');
        $idEquipo = $request->request->get('idEquipo');
        $equipo = $doctrine->getRepository(Equipo::class)->find($idEquipo);
        $posicion = $request->request->get('posicion');
        $altura = $request->request->get('altura');
        $dorsal = $request->request->get('dorsal');

        $jugador->setNombre($nombre);
        $jugador->setIdEquipo($equipo);
        $jugador->setPosicion($posicion);
        $jugador->setAltura($altura);
        $jugador->setDorsal((int)$dorsal);



        $entityManager = $doctrine->getManager();
        $entityManager->flush();
        return $this->redirect('/pruebaAdmin#jugadores');
    }

    #[Route('/jugador/{id}', name: 'borrar_jugador', methods: ['DELETE'])]
    public function borrarJugador(Request $request, ManagerRegistry $doctrine,): Response
    {
        $id = $request->get('id');
        $entityManager = $doctrine->getManager();
        $jugador = $entityManager->getRepository(Jugador::class)->find($id);

        $entityManager->remove($jugador);

        $entityManager->flush();
        return $this->redirect('/pruebaAdmin#jugadores');
    }

    #[Route('/partido/new', name: 'crear_partido', methods: ['POST'])]
    public function crearPartido(Request $request, ManagerRegistry $doctrine): Response
    {
        $idLocal = $request->request->get('nombreEquipoLocal');
        $local = $doctrine->getRepository(Equipo::class)->find($idLocal);
        $idVisitante = $request->request->get('nombreEquipoVisitante');
        $visitante = $doctrine->getRepository(Equipo::class)->find($idVisitante);
        $idUsuario = $request->request->get('estadista');
        $user = $doctrine->getRepository(User::class)->find($idUsuario);
        $fecha = new \DateTime($request->request->get('fecha'));
        $localizacion = $request->request->get('localizacion');
        $idCompeticion = $request->request->get('competicion');
        $competicion = $doctrine->getRepository(Competicion::class)->find($idCompeticion);

        $partido = new Partido();
        $partido->setIdEquipoLocal($local);
        $partido->setIdEquipoVisitante($visitante);
        $partido->setFecha($fecha);
        $partido->setLocalizacion($localizacion);
        $partido->setActivo(false);
        $partido->setIdUsuario($user);
        $partido->setCompeticion($competicion);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($partido);
        $entityManager->flush();

        return $this->redirect('/pruebaAdmin#partidos');

    }

    #[Route('/partido/{id}', name: 'ver_partido', methods: ['GET'])]
    public function verPartido(Request $request, ManagerRegistry $doctrine): Response
    {
        $id = $request->get('id');
        $entityManager = $doctrine->getManager();
        $partido = $entityManager->getRepository(Partido::class)->find($id);

        return $this->json([
            'id' => $partido->getId(),
            'competicion' => $partido->getCompeticion()->getId(),
            'equipoLocal' => $partido->getIdEquipoLocal()->getId(),
            'equipoVisitante' => $partido->getIdEquipoVisitante()->getId(),
            'localizacion' => $partido->getLocalizacion(),
            'fecha' => $partido->getFecha(),
            'estadista' => $partido->getIdUsuario()->getId(),
        ]);
    }

    #[Route('/partido/{id}/edit', name: 'editar_partido', methods: ['PUT'])]
    public function editarPartido(Request $request, ManagerRegistry $doctrine, Partido $partido): Response
    {
        $idLocal = $request->request->get('EquipoLocal');
        $local = $doctrine->getRepository(Equipo::class)->find($idLocal);

        $idVisitante = $request->request->get('EquipoVisitante');
        $visitante = $doctrine->getRepository(Equipo::class)->find($idVisitante);

        $fecha = new \DateTime($request->request->get('fecha'));
        $localizacion = $request->request->get('localizacion');

        $idUser = $request->request->get('estadista');
        $user = $doctrine->getRepository(User::class)->find($idUser);

        $partido->setIdEquipoLocal($local);
        $partido->setIdEquipoVisitante($visitante);
        $partido->setFecha($fecha);
        $partido->setLocalizacion($localizacion);
        $partido->setIdUsuario($user);

        $entityManager = $doctrine->getManager();
        $entityManager->flush();
        return $this->redirect('/pruebaAdmin#partidos');
    }

    #[Route('/partido/{id}', name: 'borrar_partido', methods: ['DELETE'])]
    public function borrarPartido(Request $request, ManagerRegistry $doctrine,): Response
    {
        $id = $request->get('id');
        $entityManager = $doctrine->getManager();
        $partido = $entityManager->getRepository(Partido::class)->find($id);

        $entityManager->remove($partido);

        $entityManager->flush();
        return $this->redirect('/pruebaAdmin#partidos');
    }

    #[Route('/admin/peticion/{id}/aprobar', name: 'aprobar_peticion_rol', methods: ['POST'])]
    public function aprobarPeticionRol(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $peticion = $entityManager->getRepository(PeticionRol::class)->find($id);

        if (!$peticion) {
            throw $this->createNotFoundException('Petición no encontrada');
        }

        $usuario = $peticion->getUsuario();
        $roles = $usuario->getRoles();

        if (!in_array('ROLE_STATS', $roles)) {
            $roles[] = 'ROLE_STATS';
            $usuario->setRoles($roles);
        }

        $competicion = $peticion->getCompeticion();
        $usuario->addCompeticioneEstadista($competicion);

        $peticion->setStatus(EstadosPeticionesRol::APPROVED);
        $entityManager->flush();

        return new Response('Petición aprobada');
    }


    #[Route('/admin/peticion/{id}/rechazar', name: 'rechazar_peticion_rol', methods: ['POST'])]
    public function rechazarPeticionRol(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $peticion = $entityManager->getRepository(PeticionRol::class)->find($id);

        if (!$peticion) {
            throw $this->createNotFoundException('Petición no encontrada');
        }

        $peticion->setStatus(EstadosPeticionesRol::REJECTED);
        $entityManager->flush();

        return new Response('Petición rechazada');
    }


    #[Route('/usuario/{id}/{idComp}', name: 'borrar_competición_usuario', methods: ['PUT'])]
    public function borrarCompeticionUsuario(Request $request, ManagerRegistry $doctrine,): Response
    {
        $id = $request->get('id');
        $idComp = $request->get('idComp');
        $competicion = $doctrine->getRepository(Competicion::class)->find($idComp);
        $entityManager = $doctrine->getManager();
        $usuario = $entityManager->getRepository(User::class)->find($id);

        $usuario->removeCompeticioneEstadista($competicion);

        $entityManager->flush();
        return $this->redirect('/pruebaAdmin#usuarios');
    }

    #[Route('/competicion/new', name: 'crear_competicion', methods: ['POST'])]
    public function crearCompeticion(Request $request, ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage): Response
    {
        $nombre = $request->request->get('nombreCompeticion');

        $competicion = new Competicion();
        $competicion->setNombre($nombre);

        $usuario = $this->getUser();

        if (!in_array('ROLE_ADMIN_LIGA', $usuario->getRoles())) {
            $usuario->setRoles(array_merge($usuario->getRoles(), ['ROLE_ADMIN_LIGA']));
        }
        if (!in_array('ROLE_STATS', $usuario->getRoles())) {
            $usuario->setRoles(array_merge($usuario->getRoles(), ['ROLE_STATS']));
        }


        $competicion->setAdmin($usuario);
        $competicion->addEstadista($usuario);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($competicion);
        $entityManager->flush();

        $newToken = new UsernamePasswordToken($usuario, 'main', $usuario->getRoles());
        $tokenStorage->setToken($newToken);


        return $this->redirect('/pruebaAdmin#competiciones');

    }

    #[Route('/competicion/{id}', name: 'ver_competicion', methods: ['GET'])]
    public function verCompeticion(Request $request, ManagerRegistry $doctrine): Response
    {
        $id = $request->get('id');
        $entityManager = $doctrine->getManager();
        $competicion = $entityManager->getRepository(Competicion::class)->find($id);
        return $this->json([
            'id' => $competicion->getId(),
            'nombre' => $competicion->getNombre(),
        ]);
    }

    #[Route('/competicion/{id}/edit', name: 'editar_competicion', methods: ['PUT'])]
    public function editarCompeticion(Request $request, ManagerRegistry $doctrine, Competicion $competicion): Response
    {

        $nombre = $request->request->get('nombreCompeticion');
        $competicion->setNombre($nombre);

        $entityManager = $doctrine->getManager();
        $entityManager->flush();
        return $this->redirect('/pruebaAdmin#competiciones');
    }

    #[Route('/competicion/{id}', name: 'borrar_competicion', methods: ['DELETE'])]
    public function borrarCompeticion(Request $request, ManagerRegistry $doctrine,): Response
    {
        $id = $request->get('id');
        $entityManager = $doctrine->getManager();
        $competicion = $entityManager->getRepository(Competicion::class)->find($id);
        $entityManager->remove($competicion);
        $entityManager->flush();
        return $this->redirect('/pruebaAdmin#competiciones');
    }
}

