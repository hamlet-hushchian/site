<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Admin\Entity\Listing;

class Bredcrumbs extends AbstractHelper
{
    private $data;
    private $page;
    private $city = 'Киев';
    private $source = 'url';
    private $id;

    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setSourceToId($id)
    {
        $this->source = 'id';
        $this->id = $id;
    }

    public function setParams($data)
    {
        $this->data = $data;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function render()
    {
        if($this->source == 'id')
        {
            $p_types = [
                'kvartira' => "квартир ",
            ];

            $listing = $this->entityManager->getRepository(Listing::class)->findOneById($this->id);
            $d_type = $listing->getDealType()->getName();
            $p_type = $listing->getPropertyType()->getNameLat();
            $p_type_link = $p_type == 'kvartira' ? 'kvartir' : $p_type;
            $city = $listing->getMicrodistrict()->getDistrict()->getCity()->getName();

            $start = ucfirst($d_type).' '.$p_types[$p_type].' в '.$city.'е';
            $startLink = '/'.$listing->getDealType()->getNameLat().'-'.$p_type_link.'-'.lcfirst($listing->getMicrodistrict()->getDistrict()->getCity()->getNameLat());

            $district = $listing->getMicrodistrict()->getDistrict()->getName().'  район';
            $districtLink = $startLink.'?district='.$listing->getMicrodistrict()->getDistrict()->getId();

            $microdistrict = $listing->getMicrodistrict()->getName();
            $microdistrictLink = $startLink.'?microdistrict='.$listing->getMicrodistrict()->getId();

            $result = '<div class="row bredcrumbs-wrap">';
            $result .= '<div class="col-xs-12 bredcrumbs">';
            $result .= '<a href="'.$startLink.'" class="bredcrumbs-item">' . $start . '</a>';
            $result .= ' <span>></span> ';
            $result .= '<a href="'.$districtLink.'" class="bredcrumbs-item">' . $district . '</a>';
            $result .= ' <span>></span> ';
            $result .= '<a href="'.$microdistrictLink.'" class="bredcrumbs-item">' . $microdistrict . '</a>';
            $result .= ' <span>></span> ';
            $result .= '<p class="bredcrumbs-item">' . $listing->getStreet() . '</p>';
            $result .= '</div></div>';
        }
        else
        {
            $result = '<div class="row bredcrumbs-wrap">
            <div class="col-xs-12 bredcrumbs">';
            if($this->city == 'Киев')
                $result .= '<a href="/" class="bredcrumbs-item">Недвижимость в Киеве</a>';
            $result .= ' <span>></span> ';
            if($this->data[0] == 'prodazha')
                $result .= '<a href="/prodazha-kvartir-kiev" class="bredcrumbs-item">Продажа</a>';
            elseif ($this->data[0] == 'arenda')
                $result .= '<a href="/arenda-kvartir-kiev" class="bredcrumbs-item">Аренда</a>';
            $result .= ' </div>
        </div>';
        }

        return $result;
    }
}

?>