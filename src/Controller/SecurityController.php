<?php

declare (strict_types=1);
namespace App\Controller;

use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class SecurityController extends AbstractController{

    public const SCOPES = [
        'google' => []
    ];

    #[Route('/login', name:'auth_oauth_login',methods:['GET'])]
    public function login(): Response{
        if($this->getUser()){
            return $this->redirectToRoute(route:'index');
        }

        return $this->render(view:'security/login.html.twig');
    }

    #[Route('/oauth/connect/{service}', name: 'auth_oauth_connect', methods:['GET'])]
    public function connect(string $service, ClientRegistry $clientRegistry):RedirectResponse{
        if(! in_array($service,array_keys(self::SCOPES), strict:true)){
            throw $this->createNotFoundException();
        }
        return $clientRegistry
            ->getClient($service)
            ->redirect(self::SCOPES[$service],[]);
    }

    #[Route('/oauth/check/{service}', name:'auth_oauth_check', methods:['GET','POST'])]
    public function check(): Response{
        
        return $this->redirectToRoute(route:'home');
    }

    #[Route('/logout', name:'auth_oauth_logout', methods:['GET'])]
    public function logout(): never{
        throw new Exception('Logout ok!');
    }
}