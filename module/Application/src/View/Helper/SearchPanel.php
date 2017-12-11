<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SearchPanel extends AbstractHelper
{
    private $data;

    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setParams($data)
    {
        $this->data = $data;
    }

    public function render()
    {
        $districts = $this->entityManager->getRepository(\Admin\Entity\District::class)->findAll();

        $result = '<section class="s_search">
    <div class="container">
        <div class="row x-select-wrap">
            <div class="x-select-item-wrap col-xs-12 col-md-2 ">
                <div class="x-select">';
        $result .= $this->data[0] == "prodazha" ? "Продажа" : "Аренда";
        $result .= '</div>
                <div class="x-select-tt">
                    <div data-value="prodazha" class="x-select-tt-item ';
        $result .= $this->data[0] == "prodazha" ? "hovered checked" : "";
        $result .= ' ">Продажа</div>
                    <div data-value="arenda" class="x-select-tt-item ';
        $result .= $this->data[0] == "arenda" ? "hovered checked" : "";
        $result .= '">Аренда</div>
                </div>
            </div>
            <div class="x-select-item-wrap col-xs-12 col-md-2 ">
                <div class="x-select">';
        if ($this->data[1] == "kvartir") {
            $result .= "Квартира";
        } elseif ($this->data[1] == "komnat") {
            $result .= "Комната";
        } else {
            $result .= "Дом";
        }
        $result .= '</div>
                <div class="x-select-tt">
                    <div data-value="kvartir" class="x-select-tt-item ';
        $result .= $this->data[1] == "kvartir" ? "hovered checked" : "";
        $result .= '">Квартира</div>
                    <div data-value="komnat" class="x-select-tt-item ';
        $result .= $this->data[1] == "komnat" ? "hovered checked" : "";
        $result .= '">Комната</div>
                    <div data-value="domov" class="x-select-tt-item ';
        $result .= $this->data[1] == "domov" ? "hovered checked" : "";
        $result .= '">Дом</div>
                </div>
            </div><div class="x-select-item-wrap col-xs-12 col-md-2">
                <div value="kiev" class="x-select">Киев</div>
            </div>
            <div data-default="Район" data-parent="rajon" class="x-select-item-wrap col-xs-12 col-md-2 checker tag">
                <div class="x-select">Район</div>
                <div class="x-select-tt">';
        foreach ($districts as $district) {
            if ($this->data[3]['district']) {
                $checked = in_array($district->getId(), $this->data[3]['district']) ? 'checked' : '';
                $result .= '<div data-value="' . $district->getId() . '" class="x-select-tt-item ' . $checked . '">' . $district->getName() . '</div>';
            } else {
                $result .= '<div data-value="' . $district->getId() . '" class="x-select-tt-item">' . $district->getName() . '</div>';
            }

        }

        $result .= '</div>
            </div>
            <div id="sb" class="x-select-item-wrap hidden-sm-down col-xs-12 col-md-2 text-center">
                <a href="" class="s_link">Показать</a>
            </div>
            <div id="rooms" data-default="Комнат"  data-parent="rooms" class="x-select-item-wrap col-xs-12 col-md-2 checker">
                <div value="null" class="x-select">Комнат</div>
                <div class="x-select-tt">';
        for ($i = 1; $i <= 6; $i++) {
            $j = $i == 6 ? '6+' : $i;
            if ($this->data[3]['rooms']) {
                $checked = in_array($i, $this->data[3]['rooms']) ? 'checked' : '';
                $result .= '<div data-value="' . $i . '" class="x-select-tt-item ' . $checked . '">' . $j . ' комнатная</div>';
            } else {
                $result .= '<div data-value="' . $i . '" class="x-select-tt-item">' . $j . ' комнатная</div>';
            }

        }
        $result .= '</div>
            </div>
            <div id="price" class="x-select-item-wrap col-xs-8 col-md-2">
                <div class="x-select">Цена</div>
                <div class="adv_select-tt x-select-tt">
                    <div class="adv_select_inputs">
                        <input type="text" value="';
        $result .= $this->data[3]['price_from'] ? $this->data[3]['price_from'][$this->data[3]['currency'][0]] : '';
        $result .= '" class="adv_from" id="price_from" name="price_from" placeholder="От">
                        <span>—</span>
                        <input type="text" value="';
        $result .= $this->data[3]['price_to'] ? $this->data[3]['price_to'][$this->data[3]['currency'][0]] : '';
        $result .= '" class="adv_to" id="price_to" name="price_to" placeholder="До">
                    </div>
                    <ul>
                        <li>4 000</li>
                        <li>6 000</li>
                        <li>8 000</li>
                        <li>10 000</li>
                        <li>12 000</li>
                        <li>14 000</li>
                    </ul>
                </div>
            </div>
            <div id="swiper" class="x-select-item-wrap col-xs-4 col-md-1 text-center">
                <div>';
        $result .= $this->data[3]['currency'] ? strtoupper($this->data[3]['currency'][0]) : 'UAH';
        $result .= '</div>
            </div>
            <div id="withphoto" class="x-select-item-wrap col-xs-5 col-md-2 single-check text-center">
только с фото <input type="checkbox" name="withphoto" />
            </div>
            <div id="byid" class="x-select-item-wrap col-xs-7  col-md-3 single-btn">
                <div class="x-input-wrap">
                    <input type="text" placeholder="Поиск по ID" />
                </div>
                <div class="x-sb">></div>
            </div>
            <div id="sb" class="x-select-item-wrap hidden-md-up col-xs-12 col-md-2 text-center">
                <a href="" class="s_link">Показать</a>
            </div>
        </div>

            <div class="col-xs-12 nopadding x-tag-container">

            </div>
    </div>
</section>';

        return $result;
    }
}

?>