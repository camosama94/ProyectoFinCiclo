<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
public function __construct(private RouterInterface $router) {}

public function handle(Request $request, \Symfony\Component\Security\Core\Exception\AccessDeniedException $accessDeniedException): ?Response
    {
        $request->getSession()->getFlashBag()->add('error', 'No tienes permiso para acceder a esa sección.');

        return new RedirectResponse($this->router->generate('app_login'));
    }
}
