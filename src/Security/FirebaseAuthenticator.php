<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseAuthenticator extends AbstractAuthenticator
{
    private EntityManagerInterface $em;
    private Auth $firebaseAuth;

    public function __construct(EntityManagerInterface $em, string $firebaseCredentialsPath)
    {
        $this->em = $em;

        $this->firebaseAuth = (new Factory())
            ->withServiceAccount($firebaseCredentialsPath)
            ->createAuth();
    }

    public function supports(Request $request): ?bool
    {
        $authHeader = $request->headers->get('Authorization');
        return $authHeader && str_starts_with($authHeader, 'Bearer ');
    }

    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization');
        $idToken = substr($authHeader, 7); // Remove "Bearer "

        try {
            $verifiedToken = $this->firebaseAuth->verifyIdToken($idToken);
            $firebaseUid = $verifiedToken->claims()->get('sub');

            return new SelfValidatingPassport(
                new UserBadge($firebaseUid, function () use ($firebaseUid) {
                    $user = $this->em->getRepository(User::class)->findOneBy(['uuid' => $firebaseUid]);

                    if (!$user) {
                        $user = new User();
                        $user->setUuid($firebaseUid); // Ensure this method exists
                        $this->em->persist($user);
                        $this->em->flush();
                    }

                    return $user;
                })
            );

        } catch (FailedToVerifyToken $e) {
            throw new AuthenticationException('Invalid Firebase token');
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null; // Continue to controller
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'error' => 'Authentication failed',
            'message' => $exception->getMessage()
        ], 401);
    }
}
