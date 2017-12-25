<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\AddListingForm;
use Admin\Entity\Listing;
use Admin\Entity\Listingx;

Class ListingController extends AbstractActionController
{
    /**
     * @var Zend\Session\Container
     */
    private $sessionContainer;

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var Admin\Service\ListingManager
     */
    private $listingManager;

    /**
     * AddListingController constructor.
     * @param $sessionContainer
     * @param $entityManager
     */
    public function __construct($sessionContainer, $entityManager, $listingManager)
    {
        $this->sessionContainer = $sessionContainer;
        $this->entityManager = $entityManager;
        $this->listingManager = $listingManager;
    }

    public function indexAction()
    {
        if ($this->getRequest()->isPost())
        {
            if(!is_null($this->params()->fromPost('delete')))
            {
                $this->listingManager->deleteListing($this->params()->fromPost('id'));
            }
            if(!is_null($this->params()->fromPost('update')))
            {
                $this->listingManager->updateListing($this->params()->fromPost('id'));
            }
        }
        $listings = $this->entityManager->getRepository(Listing::class)->getAllListings();
        $this->layout()->setVariable('activeMenuItem', 'menu_all');
        return new ViewModel([
            'listings' => $listings,
        ]);
    }

    public function addAction()
    {
//        var_dump($this->sessionContainer->userChoises);
        $step = 1;

        if (isset($this->sessionContainer->step))
            $step = $this->sessionContainer->step;

        if (null !== $this->params()->fromQuery('step') && (int)$this->params()->fromQuery('step') <= $step && (int)$this->params()->fromQuery('step') > 0) {
            $this->sessionContainer->step = (int)$this->params()->fromQuery('step');
            return $this->redirect()->toRoute("listings", ['action' => 'add']);
        }

        if ($step < 1 || $step > 3)
            $step = 1;

        if ($step == 1) {
            if (!isset($this->sessionContainer->userChoises))
                $this->sessionContainer->userChoises = [];
        }

        $form = new AddListingForm($step, $this->entityManager,$this->sessionContainer->userChoises);

        if (isset($this->sessionContainer->userChoises["step$step"])) {
            $data = $this->sessionContainer->userChoises["step$step"];
            if ($step == 1) {
                $form->get('phone')->setCount(count($data['phone']));
                $form->prepare();
            }
            $form->setData($data);
            $form->prepare();
        }


        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();



            if ($step == 1) {
                $form->get('phone')->setCount(count($data['phone']));
                $form->prepare();
            }

            if ($step == 3) {
                $request = $this->getRequest();
                $data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
            }
//            var_dump($data);
//            die();

            $form->setData($data);


            if ($form->isValid()) {

                $data = $form->getData();

                $this->sessionContainer->userChoises["step$step"] = $data;

                // Увеличиваем шаг.
                $step++;

                $this->sessionContainer->step = $step;

                if ($step > 3) {
                    $this->listingManager->save($this->sessionContainer);
                }

                return $this->redirect()->toRoute("listings", ['action' => 'add']);
            } else {
                if ($step == 3) {
                    if (isset($form->getMessages()['listing_images']['fileMimeTypeFalse']))
                        die(json_encode(['mimeError' => $form->getMessages()['listing_images']['fileMimeTypeFalse']]));
                    if (isset($form->getMessages()['listing_images']['fileImageSizeWidthTooSmall']))
                        die(json_encode(['sizeError' => $form->getMessages()['listing_images']['fileImageSizeWidthTooSmall']]));
                    if (isset($form->getMessages()['listing_images']['fileImageSizeHeightTooSmall']))
                        die(json_encode(['sizeError' => $form->getMessages()['listing_images']['fileImageSizeHeightTooSmall']]));

                }
            }
        }

        $viewModel = new ViewModel([
            'form' => $form,
        ]);

        $viewModel->setTemplate("admin/listing/step$step");

        $this->layout()->setVariable('activeMenuItem', 'menu_add');

        return $viewModel;
    }

    public function editAction()
    {
        $listingId = $this->params()->fromRoute('id');

        if(!isset($this->sessionContainer->editListingId) || $this->sessionContainer->editListingId !== $listingId)
        {
            $this->sessionContainer->editData = $this->listingManager->getSessionDataForListing($listingId);
            $this->sessionContainer->editListingId = $listingId;
            $this->sessionContainer->editStep = 1;
        }
        $step = $this->sessionContainer->editStep;

        if (null !== $this->params()->fromQuery('step') && (int)$this->params()->fromQuery('step') <= $step && (int)$this->params()->fromQuery('step') > 0) {
            $this->sessionContainer->editStep = (int)$this->params()->fromQuery('step');
            return $this->redirect()->toRoute("listings", ['action' => 'edit','id'=>$listingId]);
        }

        $form = new AddListingForm($step, $this->entityManager,$this->sessionContainer->editData);
        if($this->sessionContainer->editData["step$step"])
            $form->setData($this->sessionContainer->editData["step$step"]);

        if ($this->getRequest()->isPost())
        {
            $data = $this->params()->fromPost();
            if ($step == 3) {
                $request = $this->getRequest();
                $data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
            }
//            var_dump($data);
//            die('ss');
            $form->setData($data);
//            var_dump($form->getData);
//            die('ss');
            if($form->isValid())
            {
                $data = $form->getData();

                if($step == 3)
                {
                    $data['old_listing_images'] = $this->params()->fromPost()['old_listing_images'];
                    $data['old_crop'] = $this->params()->fromPost()['old_crop'];
//                    $data['old_order'] = $this->params()->fromPost()['old_crop'];
                }

                $this->sessionContainer->editData["step$step"] = $data;

                // Увеличиваем шаг.
                $step++;

                $this->sessionContainer->editStep = $step;

                if ($step > 3) {
                    $this->listingManager->update($this->sessionContainer);
                }

                return $this->redirect()->toRoute("listings", ['action' => 'edit','id'=>$listingId]);
            }
            else
            {
//                var_dump($form->getMessages());
//                die();
            }
        }

        $viewModel = new ViewModel([
            'form' => $form,
            'isEdit' => true
        ]);

        $viewModel->setTemplate("admin/listing/step$step");
        $this->layout()->setVariable('activeMenuItem', 'menu_all');

        return $viewModel;
    }

    public function ajaxAction()
    {
        if($this->getRequest()->isXmlHttpRequest())
        {
            if ($this->getRequest()->isPost())
            {
                if(!is_null($this->params()->fromPost('microdistrict')) && !is_null($this->params()->fromPost('microdistrict')))
                {
                    $microdistricts = $this->listingManager->getMicrodistrictsByDistrictId($this->params()->fromPost('district_id'));
                    echo json_encode($microdistricts);
                }
            }
        }
        else
        {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        die();
    }
}
