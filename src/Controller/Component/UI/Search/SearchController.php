<?php

namespace Silecust\WebShop\Controller\Component\UI\Search;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Form\Common\UI\Search\SearchForm;
use Silecust\WebShop\Service\Component\UI\Search\SearchTermReplacer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Called when search from "form" is invoked
 */
class SearchController extends EnhancedAbstractController
{

    public function __construct(EventDispatcherInterface $eventDispatcher, private SearchTermReplacer $searchTermReplacer)
    {
        parent::__construct($eventDispatcher);
    }

    #[Route('/admin/search', name: 'sc_admin_grid_search')]
    public function search(Request $request): Response
    {

        $form = $this->createForm(SearchForm::class);
        $form->get('searchTerm')->setData($request->query->get('searchTerm'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $request->query->get('_redirected_to');
            return $this->redirect($this->searchTermReplacer
                ->checkAndReplaceSearchTerm($url, $form->getData()['searchTerm']));
        }
        return $this->render('@SilecustWebShop/common/ui/search/search_form.html.twig',
            ['form' => $form, 'request' => $request]);

    }


}