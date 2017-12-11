<?php
namespace Application\Service;

class SearchManager{
    public function genParamsFromQueryString($query)
    {
        $params = [];
        $paramsValue = explode('&',$query);
        if(!is_array($paramsValue) || count($paramsValue) == 0)
            return false;
        foreach ($paramsValue as $paramValue)
        {
            $paramValue = explode('=',$paramValue);
            $param = $paramValue[0];
            $value = $paramValue[1];
            $params[$param][] = $value;
        }

        if(isset($params['price_from']))
        {
            $price = $params['price_from'][0];
            $currency = isset($params['currency'][0]) ? $params['currency'][0] : 'usd';
            $params['price_from'] = $this->convertPrice($price,$currency);
        }

        if(isset($params['price_to']))
        {
            $price = $params['price_to'][0];
            $currency = isset($params['currency'][0]) ? $params['currency'][0] : 'usd';
            $params['price_to'] = $this->convertPrice($price,$currency);
        }

        $rate = $this->getExchangeRate();
//        var_dump($rate);
        return $params;
    }

        public function convertPrice($price, $currency,$butify = false)
    {
        $result = [];
        $rate = $this->getExchangeRate();
        switch ($currency)
        {
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
        if($butify)
        {
            foreach ($result as $k => $v)
            {
                $arr = str_split($v);
                $res = '';
                $j = 0;
                for($i = count($arr); $i>-1;$i--)
                {
                    $res = $arr[$i] . $res;
                    if($j % 3 == 0)
                        $res = ' '.$res;
                    $j++;
                }
                $result[$k] = $res;
            }
        }
        return $result;
    }

    private function getExchangeRate()
    {
        $config = parse_ini_file(__DIR__ . '/../../config/config.ini', true);
        return $config['EXCHANGE_RATE'];
    }
}