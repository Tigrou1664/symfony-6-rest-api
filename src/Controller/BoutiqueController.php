<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Boutique;
use App\Entity\BoutiqueArticle;
use App\Entity\Utilisateur;
use App\Traits\ValidationTrait;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

        return new JsonResponse($data, Response::HTTP_OK);
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

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route('/boutique/{id}', name: 'shop_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $item = $doctrine->getRepository(Boutique::class)->find($id);

        if (!$item) {
            $error = [
                'code' => '404',
                'error' => 'Boutique introuvable'
            ];
            return new JsonResponse($error, 404);
        }

        $data =  [
            'id' => $item->getId(),
            'nom' => $item->getNom()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/boutique/{id}/infos', name: 'shop_info', methods:['get'] )]
    public function info(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $item = $doctrine->getRepository(Boutique::class)->find($id);

        if (!$item) {
            $error = [
                'code' => '404',
                'error' => 'Boutique introuvable'
            ];
            return new JsonResponse($error, 404);
        }

        $users = $doctrine->getRepository(Utilisateur::class)->findPersonnelByShop($id);

        // Check if user can access shop else return 403
        if (!in_array($this->getUser(), $users)) {
            $error = [
                'code' => '403',
                'error' => 'Accès interdit'
            ];
            return new JsonResponse($error, 403);
        }

        $data =  [
            'id' => $item->getId()
        ];

        $articles = $doctrine->getRepository(Article::class)->findArticlesByShop($id);

        foreach($articles as $article){
            $data['stock'][] = [
                'article' => [
                    'id' => $article['id'],
                    'nom' => $article['nom'],
                    'prix' => $article['prix'],
                ],
                'tarifLocationJour' => $article['tarifLocationJour'],
                'stock' => $article['stock'],
            ];
        }

        foreach($users as $user){
            $data['personnel'][] = [
                'id' => $user->getId(),
                'displayName' => $user->getDisplayName(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/boutique/{id}/article/{articleId}', name: 'shop_article', methods:['get'] )]
    public function article(ManagerRegistry $doctrine, int $id, int $articleId): JsonResponse
    {
        $item = $doctrine->getRepository(Boutique::class)->find($id);

        if (!$item) {
            $error = [
                'code' => '404',
                'error' => 'Boutique introuvable'
            ];
            return new JsonResponse($error, 404);
        }

        $users = $doctrine->getRepository(Utilisateur::class)->findPersonnelByShop($id);

        // Check if user can access shop else return 403
        if (!in_array($this->getUser(), $users)) {
            $error = [
                'code' => '403',
                'error' => 'Accès interdit'
            ];
            return new JsonResponse($error, 403);
        }

        $article = $doctrine->getRepository(Article::class)->findArticleByShop($articleId, $id);

        if (!$article) {
            $error = [
                'code' => '404',
                'error' => 'Article introuvable'
            ];
            return new JsonResponse($error, 404);
        }

        $data =  [
            'id' => $item->getId()
        ];

        $articles = $doctrine->getRepository(Article::class)->findArticlesByShop($id);

        foreach($articles as $article){
            $data['stock'][] = [
                'article' => [
                    'id' => $article['id'],
                    'nom' => $article['nom'],
                    'prix' => $article['prix'],
                ],
                'tarifLocationJour' => $article['tarifLocationJour'],
                'stock' => $article['stock'],
            ];
        }

        foreach($users as $user){
            $data['personnel'][] = [
                'id' => $user->getId(),
                'displayName' => $user->getDisplayName(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/boutique/{id}/articles', name: 'shop_add_article', methods:['post'] )]
    public function addArticle(ManagerRegistry $doctrine, int $id, Request $request): JsonResponse
    {
        $item = $doctrine->getRepository(Boutique::class)->find($id);

        if (!$item) {
            $error = [
                'code' => '404',
                'error' => 'Boutique introuvable'
            ];
            return new JsonResponse($error, 404);
        }

        $users = $doctrine->getRepository(Utilisateur::class)->findPersonnelByShop($id);

        // Check if user can access shop else return 403
        if (!in_array($this->getUser(), $users)) {
            $error = [
                'code' => '403',
                'error' => 'Accès interdit'
            ];
            return new JsonResponse($error, 403);
        }

        // Add article
        $entityManager = $doctrine->getManager();

        $data = [];

        foreach($request->request as $post) {
            $articleInShop = $doctrine->getRepository(BoutiqueArticle::class)->findOneBy(['boutique' => $id, 'article' => $post['article']]);

            // If article is already set => update existing article
            // else add new article
            if($articleInShop) {
                $article = $doctrine->getRepository(Article::class)->findOneBy(['id' => $post['article']]);
                $articleInShop->setTarifLocationJour($post['tarifLocationJour']);
                $articleInShop->setStock($post['stock']);

                $data[] = [
                    'article' => [
                        'id' => $article->getId(),
                        'nom' => $article->getNom(),
                        'prix' => $article->getPrix(),
                    ],
                    'tarifLocationJour' => $articleInShop->getTarifLocationJour(),
                    'stock' => $articleInShop->getStock(),
                ];
            } else {
                $newArticle = $doctrine->getRepository(Article::class)->findOneBy(['id' => $post['article']]);
                if (!$newArticle) {
                    $error = [
                        'code' => '404',
                        'error' => 'Article introuvable'
                    ];
                    return new JsonResponse($error, 404);
                }
                $ba = new BoutiqueArticle();
                $ba->setBoutique($item);
                $ba->setArticle($newArticle);

                $violations = $this->validator->validate($ba);
                if (count($violations) > 0) {
                    return $this->handleValidationErrors($violations);
                }
                $entityManager->persist($ba);
            }
        }

        $entityManager->flush();

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/boutique/{id}', name: 'shop_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Boutique::class)->find($id);

        if (!$item) {
            $error = [
                'code' => '404',
                'error' => 'Boutique introuvable'
            ];
            return new JsonResponse($error, 404);
        }

        $users = $doctrine->getRepository(Utilisateur::class)->findPersonnelByShop($id);

        // Check if user can access shop else return 403
        if (!in_array($this->getUser(), $users)) {
            $error = [
                'code' => '403',
                'error' => 'Accès interdit'
            ];
            return new JsonResponse($error, 403);
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

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/boutique/{id}', name: 'shop_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Boutique::class)->find($id);

        if (!$item) {
            $error = [
                'code' => '404',
                'error' => 'Boutique introuvable'
            ];
            return new JsonResponse($error, 404);
        }

        $users = $doctrine->getRepository(Utilisateur::class)->findPersonnelByShop($id);

        // Check if user can access shop else return 403
        if (!in_array($this->getUser(), $users)) {
            $error = [
                'code' => '403',
                'error' => 'Accès interdit'
            ];
            return new JsonResponse($error, 403);
        }

        $entityManager->remove($item);
        $entityManager->flush();

        return new JsonResponse('Deleted shop successfully with id ' . $id, Response::HTTP_OK);
    }
}
