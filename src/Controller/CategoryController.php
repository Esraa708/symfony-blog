<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CategoryController extends AbstractController
{
    public function __construct(private CategoryRepository $repo)
    {
    }

    #[Route('api/category', name: 'list_categories', methods: 'get')]
    public function listCategories(Request $request): JsonResponse
    {
        $categories = $this->repo->getAllCategories();
        $response = new JsonResponse(['categories' => $categories]);
        return $response;
    }


    #[Route('api/category', name: 'create_category', methods: 'post')]
    public function createcategory(Request $request): Response
    {
        $categoryData = $request->toArray()['category'];
        $category = new Category();
        $category->name = $categoryData['name'] ?? null;
        $category->setType($categoryData['type'] ?? null);
        try {
            $this->repo->add($category);
        } catch (ORMException | OptimisticLockException $e) {
            return new Response($e->getCode() . $e->getMessage(), 400);
        }
        return new JsonResponse('Saved new category with id ' . $category->getId());
    }


    #[Route('api/category/{id}', name: 'update_category', methods: 'put')]
    public function updateCategory(Request $request, Category $category)
    {
        if (!$category) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }

        $categoryData = $request->toArray()['category'];
        $category->name = $categoryData['name'] ?? null;
        $category->setType($categoryData['type'] ?? null);
        try {
            $this->repo->updateCategory($category);
        } catch (ORMException | OptimisticLockException $e) {
            return new Response($e->getCode() . $e->getMessage(), 400);
        }
        return new JsonResponse(["category" => $category]);
    }
    #[Route('api/category/{id}', name: 'update_category', methods: 'delete')]
    public function deleteCategory(Category $category): JsonResponse
    {
        if (!$category) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }
        try {
            $this->repo->remove($category);
        } catch (ORMException | OptimisticLockException $e) {
            return new Response($e->getCode() . $e->getMessage(), 400);
        }
        return new JsonResponse('deleted successfully', 200);
    }
}
