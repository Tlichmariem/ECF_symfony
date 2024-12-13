<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): ?bool
    {
        // Vérifie si la requête est pour la route de connexion et si c'est une méthode POST
        return $request->attributes->get('_route') === 'login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        // Récupère les informations de connexion du formulaire
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Crée un Passport avec les badges d'utilisateur et de mot de passe
        return new Passport(
            new UserBadge($email), // Crée un badge pour l'utilisateur (ici, l'email)
            new PasswordCredentials($password) // Crée un badge pour le mot de passe
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse('/');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse('/login');
    }
}
