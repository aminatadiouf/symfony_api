<?php

namespace App\Controller;

use App\Entity\Formations;
use App\Repository\AuthorRepository;
use App\Repository\FormationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    #[Route('/api/formations', name: 'app_formation', methods: ['GET'])]
    public function getFormationList(FormationsRepository $formationsRepository, SerializerInterface $serializer): JsonResponse
    {
        $formationList = $formationsRepository->findAll();
        $jsonFormationList = $serializer->serialize($formationList, 'json', ['groups' => 'getFormation']);
        return new JsonResponse($jsonFormationList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/formations/{id}', name: 'detailFormations', methods: ['GET'])]
    public function getDetailBook(Formations  $formations, SerializerInterface $serializer): JsonResponse
    {
        $jsonFormation = $serializer->serialize($formations, 'json', ['groups' => 'getFormation']);
        return new JsonResponse($jsonFormation, Response::HTTP_OK, [], true);
    }

    #[Route('/api/formations/{id}', name: 'deleteBook', methods: ['DELETE'])]
    public function deleteFormation(Formations $formations, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($formations);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    #[Route('/api/formations/create', name: 'createFormation', methods: ['POST'])]
    public function registerFormation(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $formation = $serializer->deserialize($request->getContent(), Formations::class, 'json');
        $errors = $validator->validate($formation);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($formation, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $em->persist($formation);
        $em->flush();
        $jsonFormation = $serializer->serialize($formation, 'json', ['groups' => 'getFormation']);
        return new JsonResponse($jsonFormation, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/formations/edit/{id}', name: "updateFormation", methods: ['PUT'])]
    public function updateBook(Request $request, SerializerInterface $serializer, Formations $currentFormations, EntityManagerInterface $em): JsonResponse
    {
        $updatedBook = $serializer->deserialize(
            $request->getContent(),
            Formations::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentFormations]
        );
        $em->persist($updatedBook);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
