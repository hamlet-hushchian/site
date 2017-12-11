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

    public function copyAction()
    {
        $db = mysqli_connect("newredl.mysql.ukraine.com.ua","newredl_db","2nhpfzhf","newredl_db");
        $query = mysqli_query($db,"SELECT * FROM listings WHERE image != '' ") or die(mysqli_error($db));



        $d_types = ['Продажа' => 1, "Аренда" => 2];
        $cur = ['USD' => 1, "UAH" => 2, "EUR" => 3];

        while ($res = mysqli_fetch_assoc($query))
        {
            $this->sessionContainer->userChoises = [];
            $step1 = [];
            $step2 = [];
            $step3 = [];

            $res['street'] = iconv('windows-1251','utf-8',$res['street']);
            $res['metro'] = iconv('windows-1251','utf-8',$res['metro']);
            $res['d_type'] = iconv('windows-1251','utf-8',$res['d_type']);
            $res['d_type'] = $d_types[$res['d_type']];
            $res['p_type'] = iconv('windows-1251','utf-8',$res['p_type']);
            $res['city'] = iconv('windows-1251','utf-8',$res['city']);
            $res['rajon'] = iconv('windows-1251','utf-8',$res['rajon']);
            $res['micro_rajon'] = iconv('windows-1251','utf-8',$res['micro_rajon']);
            $res['lend_unit'] = iconv('windows-1251','utf-8',$res['lend_unit']);
            $res['listing_ditail'] = iconv('windows-1251','utf-8',$res['listing_ditail']);

            if($res['p_type'] !== 'Квартира' || $res['city'] !== 'Киев')
                continue;

            $mdr = $this->entityManager->getRepository(\Admin\Entity\Microdistrict::class)->findByName($res['micro_rajon']);
            $sbr = $this->entityManager->getRepository(\Admin\Entity\SubwayStation::class)->findByName($res['metro']);
            $ld = json_decode($res['listing_ditail'],true);

            $md = is_object($mdr[0]) ? $mdr[0]->getId() : 99;
            $sb = is_object($sbr[0]) ? $sbr[0]->getId() : 99;
            $step1['phone'] = $step1['phone_2'] = $step1['phone_3'] = [];
            $res['phone'] = preg_replace('~\+38| |\)|\(|-~','',$res['phone']);
            $res['phone_2'] = preg_replace('~\+38| |\)|\(|-~','',$res['phone_2']);
            $res['phone_3'] = preg_replace('~\+38| |\)|\(|-~','',$res['phone_3']);
            $images = explode(',',$res['image']);

            $step1['deal_type'] = $res['d_type'];
            $step1['property_type'] = 1;
            $step1['microdistrict'] = $md;
            $step1['subway_station'] = $sb;
            $step1['street'] = $res['street'];
            $step1['house_number'] = $ld['number_h'] !== null ? $ld['number_h'] : 99999;
            $step1['phone'][] = $res['phone'];
            $step1['phone'][] = $res['phone_2'];
            $step1['phone'][] = $res['phone_3'];

            $step2['q_rooms'] = $res['q_rooms'];
            $step2['plan'] = $res['plan_build'];
            $step2['common_square'] = $res['comm_square'];
            $step2['real_square'] = $res['real_square'];
            $step2['kitchen_square'] = $ld['kuh_square'];
            $step2['balkon_square'] = $ld['balk_square'];
            $step2['level'] = $ld['level'];
            $step2['levels'] = $ld['levels'];
            $step2['san_node'] = $ld['san_nood'];
            $step2['build_type'] = $res['type_build'];
            $step2['type_wall'] = $ld['type_wall'];
            $step2['type_window'] = $ld['type_window'];
            $step2['type_warm'] = $ld['type_warm'];
            $step2['type_warm'] = $ld['type_warm'];
            $step2['price'] = $res['price'];
            $step2['currency'] = $cur[$res['currency']];
            $step2['description'] = $ld['number_h'] !== null ? $ld['number_h'] : '';

            foreach ($images as $k=>$image)
            {
                $name = 'https://redl.com.ua/admin/'.$image;
                $inf = getimagesize($name);
                $h = $inf[0] * 0.625;
                $crop = '{"x":0,"y":0,"width":'.$inf[0].',"height":'.$h.',"rotate":0,"scaleX":1,"scaleY":1}';
                $step3['listing_images'][] = ['name' => $name,'tmp_name' => '/home/newredl/redl.com.ua/www/admin/'.$image,'is_copy'=>true];
                $step3['crop'][] = $crop;
                $step3['order'][] = $k;
            }

            $this->sessionContainer->userChoises['step1'] = $step1;
            $this->sessionContainer->userChoises['step2'] = $step2;
            $this->sessionContainer->userChoises['step3'] = $step3;

            $this->listingManager->save($this->sessionContainer);

        }

        die();
    }

    public function indexAction()
    {
        if ($this->getRequest()->isPost())
        {
            if(!is_null($this->params()->fromPost('delete')))
            {
                $this->listingManager->deleteListing($this->params()->fromPost('id'));
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
