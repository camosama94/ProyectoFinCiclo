<?php

namespace App\Controller;

use App\Entity\Accion;
use App\Entity\Competicion;
use App\Entity\Jugador;
use App\Entity\Partido;
use App\Entity\StatsJugador;
use App\Enum\TipoAccion;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class StatsController extends AbstractController
{
    #[Route('/estadisticas/{idPartido}/', name: 'ruta_hacer_stats')]
    public function index(ManagerRegistry $doctrine, int $idPartido): Response
    {
        $partido = $doctrine->getRepository(Partido::class)->find($idPartido);
        $usuario = $this->getUser();

        $acciones = $doctrine->getRepository(Accion::class)->findBy(['idPartido' => $partido],['minuto' => 'ASC']);

        $stats = $doctrine->getRepository(StatsJugador::class)->findBy(['idPartido' => $partido]);

        if (!$partido || $partido->getIdUsuario() !== $usuario) {
            $this->addFlash('error', 'No tienes permiso para acceder a ese partido.');
            return $this->redirectToRoute('ruta_partidos');
        }

        if ($partido->isFinalizado()) {
            $this->addFlash('error', 'Las estadisticas para este partido ya se han realizado.');
            return $this->redirectToRoute('ruta_partidos');
        }


        return $this->render('estadisticas/hacerEstadisticas.html.twig', ['partido' => $partido, 'acciones' => $acciones, 'stats' => $stats]);
    }

    #[Route('/estadisticas/{idPartido}/{idAccion}/', name: 'ruta_accion_nueva', methods: ['POST']) ]
    public function accionNueva(ManagerRegistry $doctrine, int $idPartido, string $idAccion, Request $request): JsonResponse
    {
        $usuario = $this->getUser();
        $entityManager = $doctrine->getManager();


        $partido = $doctrine->getRepository(Partido::class)->find($idPartido);
        $jugadorId = $request->request->get('jugador_id');
        $jugador = $entityManager->getRepository(Jugador::class)->find($jugadorId);
        $valor = $request->request->get('valor', 0);
        $tiempo = $request->request->get('tiempo', 0);
        $minuto = \DateTime::createFromFormat('H:i:s', gmdate('H:i:s', $tiempo));




        // Seguridad: comprobar acceso
        if (!$partido || $partido->getIdUsuario() !== $usuario) {
            $this->addFlash('error', 'No tienes permiso para este partido.');
            return new JsonResponse(['error' => 'No tienes permiso para este partido.'], 403);
        }

        // Validar tipo de acción (en base al enum que tú defines)

        $tiposPermitidos = array_map(
            fn(TipoAccion $accion) => $accion->value,
            TipoAccion::cases()
        );

        if (!in_array($idAccion, $tiposPermitidos)) {
            $this->addFlash('error', 'Tipo de acción no válido.');
            return new JsonResponse(['error' => 'Tipo de acción no válido.'], 400);
        }

        if ($idAccion === 'TA') {
            if($jugador->getIdEquipo()->getNombre() === $partido->getIdEquipoLocal()->getNombre()){
                $partido->setPuntosLocal($partido->getPuntosLocal() + $valor);
            }else{
                $partido->setPuntosVisitante($partido->getPuntosVisitante() + $valor);
            }
        }

        // Crear la acción (ejemplo genérico)
        $accion = new Accion();
        $accion->setIdPartido($partido);
        $accion->setIdJugador($jugador); // Deberás completar esto con lógica adicional (jugador seleccionado, etc.)
        $accion->setTipoDeAccion(TipoAccion::from($idAccion));
        $accion->setMinuto($minuto);
        $accion->setValor($valor);

        $entityManager->persist($accion);
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'mensaje' => 'Acción registrada correctamente.',
            'accion_id' => $accion->getId()
        ]);
    }

    #[Route('/estadisticas/{idPartido}/finalizar/', name: 'ruta_finalizar_partido', methods: ['PUT']) ]
    public function finPartido(ManagerRegistry $doctrine, int $idPartido): JsonResponse
    {
        $partido = $doctrine->getRepository(Partido::class)->find($idPartido);
        $entityManager = $doctrine->getManager();

        $partido->setFinalizado(true);
        $entityManager->flush();
        return new JsonResponse(['success' => true, 'message' => 'Partido finalizado correctamente.']);

    }

    #[Route('/estadisticas/{idAccion}/borrar/', name: 'ruta_borrar_accion', methods: ['DELETE'])]
    public function borrarAccion(ManagerRegistry $doctrine, int $idAccion): JsonResponse
    {
        $accion = $doctrine->getRepository(Accion::class)->find($idAccion);

        if (!$accion) {
            return new JsonResponse(['success' => false, 'message' => 'Acción no encontrada.'], 404);
        }

        $partido = $doctrine->getRepository(Partido::class)->find($accion->getIdPartido());

        if($accion->getTipoDeAccion() == TipoAccion::TA){
            if($accion->getIdJugador()->getIdEquipo()->getId() == $partido->getIdEquipoLocal()->getId()){
                $partido->setPuntosLocal($partido->getPuntosLocal() - $accion->getValor());
            }else{
                $partido->setPuntosVisitante($partido->getPuntosVisitante() - $accion->getValor());
            }
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($accion);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Acción borrada correctamente.']);
    }

}