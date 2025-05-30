<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class FirebaseAuthenticator extends AbstractAuthenticator
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new AuthenticationException('Missing or invalid Authorization header');
        }

        $idToken = substr($authHeader, 7);

        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . '/../../config/firebaseAuth.json');

        try {
            $verifiedToken = $firebase->createAuth()->verifyIdToken($idToken);
            $firebaseUid = $verifiedToken->claims()->get('sub');

            return new SelfValidatingPassport(
                new UserBadge($firebaseUid, function () use ($firebaseUid) {
                    $user = $this->em->getRepository(User::class)->findOneBy([
                        'uuid' => $firebaseUid,
                    ]);

                    if (!$user) {
                        $user = new User();
                        $user->setUuid($firebaseUid); // Use the correct setter from your entity
                        $this->em->persist($user);
                        $this->em->flush();
                    }

                    return $user;
                })
            );

        } catch (FailedToVerifyToken $e) {
            throw new AuthenticationException('Firebase token verification failed: ' . $e->getMessage());
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response('Authentication Failed: ' . $exception->getMessage(), 401);
    }
}
