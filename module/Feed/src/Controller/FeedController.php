<?php
namespace Feed\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Entity\Listing;

class FeedController extends AbstractActionController
{
    private $entityManager;
    private $xmlManager;

    public function __construct($em,$xm)
    {
        $this->entityManager = $em;
        $this->xmlManager = $xm;
    }

    public function indexAction()
    {
        $listings = $this->entityManager->getRepository(Listing::class)->getListingsForFeed();
        $this->xmlManager->generateFeedForLun($listings);
        $this->layout()->setTemplate('layout/red-head-footer');
//        return [];
        return $this->getResponse();
    }
}
