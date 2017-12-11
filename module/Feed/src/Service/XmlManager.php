<?php
namespace Feed\Service;

Class XmlManager
{
    private $config;
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function generateFeedForLun($listings)
    {
        $dom = new \DOMDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
        $root = $dom->createElement("realty-feed"); // Создаём корневой элемент
        $root->appendChild($dom->createAttribute('xmlns'))->appendChild($dom->createTextNode("http://webmaster.yandex.ru/schemas/feed/realty/2010-06"));
        $dom->appendChild($root);
        date_default_timezone_set('Europe/Kiev');
        $date = date("Y-m-d");
        $time = date("H:i:sP");
        $cur_date = $date."T".$time;
        $gen_date = $dom->createElement('generation-date', $cur_date);
        $root->appendChild($gen_date);

        foreach ($listings as $listing)
        {
            $dts = new \DateTime($listing->getDateAdd());
            $listing->setDateAdd($dts->format('Y-m-d') . 'T' . $dts->format('H:i:sP'));
            $dts = new \DateTime($listing->getDateEdit());
            $listing->setDateEdit($dts->format('Y-m-d') . 'T' . $dts->format('H:i:sP')) /* . $dts->format('H:i:sP')*/;/*T08:00:00*/

            $offer = $dom->createElement("offer");
            $offer->setAttribute("internal-id",$listing->getId());

            $type = $dom->createElement('type',$listing->getDealType()->getName());
            $offer->appendChild($type);

            $property_type = $dom->createElement('property-type',"жилая");
            $offer->appendChild($property_type);

            $category = $dom->createElement('category',ucfirst($listing->getPropertyType()->getName()));
            $offer->appendChild($category);

            $deal_status = $dom->createElement('deal-status',"прямая продажа");
            $offer->appendChild($deal_status);

            $url = $dom->createElement('url',"https://redl.com.ua/listing/".$listing->getId());
            $offer->appendChild($url);

            $creation_date = $dom->createElement('creation-date',$listing->getDateAdd());
            $offer->appendChild($creation_date);

            $last_update_date = $dom->createElement('last-update-date',$listing->getDateEdit());
            $offer->appendChild($last_update_date);

            $location = $dom->createElement("location");
            $country = $dom->createElement('country','Украина');
            $location->appendChild($country);
            $locality_name = $dom->createElement('locality-name',$listing->getMicrodistrict()->getDistrict()->getCity()->getName());
            $location->appendChild($locality_name);
            $adr = explode(",", $listing->getStreet());
            $adr = $adr[0].", ".$listing->getHouseNumber();
            $address = $dom->createElement('address',$adr);
            $location->appendChild($address);
            foreach ($listing->getParamsValue() as $pv)
            {
                if($pv->getParam()->getParamKey() == 'flat_number')
                {
                    $apartment = $dom->createElement('apartment',$pv->getValue());

                    $location->appendChild($apartment);
                }
            }
            $offer->appendChild($location);

            $sales_agent = $dom->createElement('sales-agent');
            $phone = $dom->createElement('phone','050 828-07-98');
            $sales_agent->appendChild($phone);
            $offer->appendChild($sales_agent);
            $price = $dom->createElement('price');
            $value = $dom->createElement('value',$listing->getPrice());
            $price->appendChild($value);
            $currency = $dom->createElement('currency',$listing->getCurrency()->getShort());
            $price->appendChild($currency);
            $offer->appendChild($price);

            foreach ($listing->getImages() as $image)
            {
                $image = $dom->createElement('image','https://redl.com.ua'.$image->getSourceLink());
                $offer->appendChild($image);
            }

            $description = $dom->createElement('description',$listing->getDescription());
            $offer->appendChild($description);

            foreach ($listing->getParamsValue() as $pv)
            {
                if($pv->getParam()->getParamKey() == 'common_square')
                {
                    $area = $dom->createElement('area');
                    $value = $dom->createElement('value',$pv->getValue());
                    $area->appendChild($value);
                    $unit = $dom->createElement('unit','кв. м');
                    $area->appendChild($unit);
                    $offer->appendChild($area);
                }
            }

            foreach ($listing->getParamsValue() as $pv)
            {
                if($pv->getParam()->getParamKey() == 'real_square')
                {
                    $living_space = $dom->createElement('living-space');
                    $value = $dom->createElement('value',$pv->getValue());
                    $living_space->appendChild($value);
                    $unit = $dom->createElement('unit','кв. м');
                    $living_space->appendChild($unit);
                    $offer->appendChild($living_space);
                }
            }

            foreach ($listing->getParamsValue() as $pv)
            {
                if($pv->getParam()->getParamKey() == 'kitchen_square')
                {
                    $kuh_square = $dom->createElement('kitchen-space');
                    $value = $dom->createElement('value',$pv->getValue());
                    $kuh_square->appendChild($value);
                    $unit = $dom->createElement('unit','кв. м');
                    $kuh_square->appendChild($unit);
                    $offer->appendChild($kuh_square);
                }
            }


            foreach ($listing->getParamsValue() as $pv)
            {
                if($pv->getParam()->getParamKey() == 'q_rooms')
                {
                    $rooms = $dom->createElement('rooms',$pv->getValue());
                    $offer->appendChild($rooms);
                    $rooms_offered = $dom->createElement('rooms-offered',$pv->getValue());
                    $offer->appendChild($rooms_offered);
                }
            }

            foreach ($listing->getParamsValue() as $pv)
            {
                if($pv->getParam()->getParamKey() == 'level')
                {
                    $floor = $dom->createElement('floor',$pv->getValue());
                    $offer->appendChild($floor);
                }
            }

            foreach ($listing->getParamsValue() as $pv)
            {
                if($pv->getParam()->getParamKey() == 'levels')
                {
                    $floor = $dom->createElement('floors-total',$pv->getValue());
                    $offer->appendChild($floor);
                }
            }

            $root->appendChild($offer);
        }
        $dom->save($this->config['xml_file']['lun']); // Сохраняем полученный XML-документ в файл
    }
}