<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Entity\PeticionRol;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/usuario/gestion', name: 'user_gestion')]
    public function index(): Response
    {
        return $this->render('user/user.html.twig');
    }

    #[Route('/form/usuario', name: 'form_datos')]
    public function equipos(ManagerRegistry $doctrine): Response
    {
        return $this->render('forms/datosUsuario.html.twig');
    }
    #[Route('/usuario/{id}/peticion-rol', name:'request_stats', methods:['POST'])]
    public function requestStats(int $id, ManagerRegistry $doctrine, Security $security): JsonResponse
    {
        $user = $security->getUser();
        if (!$user || $user->getId() !== $id) {
            return new JsonResponse(['error'=>'Acceso denegado'], 403);
        }

        // Verifica que no haya solicitud pendiente
        foreach ($user->getPeticionRol() as $req) {
            if ($req->getRol() === 'ROLE_STATS' && $req->getStatus() === 'PENDING') {
                return new JsonResponse(['error'=>'Ya hay solicitud pendiente'], 400);
            }
        }

        $pr = new PeticionRol();
        $pr->setUsuario($user);
        $pr->setRol('ROLE_STATS');
        $pr->setCreatedAt(new \DateTimeImmutable('now'));

        $em = $doctrine->getManager();
        $em->persist($pr);
        $em->flush();

        // Opcional: notificar a los admins via email o mensaje
        return new JsonResponse(['success'=>true]);
    }


}