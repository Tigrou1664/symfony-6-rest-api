<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\Utilisateur;
use App\Traits\ValidationTrait;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api', name: 'api_')]
class BoutiqueController extends AbstractController
{
    use ValidationTrait;

    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    #[Route('/boutiques', name: 'shop_list', methods:['get'] )]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        $items = $doctrine
            ->getRepository(Boutique::class)
            ->findAll();

        $data = [];

        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'nom' => $item->getNom()
            ];
        }

//        return $this->json($data);
        return new JsonResponse($data, Response::HTTP_CREATED);
    }


    #[Route('/boutiques', name: 'shop_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $item = new Boutique();
        $item->setNom($request->request->get('nom'));

        $violations = $this->validator->validate($item);
        if (count($violations) > 0) {
            return $this->handleValidationErrors($violations);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        $data =  [
            'id' => $item->getId(),
            'nom' => $item->getNom()
        ];

        //return $this->json($data);
        return new JsonResponse($data, Response::HTTP_CREATED);
    }


    #[Route('/boutiques/{id}', name: 'shop_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $item = $doctrine->getRepository(Boutique::class)->find($id);

        if (!$item) {

            return $this->json('No shop found for id ' . $id, 404);
        }

        $data =  [
            'id' => $item->getId(),
            'nom' => $item->getNom()
        ];

        return $this->json($data);
    }

    #[Route('/boutiques/{id}/info', name: 'shop_info', methods:['get'] )]
    public function info(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $item = $doctrine->getRepository(Boutique::class)->find($id);

        if (!$item) {
            return $this->json('No shop found for id ' . $id, 404);
        }

        // Check in personnel if user is set else return 403
        if (!$this->getUser() instanceof Utilisateur) {
            return new JsonResponse('Accès interdit', 403);
        }

        $data =  [
            'id' => $item->getId(),
            'nom' => $item->getNom()
        ];

        return $this->json($data);
    }

    #[Route('/boutiques/{id}', name: 'shop_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Boutique::class)->find($id);

        if (!$item) {
            return $this->json('No shop found for id' . $id, 404);
        }

        $item->setNom($request->request->get('nom'));
        $item->setPrix($request->request->get('prix'));

        $violations = $this->validator->validate($item);
        if (count($violations) > 0) {
            return $this->handleValidationErrors($violations);
        }
        
        $entityManager->flush();

        $data =  [
            'id' => $item->getId(),
            'nom' => $item->getNom()
        ];

        return $this->json($data);
    }

    #[Route('/boutiques/{id}', name: 'shop_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Boutique::class)->find($id);

        if (!$item) {
            return $this->json('No shop found for id' . $id, 404);
        }

        $entityManager->remove($item);
        $entityManager->flush();

        return $this->json('Deleted a shop successfully with id ' . $id);
    }
}
