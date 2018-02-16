<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Admin\Service\ListingManager;
use Application\Service\SearchManager;
use Application\Form\SelectionForm;
use Application\Entity\Selection;

class ListingController extends AbstractActionController
{
    private $listingManager;
    private  $entityManager;
    public function __construct($lm,$em)
    {
        $this->listingManager = $lm;
        $this->entityManager = $em;
    }

    public function onDispatch(MvcEvent $e)
    {
        // Вызываем метод базового класса onDispatch() и получаем ответ
        $response = parent::onDispatch($e);

        // Устанавливаем альтернативный лэйаут
        $this->layout()->setTemplate('layout/listing');

        // Возвращаем ответ
        return $response;
    }

    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if(!$this->entityManager->getRepository(\Admin\Entity\Listing::class)->findOneById($id))
        {
            $this->getResponse()->setStatusCode(404);
            return new ViewModel();
        }


        $form = new SelectionForm();
        $selection = new Selection();
        $form->bind($selection);
        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isPost()) {
                $form->setData($this->params()->fromPost());
                if($form->isValid())
                {
                    $selection->setListingId($this->params()->fromRoute('id'));
                    $this->entityManager->persist($selection);
                    $this->entityManager->flush();

                    $message = "<p><b>Имя: </b>".$selection->getName().";</p>".
                    "<p><b>Телефон: </b>".$selection->getPhone().";</p>".
                    "<p><b>ID Объявления: </b>".$selection->getListingId().";</p>".
                    "<p><b>Сообщение: </b>".$selection->getMessage().";</p>";
                    $headers = "From: Redl \r\n";
                    $headers .= "Reply-To: no-reply@redl.com.ua\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                    mail('guschan12@gmail.com','Запрос на подбор',$message,$headers);

                    echo json_encode(['success'=>true]);
                }
                else
                {
                    echo json_encode($form->getMessages());
                }
                exit(200);
            }
        }
        $listing = $this->listingManager->getListingById($id);
        $sm = new SearchManager();
        $allPrices = $sm->convertPrice($listing->getPrice(),strtolower($listing->getCurrency()->getShort()),true);
        $listing->setPrice($allPrices);
        return new ViewModel([
            'id' => $id,
            'listing' => $listing,
            'form' => $form,
        ]);
    }
}