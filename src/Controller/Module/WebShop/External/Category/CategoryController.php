<?php

namespace App\Controller\Module\WebShop\External\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @param CategoryRepository $categoryRepository
     *
     * @return Response
     *
     * Sidebar hierarchy of categories
     */
    #[Route('/shop/category/hierarchy/list', name: 'module_web_shop_category_hierarchy_list')]
    public function list(CategoryRepository $categoryRepository, Request $request): Response
    {

        // array hydrated
        $array = $this->sort($categoryRepository->findAllCategories());

        return $this->render(
            'module/web_shop/external/category/web_shop_category_hierarchy.html.twig',
            [
                'categories' => $array]
        );
    }

    /**
     * @param array $categories
     * @return array
     *
     * Creates an associative array out of passed categories
     * Note: Categories have to be an assoc array
     */
    private function sort(array $categories): array
    {
        $array = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            $pathArray = explode('/', $category['path']);
            $this->fromKeysMerge($pathArray, $category, $array);
        }
        return $array;
    }

    /**
     * @param array $pathArray
     * @param $value
     * @param $array
     * @return void
     *
     * Creates a reference, adds keys and the finally adds values
     *
     * Note: Mind the reference to the array.
     *
     */
    private function fromKeysMerge(array $pathArray, $value, &$array): void
    {
        // first element is always null because
        // path starts off with a '/'
        unset($pathArray[0]);

        $reference =& $array;
        foreach ($pathArray as $key) {
            $reference =& $reference[$key];
        }
        $reference = $value;
    }
}