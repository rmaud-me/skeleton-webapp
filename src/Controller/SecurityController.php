<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security_login')]
    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }

    #[Route('/register', name: 'security_register')]
    public function register(): Response
    {
        return $this->render('security/register.html.twig');
    }

    #[Route('/forgot-password', name: 'security_forgot_password')]
    public function forgotPassword(): Response
    {
        return $this->render('security/forgot-password.html.twig');
    }
}
