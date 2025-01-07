<?php

namespace App\Controller\Component\UI\Search;

use App\Form\Common\UI\Search\SearchForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{

    #[Route('/admin/search', name: 'sc_admin_route_grid_search')]
    public function search(Request $request): Response
    {

        $form = $this->createForm(SearchForm::class);
        $form->get('searchTerm')->setData($request->query->get('searchTerm'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $request->query->get('_redirected_to');
            return $this->redirect( "$url&searchTerm={$form->getData()['searchTerm']}");
}
        return $this->render('common/ui/search/search_form.html.twig', ['form' => $form, 'request' => $request]);

    }
}