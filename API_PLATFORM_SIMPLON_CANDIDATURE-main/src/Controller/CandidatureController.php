<?php

namespace App\Controller;

use App\Entity\Candidatures;
use App\Repository\UsersRepository;
use App\Repository\FormationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CandidaturesRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CandidatureController extends AbstractController
{
    #[Route('/api/candidature', name: 'app_candidature', methods: ['POST'])]
    public function create(SerializerInterface $serializer,Request $request,Security $security,FormationsRepository $form,UserRepository $usersRepo,EntityManagerInterface $em): JsonResponse
    {
        $cand = $serializer->deserialize($request->getContent(),Candidatures::class,'json');
        $userObjet = $security->getUser();
        $data = $request->toArray();
        $idFormation = $data['formation']??-1;
        $cand->setUser($userObjet);
        $cand->setFormation($form->find($idFormation));
        $em->persist($cand);
        $em->flush();
        return new JsonResponse($serializer->serialize($cand, 'json', ['groups' => 'getCandidature']), JsonResponse::HTTP_OK, [], true); 
    }

    #[Route('/api/candidatures/accepted/{id}', name: 'candidature_accepted', methods: ['PUT'])]
    public function getAcceptedCandidatures(CandidaturesRepository $candidatureRepository, SerializerInterface $serializer): JsonResponse
    {
        $acceptedCandidatures = $candidatureRepository->findBy(['is_accepted' => true]);

        $data = $serializer->serialize($acceptedCandidatures, 'json',  ['groups' => 'getCandidature']);

        return new JsonResponse($data, JsonResponse::HTTP_OK,[],true);
    }

    #[Route('/api/candidatures/refused', name: 'candidature_refused', methods: ['GET'])]
    public function getRefusedCandidatures(CandidaturesRepository $candidatureRepository, SerializerInterface $serializer): JsonResponse
    {
        $acceptedCandidatures = $candidatureRepository->findBy(['is_accepted' => false]);

        $data = $serializer->serialize($acceptedCandidatures, 'json',  ['groups' => 'getCandidature']);

        return new JsonResponse($data, JsonResponse::HTTP_OK,[],true);
    }
}
