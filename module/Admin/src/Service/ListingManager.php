<?php

namespace Admin\Service;

use Admin\Entity\Listing;
use Admin\Entity\PropertyParamsValue;
use Admin\Entity\PropertyParams;
use Admin\Entity\Phone;
use Admin\Entity\ListingImage;
use Admin\Entity\Currency;
use Admin\Entity\PropertyType;
use Admin\Entity\DealType;
use Admin\Entity\SubwayStation;
use Admin\Entity\Microdistrict;
use PHPImageWorkshop\ImageWorkshop;

require_once(__DIR__ . '/../Lib/image-workshop/src/PHPImageWorkshop/ImageWorkshop.php');

class ListingManager
{
    private $entityManager;

    private $listingParams = [];

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        foreach ($this->entityManager->getRepository(PropertyParams::class)->findAll() as $param) {
            $this->listingParams[$param->getParamKey()] = $param;
        }
    }

    public function getListingById($id)
    {
        return $this->entityManager->getRepository(Listing::class)->getListing($id);
    }

    public function getSearchResult($d_type,$p_type,$city,$params)
    {
        return $this->entityManager->getRepository(Listing::class)->getSearchResult($d_type,$p_type,$city,$params);
    }


    public function save($sessionContainer)
    {
        //1. Save Listing
        $listing = new Listing();

        $curDate = date('Y-m-d H:i:s');
        $currency = $this->entityManager->getRepository(Currency::class)->findOneById($sessionContainer->userChoises['step2']['currency']);
        $propertyType = $this->entityManager->getRepository(PropertyType::class)->findOneById($sessionContainer->userChoises['step1']['property_type']);
        $dealType = $this->entityManager->getRepository(DealType::class)->findOneById($sessionContainer->userChoises['step1']['deal_type']);
        $subwayStation = $this->entityManager->getRepository(SubwayStation::class)->findOneById($sessionContainer->userChoises['step1']['subway_station']);
        $microdistrict = $this->entityManager->getRepository(Microdistrict::class)->findOneById($sessionContainer->userChoises['step1']['microdistrict']);

        $listing->setDateAdd($curDate);
        $listing->setDateEdit($curDate);
        $listing->setDateCall($curDate);
        $listing->setUserId(1);
        $listing->setDealType($dealType);
        $listing->setPropertyType($propertyType);
        $listing->setMicrodistrict($microdistrict);
        $listing->setSubwayStation($subwayStation);
        $listing->setStreet($sessionContainer->userChoises['step1']['street']);
        $listing->setHouseNumber($sessionContainer->userChoises['step1']['house_number']);
        $listing->setPrice($sessionContainer->userChoises['step2']['price']);
        $listing->setCurrency($currency);
        $listing->setDescription($sessionContainer->userChoises['step2']['description']);

        //2.Save ParamsValue
        $this->setParamsValue($sessionContainer->userChoises, $listing);

        //3.Save Phones
        foreach ($sessionContainer->userChoises['step1']['phone'] as $phone_number) {
            $phone = new Phone();
            $phone->setListing($listing);
            $phone->setNumber($phone_number);
            $this->entityManager->persist($phone);
            $listing->addPhone($phone);
        }

        //4.Save Photos
        if (count($sessionContainer->userChoises['step3']['listing_images']) > 0) {
            $uniq_id = uniqid();
            foreach ($sessionContainer->userChoises['step3']['listing_images'] as $k => $image) {
                $name = sha1(uniqid());
                $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $new_name = $name . '.' . $ext;
                $s_link = '/assets/uploads/listing-images/' . $uniq_id . '/source/' . $new_name;
                $th_link = '/assets/uploads/listing-images/' . $uniq_id . '/thumb/' . $new_name;
                $listingImage = new ListingImage();
                $listingImage->setUniqId($uniq_id);
                $listingImage->setListing($listing);
                $listingImage->setSourceLink($s_link);
                $listingImage->setThumbLink($th_link);
                $listingImage->setName($name);
                $listingImage->setExt($ext);
                $listingImage->setCrop($sessionContainer->userChoises['step3']['crop'][$k]);
                $listingImage->setOrder($sessionContainer->userChoises['step3']['order'][$k]);
                $this->entityManager->persist($listingImage);
                $listing->addImage($listingImage);
            }
        }

        $this->entityManager->persist($listing);
        $this->entityManager->flush();
        $listingId = $listing->getId();

        //5.Process and save images to the server
        if (count($sessionContainer->userChoises['step3']['listing_images']) > 0) {
            $images = $this->entityManager->getRepository(ListingImage::class)->findByListingId($listingId);
            $source_dir = __DIR__ . '/../../../../public/assets/uploads/listing-images/' . $images[0]->getUniqId() . '/source';
            if (!file_exists($source_dir)) {
                mkdir($source_dir, 0777, true);
            }
            for ($i = 0; $i < count($images); $i++) {
                $s_path = __DIR__ . '/../../../../public' . $images[$i]->getSourceLink();
                if($sessionContainer->userChoises['step3']['listing_images'][$i]['is_copy'])
                {
                    if (file_exists($sessionContainer->userChoises['step3']['listing_images'][$i]['tmp_name'])){
                        copy($sessionContainer->userChoises['step3']['listing_images'][$i]['tmp_name'], $s_path);
                    }
                }
                else
                {
                    move_uploaded_file($sessionContainer->userChoises['step3']['listing_images'][$i]['tmp_name'], $s_path);
                }


                $crop_data = json_decode($images[$i]->getCrop(), true);
                if (!file_exists($s_path))
                    return;
                $layer = ImageWorkshop::initFromPath($s_path);
                $layer->cropInPixel(round($crop_data['width']), round($crop_data['height']), round($crop_data['x']), round($crop_data['y']), 'LT');
//
                $th_path = __DIR__ . '/../../../../public/assets/uploads/listing-images/' . $images[$i]->getUniqId() . '/thumb';
                $filename = $images[$i]->getName() . '.' . $images[$i]->getExt();
                $createFolders = true;
                $backgroundColor = null; // transparent, only for PNG (otherwise it will be white if set null)
                $imageQuality = 80; // useless for GIF, usefull for PNG and JPEG (0 to 100%)
                $layer->save($th_path, $filename, $createFolders, $backgroundColor, $imageQuality);

            }
        }


        unset($sessionContainer->userChoises);
        die(json_encode(['success' => true]));
    }

    public function update($sessionContainer)
    {
        $listing = $this->entityManager->getRepository(Listing::class)->findOneById($sessionContainer->editListingId);
        $paramsValue = [];
        foreach ($listing->getParamsValue() as $paramValue) {
            $paramsValue[$paramValue->getParam()->getParamKey()] = $paramValue;
        }

        $curDate = date('Y-m-d H:i:s');
        $currency = $this->entityManager->getRepository(Currency::class)->findOneById($sessionContainer->editData['step2']['currency']);
        $propertyType = $this->entityManager->getRepository(PropertyType::class)->findOneById($sessionContainer->editData['step1']['property_type']);
        $dealType = $this->entityManager->getRepository(DealType::class)->findOneById($sessionContainer->editData['step1']['deal_type']);
        $subwayStation = $this->entityManager->getRepository(SubwayStation::class)->findOneById($sessionContainer->editData['step1']['subway_station']);
        $microdistrict = $this->entityManager->getRepository(Microdistrict::class)->findOneById($sessionContainer->editData['step1']['microdistrict']);

        if ($sessionContainer->editData['step1']['property_type'] == $listing->getPropertyType()->getId()) {
            if (in_array($sessionContainer->editData['step1']['property_type'], [1, 2])) {
                $paramsValue['house_number']->setValue($sessionContainer->editData['step1']['house_number']);
                $paramsValue['flat_number']->setValue($sessionContainer->editData['step1']['flat_number']);
            }

//            var_dump($sessionContainer->editData['step2']);

            foreach ($sessionContainer->editData['step2'] as $key => $value) {
                if (!in_array($key, ['price', 'currency', 'description'])) {
                    if ($key == 'plan')
                    {
                        $key = 'plan_build';
                    }

                    $paramsValue[$key]->setValue($value);
                }

            }
        } else {
            foreach ($listing->getParamsValue() as $paramValue) {
                $this->entityManager->remove($paramValue);
            }
            $this->setParamsValue($sessionContainer->editData, $listing);
        }

        if (count($sessionContainer->editData['step1']['phone']) == count($listing->getPhones())) {
            for ($i = 0; $i < count($listing->getPhones()); $i++) {
                $listing->getPhones()[$i]->setNumber($sessionContainer->editData['step1']['phone'][$i]);
            }
        } else {
            foreach ($listing->getPhones() as $phone) {
                $this->entityManager->remove($phone);
            }
            foreach ($sessionContainer->editData['step1']['phone'] as $phone_number) {
                $phone = new Phone();
                $phone->setListing($listing);
                $phone->setNumber($phone_number);
                $this->entityManager->persist($phone);
                $listing->addPhone($phone);
            }
        }


        $needRethumb = [];
        $needDelete = [];
        $needSave = [];


        if (count($sessionContainer->editData['step3']['old_listing_images']) == count($listing->getImages())) {//if not delete and not add images
            for ($i = 0; $i < count($listing->getImages()); $i++) {
                foreach ($sessionContainer->editData['step3']['old_listing_images'] as $k=>$v)
                {
                    if($sessionContainer->editData['step3']['old_listing_images'][$k] == $listing->getImages()[$i]->getSourceLink())
                    {
                        $listing->getImages()[$i]->setOrder($sessionContainer->editData['step3']['order'][$k]);

                        if ($listing->getImages()[$i]->getCrop() !== $sessionContainer->editData['step3']['old_crop'][$k]) {
                            $listing->getImages()[$i]->setCrop($sessionContainer->editData['step3']['old_crop'][$k]);
                            $needRethumb[] = $i;
                        }
                    }
                }

            }
        } else {
            for ($i = 0; $i < count($listing->getImages()); $i++) {
                if (!in_array($listing->getImages()[$i]->getSourceLink(), $sessionContainer->editData['step3']['old_listing_images'])) {
                    $needDelete[] = [$listing->getImages()[$i]->getUniqId(), $listing->getImages()[$i]->getName() . '.' . $listing->getImages()[$i]->getExt()];
                    $this->entityManager->remove($listing->getImages()[$i]);
                }
            }
        }

        if (count($sessionContainer->editData['step3']['listing_images']) > 0) {
            $uniq_id = $listing->getImages()[0] ? $listing->getImages()[0]->getUniqId() : uniqid();
            foreach ($sessionContainer->editData['step3']['listing_images'] as $k => $image) {
                $name = sha1(uniqid());
                $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $new_name = $name . '.' . $ext;
                $s_link = '/assets/uploads/listing-images/' . $uniq_id . '/source/' . $new_name;
                $th_link = '/assets/uploads/listing-images/' . $uniq_id . '/thumb/' . $new_name;
                $listingImage = new ListingImage();
                $listingImage->setUniqId($uniq_id);
                $listingImage->setListing($listing);
                $listingImage->setSourceLink($s_link);
                $listingImage->setThumbLink($th_link);
                $listingImage->setName($name);
                $listingImage->setExt($ext);
                $listingImage->setCrop($sessionContainer->editData['step3']['crop'][$k]);
                $listingImage->setOrder($sessionContainer->editData['step3']['order'][$k]);
                $this->entityManager->persist($listingImage);
                $listing->addImage($listingImage);
                $needSave[] = [$listingImage, $image];
            }
        }

        $listing->setDateEdit($curDate);
        $listing->setDealType($dealType);
        $listing->setPropertyType($propertyType);
        $listing->setMicrodistrict($microdistrict);
        $listing->setSubwayStation($subwayStation);
        $listing->setStreet($sessionContainer->editData['step1']['street']);
        $listing->setHouseNumber($sessionContainer->editData['step1']['house_number']);
        $listing->setPrice($sessionContainer->editData['step2']['price']);
        $listing->setCurrency($currency);
        $listing->setDescription($sessionContainer->editData['step2']['description']);

        $this->entityManager->flush();

        if (count($needRethumb) > 0) {
            foreach ($needRethumb as $imageKey) {
                $this->createThumb($listing->getImages()[$imageKey]);
            }
        }

        if (count($needDelete) > 0) {
            foreach ($needDelete as $imageData) {
                $dir = __DIR__ . '/../../../../public' . '/assets/uploads/listing-images/' . $imageData[0];// . '/source/';
                unlink($dir . '/source/' . $imageData[1]);
                if (count(glob("$dir/source/*")) === 0) {
                    rmdir("$dir/source");
                }
                unlink($dir . '/thumb/' . $imageData[1]);
                if (count(glob("$dir/thumb/*")) === 0) {
                    rmdir("$dir/thumb");
                }
                if (count(glob("$dir/*")) === 0) {
                    rmdir($dir);
                }
            }
        }

        if (count($needSave) > 0) {
            foreach ($needSave as $imageData) {
//                $image = $this->entityManager->getRepository(ListingImage::class)->findBy(['name' => $imageData[0]]);
                $this->saveOneImage($imageData[0], $imageData[1]);
            }
        }

        unset($sessionContainer->editData);
        unset($sessionContainer->editListingId);
        unset($sessionContainer->editStep);
        die(json_encode(['success' => true]));
    }

    public function deleteListing($id)
    {
        $listing = $this->entityManager->getRepository(Listing::class)->findOneById($id);
        $paramsValue = $this->entityManager->getRepository(PropertyParamsValue::class)->findByListingId($id);
        if(count($listing->getImages()) > 0)
        {
            foreach ($listing->getImages() as $image)
            {
                $this->deleteImage($image->getUniqId(),$image->getName().".".$image->getExt());
                $this->entityManager->remove($image);
            }
        }
        foreach ($listing->getPhones() as $phone)
        {
            $this->entityManager->remove($phone);
        }
        foreach ($paramsValue as $paramValue)
        {
            $this->entityManager->remove($paramValue);
        }
        $this->entityManager->remove($listing);
        $this->entityManager->flush();
        die('success');
    }

    public function getSessionDataForListing($listingId)
    {
        $listing = $this->entityManager->getRepository(Listing::class)->findOneById($listingId);
        $data = [];

        //Set data for step 1
        $data['step1']['deal_type'] = $listing->getDealType()->getId();
        $data['step1']['property_type'] = $listing->getPropertyType()->getId();
        $data['step1']['microdistrict'] = $listing->getMicrodistrict()->getId();
        $data['step1']['district'] = $listing->getMicrodistrict()->getDistrict()->getId();
        $data['step1']['city'] = $listing->getMicrodistrict()->getDistrict()->getCity()->getId();
        $data['step1']['subway_station'] = $listing->getSubwayStation()->getId();
        $data['step1']['street'] = $listing->getStreet();
        $data['step1']['house_number'] = $listing->getHouseNumber();
        if (in_array($listing->getPropertyType()->getId(), [1, 2])) {
            foreach ($listing->getParamsValue() as $paramValue) {
                if ($paramValue->getParam()->getParamKey() == 'flat_number') {
                    $data['step1']['flat_number'] = $paramValue->getValue();
                }
            }
        }
        foreach ($listing->getPhones() as $phone) {
            $data['step1']['phone'][] = $phone->getNumber();
        }

        //Set data for step 2
        if ($data['step1']['property_type'] == 1)//Квартира
        {
            foreach ($listing->getParamsValue() as $paramValue) {
                $params[$paramValue->getParam()->getParamKey()] = $paramValue->getValue();
            }
            $data['step2']['q_rooms'] = $params['q_rooms'];
            $data['step2']['plan'] = $params['plan_build'];
            $data['step2']['common_square'] = $params['common_square'];
            $data['step2']['real_square'] = $params['real_square'];
            $data['step2']['kitchen_square'] = $params['kitchen_square'];
            $data['step2']['balkon_square'] = $params['balkon_square'];
            $data['step2']['level'] = $params['level'];
            $data['step2']['levels'] = $params['levels'];
            $data['step2']['san_node'] = $params['san_node'];
            $data['step2']['build_type'] = $params['build_type'];
            $data['step2']['type_wall'] = $params['type_wall'];
            $data['step2']['type_window'] = $params['type_window'];
            $data['step2']['type_warm'] = $params['type_warm'];
            $data['step2']['price'] = $listing->getPrice();
            $data['step2']['currency'] = $listing->getCurrency()->getId();
            $data['step2']['description'] = $listing->getDescription();
        }
        if ($data['step1']['property_type'] == 2)//Комната
        {
            $data['step2']['common_square'] = $params['common_square'];
            $data['step2']['kitchen_square'] = $params['kitchen_square'];
            $data['step2']['level'] = $params['level'];
            $data['step2']['levels'] = $params['levels'];
            $data['step2']['san_node'] = $params['san_node'];
            $data['step2']['build_type'] = $params['build_type'];
            $data['step2']['type_wall'] = $params['type_wall'];
            $data['step2']['type_window'] = $params['type_window'];
            $data['step2']['type_warm'] = $params['type_warm'];
            $data['step2']['price'] = $listing->getPrice();
            $data['step2']['currency'] = $listing->getCurrency()->getId();
            $data['step2']['description'] = $listing->getDescription();
        }


        //Set data for step 3
        foreach ($listing->getImages() as $image) {
            $data['step3']['listing_images'][] = $image->getSourceLink();
            $data['step3']['crop'][] = $image->getCrop();
        }


        return $data;
    }

    public function getMicrodistrictsByDistrictId($district_id)
    {
        $microdistricts = [];
        $repo = $this->entityManager->getRepository(Microdistrict::class)->findByDistrictId($district_id);
        foreach ($repo as $microdistrict)
        {
            $microdistricts[$microdistrict->getId()] = $microdistrict->getName();
        }
        return $microdistricts;
    }

    public function formatStreet($street)
    {
        $street = explode(',', $street);
        $street_sh = $street[0];

        if (preg_match('~улица~', $street[0]))
            $street_sh = preg_replace('~улица~', 'ул.', $street[0]);

        if (preg_match('~проспект~', $street[0]))
            $street_sh = preg_replace('~проспект~', 'просп.', $street[0]);

        return $street_sh;
    }

    public function formatLevels($level,$levels)
    {
        if($level !== '' && $levels !== "")
        {
            $result = $level.' этаж из '.$levels;
        }
        else if($level !== '' && $levels == '')
        {
            $result = $level . ' этаж';
        }
        else if($level == '' && $levels !== '')
        {
            $result = $levels.' этажей';
        }
        else if($level == '' && $levels == '')
        {
            $result = 'Неизвстно';
        }
        return $result;
    }

    public function formatRooms($q_rooms)
    {
        $str = '';
        if($q_rooms == 1)
            $str = ' комната';
        if($q_rooms > 1 && $q_rooms < 5)
            $str = ' комнаты';
        if($q_rooms >= 5)
            $str = ' комнат';
        return $q_rooms.$str;
    }

    private function setParamsValue($listingData, $listing)
    {
        if (in_array($listingData['step1']['property_type'], [1, 2])) {
            $paramValue = new PropertyParamsValue();
            $paramValue->setListing($listing);
            $paramValue->setParam($this->listingParams['house_number']);
            $paramValue->setValue($listingData['step1']['house_number']);
            $this->entityManager->persist($paramValue);
            $listing->addParamValue($paramValue);

            $paramValue = new PropertyParamsValue();
            $paramValue->setListing($listing);
            $paramValue->setParam($this->listingParams['flat_number']);
            $paramValue->setValue($listingData['step1']['flat_number']);
            $this->entityManager->persist($paramValue);
            $listing->addParamValue($paramValue);
        }

        foreach ($listingData['step2'] as $key => $value) {
            if (!in_array($key, ['price', 'currency', 'description'])) {
                if ($key == 'plan')
                    $key = 'plan_build';
                $paramValue = new PropertyParamsValue();
                $paramValue->setListing($listing);
                $paramValue->setParam($this->listingParams[$key]);
                $paramValue->setValue($value);
                $this->entityManager->persist($paramValue);
                $listing->addParamValue($paramValue);
            }

        }
    }

    private function saveOneImage($image, $tmp_data)
    {
        $source_dir = __DIR__ . '/../../../../public/assets/uploads/listing-images/' . $image->getUniqId() . '/source';
        if (!file_exists($source_dir)) {
            mkdir($source_dir, 0777, true);
        }
        $s_path = __DIR__ . '/../../../../public' . $image->getSourceLink();
        move_uploaded_file($tmp_data['tmp_name'], $s_path);
        $this->createThumb($image);
    }

    /*
     * Create and save (with replace) Thumbnail from image
     * @param \Admin\Entity\ListingImage
     */
    private function createThumb($image)
    {
        $s_path = __DIR__ . '/../../../../public' . $image->getSourceLink();
        $crop_data = json_decode($image->getCrop(), true);

        $layer = ImageWorkshop::initFromPath($s_path);
        $layer->cropInPixel(round($crop_data['width']), round($crop_data['height']), round($crop_data['x']), round($crop_data['y']), 'LT');

        $th_path = __DIR__ . '/../../../../public/assets/uploads/listing-images/' . $image->getUniqId() . '/thumb';
        $filename = $image->getName() . '.' . $image->getExt();
        $createFolders = true;
        $backgroundColor = null; // transparent, only for PNG (otherwise it will be white if set null)
        $imageQuality = 80; // useless for GIF, usefull for PNG and JPEG (0 to 100%)
        $layer->save($th_path, $filename, $createFolders, $backgroundColor, $imageQuality);
    }

    private function deleteImage($uniqid,$nameExt)
    {
        $dir = __DIR__ . '/../../../../public' . '/assets/uploads/listing-images/' . $uniqid;// . '/source/';
        unlink($dir . '/source/' . $nameExt);
        if (count(glob("$dir/source/*")) === 0) {
            rmdir("$dir/source");
        }
        unlink($dir . '/thumb/' . $nameExt);
        if (count(glob("$dir/thumb/*")) === 0) {
            rmdir("$dir/thumb");
        }
        if (count(glob("$dir/*")) === 0) {
            rmdir($dir);
        }
    }

}