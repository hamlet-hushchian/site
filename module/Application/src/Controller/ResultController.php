<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Application\Service\SearchManager;
use Admin\Entity\Listing;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class ResultController extends AbstractActionController
{
    private $entityManager;
    private $listingManager;

    public function __construct($entityManager, $listingManager)
    {
        $this->entityManager = $entityManager;
        $this->listingManager = $listingManager;
    }

    /**
     * Redefine method of parent class onDispatch(),
     * for set alternative layout for all actions in this controller.
     */
    public function onDispatch(MvcEvent $e)
    {
        // Call method of base class onDispatch() and get response
        $response = parent::onDispatch($e);

        // Set alternative layout
        $this->layout()->setTemplate('layout/searchResult');

        // Return response
        return $response;
    }

    public function indexAction()
    {
        $searchManager = new SearchManager();
        $dType = $this->params()->fromRoute('d_type');
        $pType = $this->params()->fromRoute('p_type');
        $city = $this->params()->fromRoute('city');
        $data = $searchManager->genParamsFromQueryString($this->getRequest()->getUri()->getQuery());
        $page = $this->params()->fromQuery('page', 1);
        $listings = $this->entityManager->getRepository(Listing::class)->getSearchResult($dType, $pType, $city, $data);

        // Создаем ZF3 пагинатор.
        $adapter = new DoctrineAdapter(new ORMPaginator($listings, false));
        $paginator = new Paginator($adapter);

// Устанавливаем номер страницы и размер страницы.
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);


// Проходим по результатам с текущей страницы.
        foreach ($paginator as $listing) {
            // Делаем необходимые манипуляции с каждым объявлением.
            $listing->setPrice($searchManager->convertPrice($listing->getPrice(), strtolower($listing->getCurrency()->getShort())));
            $listing->setStreet($this->listingManager->formatStreet($listing->getStreet()));

            foreach ($listing->getParamsValue() as $paramValue) {
                $listing->params[$paramValue->getParam()->getParamKey()] = $paramValue->getValue();
            }

            $levelsString = $this->listingManager->formatLevels($listing->params['level'], $listing->params['levels']);
            $roomsString = $this->listingManager->formatRooms($listing->params['q_rooms']);

            $listing->params['levelsString'] = $levelsString;
            $listing->params['roomsString'] = $roomsString;
        }


        $viewModel = new ViewModel([
            'data' => [$dType, $pType, $city, $data],
            'listings' => $paginator,
            'url' => [
                'd_type' => $dType,
                'p_type' => $pType,
                'city' => $city,
                'query' => $data,
            ],
        ]);
        $this->layout()->setVariable('activeMenuItem', $this->params()->fromRoute('d_type'));


        return $viewModel;
    }
}
