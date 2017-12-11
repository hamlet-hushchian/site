<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CountResults extends AbstractHelper
{
    private $count;

    public function setCount($count)
    {
        $this->count = $count;
    }

    public function render()
    {
        $length = strlen((string)$this->count);
        $digits = str_split((string)$this->count);
        $num = $length > 1 ? end($digits) : $this->count;
        $ending = 'ий';

        if($num > 0)
        {
            if($num < 5)
            {
                if ($digits[count($digits)-2] == 1)
                {
                    $ending = 'ий';
                }
                else
                {
                    if($num == 1)
                    {
                        $ending = 'ие';
                    }
                    else
                    {
                        $ending = 'ия';
                    }
                }
            }
        }

        $result = '<div class="count">';
            $result .= $this->count.' предложен'.$ending;
        $result .= '</div>';

        return $result;
    }
}

?>