<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Application\Service\SearchManager;
use Admin\Service\ListingManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class ResultController extends AbstractActionController
{
    private $listingManager;

    public function __construct($listing_manager)
    {
        $this->listingManager = $listing_manager;
    }

    /**
     * Мы переопределяем метод родительского класса onDispatch(),
     * чтобы установить альтернативный лэйаут для всех действий в этом контроллере.
     */
    public function onDispatch(MvcEvent $e)
    {
        // Вызываем метод базового класса onDispatch() и получаем ответ
        $response = parent::onDispatch($e);

        // Устанавливаем альтернативный лэйаут
        $this->layout()->setTemplate('layout/searchResult');

        // Возвращаем ответ
        return $response;
    }

    public function indexAction()
    {
        $searchManager = new SearchManager();
        $data = $searchManager->genParamsFromQueryString($this->getRequest()->getUri()->getQuery());
        $dType = $this->params()->fromRoute('d_type');
        $pType = $this->params()->fromRoute('p_type');
        $city = $this->params()->fromRoute('city');
        $listings = $this->listingManager->getSearchResult($dType, $pType, $city, $data);
//        foreach ($listings as $listing)
//        {
//            $listing->setPrice($searchManager->convertPrice($listing->getPrice(),strtolower($listing->getCurrency()->getShort())));
//        }

        // Создаем ZF3 пагинатор.
        $page = $this->params()->fromQuery('page', 1);
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

            foreach ($listing->getParamsValue() as $paramValue)
            {
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
