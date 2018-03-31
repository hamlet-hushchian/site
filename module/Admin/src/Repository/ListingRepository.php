<?php

namespace Admin\Repository;

use Admin\Entity\Listing;
use Doctrine\ORM\EntityRepository;

// Это пользовательский класс репозитория для сущности Listing.
class ListingRepository extends EntityRepository
{
    public function getAllListings()
    {
        $entityManager = $this->getEntityManager();
        $listings = $entityManager->getRepository(Listing::class)->findBy([], ['id' => 'DESC']);

        foreach ($listings as $listing) {
            $listing->setStreet($this->formatStreet($listing->getStreet()));

            $level = $listing->getParamsValue()[2]->getValue();
            $levels = $listing->getParamsValue()[3]->getValue();
            $q_rooms = $listing->getParamsValue()[0]->getValue();

            $levelsString = $this->formatLevels($level, $levels);
            $roomsString = $this->formatRooms($q_rooms);

            $listing->getParamsValue()['levelsString'] = $levelsString;
            $listing->getParamsValue()['roomsString'] = $roomsString;
        }
        return $listings;
    }

    public function getListing($id)
    {
        $entityManager = $this->getEntityManager();
        $listing = $entityManager->getRepository(Listing::class)->findOneById($id);

        $roomsString = $this->formatRooms($listing->getParamsValue()[1]->getValue());
        $levelsString = $this->formatLevels($listing->getParamsValue()[2]->getValue(), $listing->getParamsValue()[3]->getValue());

        $latLong = $this->getLatLong($listing->getStreet());
        $listing->lat = $latLong[0];
        $listing->long = $latLong[1];
        $listing->setStreet($this->formatStreet($listing->getStreet()));
        $listing->getParamsValue()['roomsString'] = $roomsString;
        $listing->getParamsValue()['levelsString'] = $levelsString;

        return $listing;
    }

    public function getSearchResult($d_type, $p_type, $city, $params)
    {
        $p_type = $p_type == 'kvartir' ? 'kvartira' : $p_type;
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('l,q_rooms,common_square', 'level', 'levels')
            ->from(Listing::class, 'l')
            ->join('l.dealType', 'd')
            ->join('l.propertyType', 'p')
            ->join('l.microdistrict', 'm')
            ->join('m.district', 'di')
            ->join('di.city', 'c')
            ->join('l.paramsValue', 'q_rooms', 'WITH', 'q_rooms.paramId = 2')
            ->join('l.paramsValue', 'common_square', 'WITH', 'common_square.paramId = 5')
            ->join('l.paramsValue', 'level', 'WITH', 'level.paramId = 3')
            ->join('l.paramsValue', 'levels', 'WITH', 'levels.paramId = 4')
            ->where($queryBuilder->expr()->andX('d.nameLat = :deal', 'p.nameLat = :property', 'c.nameLat = :city'))
            ->orderBy('l.dateAdd', 'DESC')
            ->setParameter('deal', $d_type)
            ->setParameter('property', $p_type)
            ->setParameter('city', $city);

        if (isset($params['rooms'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('q_rooms.value', ':qr'))
                ->setParameter('qr', $params['rooms']);
        }

        if (isset($params['price_from'])) {
            $queryBuilder->andWhere('l.price >= :priceFromUah AND l.currencyId = 2 OR l.price >= :priceFromUsd AND l.currencyId = 1 OR l.price >= :priceFromEur AND l.currencyId = 3')
                ->setParameter('priceFromUah', $params['price_from']['uah'])
                ->setParameter('priceFromUsd', $params['price_from']['usd'])
                ->setParameter('priceFromEur', $params['price_from']['eur']);
        }

        if (isset($params['price_to'])) {
            $queryBuilder->andWhere('l.price <= :priceToUah AND l.currencyId = 2 OR l.price <= :priceToUsd AND l.currencyId = 1 OR l.price <= :priceToEur AND l.currencyId = 3')
                ->setParameter('priceToUah', $params['price_to']['uah'])
                ->setParameter('priceToUsd', $params['price_to']['usd'])
                ->setParameter('priceToEur', $params['price_to']['eur']);
        }

        if (isset($params['district'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('di.id', ':districts'))
                ->setParameter('districts', $params['district']);
        }

        if (isset($params['microdistrict'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('m.id', ':microdistricts'))
                ->setParameter('microdistricts', $params['microdistrict']);
        }

        $listings = $queryBuilder->getQuery();

        return $listings;
    }

    public function getListingsForFeed()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('l')
            ->from(Listing::class, 'l')
            ->orderBy('l.dateAdd', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function getListingsForAdmin($params = false)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        if ($params && is_array($params)) {

            if (isset($params['pricefrom'])) {
                $currency = isset($params['currency']) ? strtolower($params['currency']) : 'usd';
                $params['pricefrom'] = $this->convertPrice($params['pricefrom'], $currency);
            }

            if (isset($params['priceto'])) {
                $currency = isset($params['currency']) ? strtolower($params['currency']) : 'usd';
                $params['priceto'] = $this->convertPrice($params['priceto'], $currency);
            }

            $queryBuilder->select('l')
                ->from(Listing::class, 'l')
                ->join('l.dealType', 'd')
                ->join('l.propertyType', 'p')
                ->join('l.microdistrict', 'm')
                ->join('m.district', 'di')
                ->join('di.city', 'c')
                ->join('l.paramsValue', 'q_rooms', 'WITH', 'q_rooms.paramId = 2')
                ->join('l.paramsValue', 'common_square', 'WITH', 'common_square.paramId = 5')
                ->join('l.paramsValue', 'level', 'WITH', 'level.paramId = 3')
                ->join('l.paramsValue', 'levels', 'WITH', 'levels.paramId = 4')
                ->orderBy('l.dateAdd', 'DESC');

            if (isset($params['dtype'])) {
                $queryBuilder->andWhere('d.nameLat = :dtype')
                    ->setParameter('dtype', $params['dtype']);
            }

            if (isset($params['ptype'])) {
                $queryBuilder->andWhere('p.nameLat = :ptype')
                    ->setParameter('ptype', $params['ptype']);
            }

            if (isset($params['rooms'])) {
                $queryBuilder->andWhere($queryBuilder->expr()->in('q_rooms.value', ':qr'))
                    ->setParameter('qr', $params['rooms']);
            }

            if (isset($params['pricefrom'])) {
                $queryBuilder->andWhere('l.price >= :priceFromUah AND l.currencyId = 2 OR l.price >= :priceFromUsd AND l.currencyId = 1 OR l.price >= :priceFromEur AND l.currencyId = 3')
                    ->setParameter('priceFromUah', $params['pricefrom']['uah'])
                    ->setParameter('priceFromUsd', $params['pricefrom']['usd'])
                    ->setParameter('priceFromEur', $params['pricefrom']['eur']);
            }

            if (isset($params['priceto'])) {
                $queryBuilder->andWhere('l.price <= :priceToUah AND l.currencyId = 2 OR l.price <= :priceToUsd AND l.currencyId = 1 OR l.price <= :priceToEur AND l.currencyId = 3')
                    ->setParameter('priceToUah', $params['priceto']['uah'])
                    ->setParameter('priceToUsd', $params['priceto']['usd'])
                    ->setParameter('priceToEur', $params['priceto']['eur']);
            }

            if (isset($params['district'])) {
                $queryBuilder->andWhere('di.id = :district')
                    ->setParameter('district', $params['district']);
            }

            if (isset($params['microdistrict'])) {
                $queryBuilder->andWhere($queryBuilder->expr()->in('m.id', ':microdistricts'))
                    ->setParameter('microdistricts', $params['microdistrict']);
            }

            if (isset($params['street'])) {
                $queryBuilder->andWhere('l.street LIKE :street')
                    ->setParameter('street', '%'.$params['street'].'%');
            }
        } else {
            $queryBuilder->select('l')
                ->from(Listing::class, 'l')
                ->orderBy('l.id', 'DESC');
        }
        return $queryBuilder->getQuery();
    }


    private function formatStreet($street)
    {
        $street = explode(',', $street);
        $street_sh = $street[0];

        if (preg_match('~улица~', $street[0]))
            $street_sh = preg_replace('~улица~', 'ул.', $street[0]);

        if (preg_match('~проспект~', $street[0]))
            $street_sh = preg_replace('~проспект~', 'просп.', $street[0]);

        return $street_sh;
    }

    private function formatLevels($level, $levels)
    {
        if ($level !== '' && $levels !== "") {
            $result = $level . ' этаж из ' . $levels;
        } else if ($level !== '' && $levels == '') {
            $result = $level . ' этаж';
        } else if ($level == '' && $levels !== '') {
            $result = $levels . ' этажей';
        } else if ($level == '' && $levels == '') {
            $result = 'Неизвстно';
        }
        return $result;
    }

    private function formatRooms($q_rooms)
    {
        $str = '';
        if ($q_rooms == 1)
            $str = ' комната';
        if ($q_rooms > 1 && $q_rooms < 5)
            $str = ' комнаты';
        if ($q_rooms >= 5)
            $str = ' комнат';
        return $q_rooms . $str;
    }

    private function getLatLong($addr)
    {
        $addr = urlencode($addr);
        $mapData = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDKOsmSnuOK7SPlEtXGhZdxUZWg4QNRcoQ&new_forward_geocoder=true&address=$addr"), true);
        $lat = $mapData['results'][0]['geometry']['bounds']['northeast']['lat'];
        $lng = $mapData['results'][0]['geometry']['bounds']['northeast']['lng'];
        return [$lat, $lng];
    }

    private function convertPrice($price, $currency, $beatify = false)
    {
        $result = [];
        $moduleConfig = include __DIR__ . '/../../config/module.config.php';
        $rate = $moduleConfig['exchange_rate'];
        switch ($currency) {
            case 'uah':
                $result['uah'] = round($price);
                $result['usd'] = round($price / $rate['usd']);
                $result['eur'] = round($price / $rate['eur']);
                break;
            case 'usd':
                $result['uah'] = round($price * $rate['usd']);
                $result['usd'] = round($price);
                $result['eur'] = round($result['uah'] / $rate['eur']);
                break;
            case 'eur':
                $result['uah'] = round($price * $rate['eur']);
                $result['usd'] = round($result['uah'] / $rate['usd']);
                $result['eur'] = round($price);
                break;
            default:
                $result['uah'] = round($price * $rate['usd']);
                $result['usd'] = round($price);
                $result['eur'] = round($result['uah'] / $rate['eur']);
                break;
        }
        if ($beatify) {
            foreach ($result as $k => $v) {
                $arr = str_split($v);
                $res = '';
                $j = 0;
                for ($i = count($arr); $i > -1; $i--) {
                    $res = $arr[$i] . $res;
                    if ($j % 3 == 0)
                        $res = ' ' . $res;
                    $j++;
                }
                $result[$k] = $res;
            }
        }
        return $result;
    }

}