<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class H1 extends AbstractHelper
{
    private $data;

    public function setParams($data)
    {
        $this->data = $data;
    }

    public function render()
    {
        $result = '<h1>';
        $result .= $this->data[0] == 'prodazha' ? 'Купить ' : 'Арендовать ';
        if($this->data[3]['rooms'])
        {
            for ($i = 0; $i < count($this->data[3]['rooms']); $i++)
            {
                $result .= $i == 0 ? $this->data[3]['rooms'][0] : ', '.$this->data[3]['rooms'][$i];
            }
            $result .= $this->data[1] == 'domov' ?'-комнатный ' : '-комнатную ';
        }
        $result .= $this->data[1] == 'kvartir' ? 'квартиру ' : ($this->data[1] == 'komnat' ? 'Комнату ' : 'Дом ');
        $result .= 'в Киеве</h1>';

        return $result;
    }
}

?>