<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Traits\ValidationTrait;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api', name: 'api_')]
class ArticleController extends AbstractController
{
    use ValidationTrait;

    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    #[Route('/articles', name: 'article_list', methods:['get'] )]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        $items = $doctrine
            ->getRepository(Article::class)
            ->findAll();

        $data = [];

        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'nom' => $item->getNom(),
                'prix' => $item->getPrix()
            ];
        }

//        return $this->json($data);
        return new JsonResponse($data, Response::HTTP_CREATED);
    }


    #[Route('/articles', name: 'article_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $item = new Article();
        $item->setNom($request->request->get('nom'));
        $item->setPrix($request->request->get('prix'));

        $violations = $this->validator->validate($item);
        if (count($violations) > 0) {
            return $this->handleValidationErrors($violations);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        $data =  [
            'id' => $item->getId(),
            'nom' => $item->getNom(),
            'prix' => $item->getPrix()
        ];

        //return $this->json($data);
        return new JsonResponse($data, Response::HTTP_CREATED);
    }


    #[Route('/articles/{id}', name: 'article_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $item = $doctrine->getRepository(Article::class)->find($id);

        if (!$item) {

            return $this->json('No article found for id ' . $id, 404);
        }

        $data =  [
            'id' => $item->getId(),
            'nom' => $item->getNom(),
            'prix' => $item->getPrix()
        ];

        return $this->json($data);
    }

    #[Route('/articles/{id}', name: 'article_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Article::class)->find($id);

        if (!$item) {
            return $this->json('No article found for id' . $id, 404);
        }

        $item->setNom($request->request->get('nom'));
        $item->setPrix($request->request->get('prix'));
        $entityManager->flush();

        $data =  [
            'id' => $item->getId(),
            'nom' => $item->getNom(),
            'prix' => $item->getPrix()
        ];

        return $this->json($data);
    }

    #[Route('/articles/{id}', name: 'article_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Article::class)->find($id);

        if (!$item) {
            return $this->json('No article found for id' . $id, 404);
        }

        $entityManager->remove($item);
        $entityManager->flush();

        return $this->json('Deleted article successfully with id ' . $id);
    }
}
