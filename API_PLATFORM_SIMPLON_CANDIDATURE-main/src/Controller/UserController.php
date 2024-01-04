<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/api/registerUser', name: 'app_user', methods: ['POST'])]
    public function registerUser(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordEncoder
    ): JsonResponse {
        // Étape 1: Récupérer les données JSON de la requête.
        $jsonData = $request->getContent();

        // Étape 2: Désérialiser les données JSON dans un objet User.
        $user = $serializer->deserialize($jsonData, User::class, 'json');

        // Étape 3: Chiffrer le mot de passe de l'utilisateur.
        $hashedPassword = $passwordEncoder->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        // Étape 4: Valider les données de l'utilisateur avec le Validator.
        $errors = $validator->validate($user);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        // Étape 5: Enregistrer l'utilisateur dans la base de données.
        $em->persist($user);
        $em->flush();

        // Retourner la réponse.
        $jsonUser = $serializer->serialize($user, 'json');
        return new JsonResponse($jsonUser, JsonResponse::HTTP_CREATED, [], true);
    }
}
