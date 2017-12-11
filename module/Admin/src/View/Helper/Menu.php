<?php
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;

// Этот класс помощника вида отображает панель меню.
class Menu extends AbstractHelper
{
    // Массив пунктов меню.
    protected $items = [];

    // ID активного пункта.
    protected $activeItemId = '';

    // Конструктор.
    public function __construct($items=[])
    {
        $this->items = $items;
    }
    // Задаем пункты меню.
    public function setItems($items)
    {
        $this->items = $items;
    }

    // Задаем ID активных пунктов.
    public function setActiveItemId($activeItemId)
    {
        $this->activeItemId = $activeItemId;
    }


    // Визуализация меню.
    public function render()
    {
        if (count($this->items)==0)
            return ''; // Do nothing if there are no items.

        $result = '<ul id="main-menu" class="main-menu">';

        // Визуализация элементов
        foreach ($this->items as $item) {
            $result .= $this->renderItem($item);
        }

        $result .= '</ul>';
        return $result;
    }


    // Визуализирует элемент.
    protected function renderItem($item)
    {
        $id = isset($item['id']) ? $item['id'] : '';
        $isActive = ($id==$this->activeItemId);
        $label = isset($item['label']) ? $item['label'] : '';
        $icon = isset($item['icon']) ? $item['icon'] : '';

        $result = '';

        if(isset($item['dropdown'])) {

            $dropdownItems = $item['dropdown'];
            $isActiveDropdown = false;

            foreach ($dropdownItems as $dropdownItem)
            {
                if($dropdownItem['id'] == $this->activeItemId)
                    $isActiveDropdown = true;
            }

            $result .= '<li class="has-sub ' . ($isActiveDropdown?'expanded opened active':'') . '">';
            $result .= '<a href="#">';
            $result .= '<i class="' . $icon . '"></i>';
            $result .= '<span class="title">' . $label . '</span>';
            $result .= '</a>';

            $result .= '<ul>';

            foreach ($dropdownItems as $item) {
                $id = isset($item['id']) ? $item['id'] : '';
                $isActive = ($id==$this->activeItemId);
                $link = isset($item['link']) ? $item['link'] : '#';
                $label = isset($item['label']) ? $item['label'] : '';

                $result .= '<li ' . ($isActive ? 'class="active"' : '') . '>';
                $result .= '<a href="'.$link.'">'.$label.'</a>';
                $result .= '</li>';
            }

            $result .= '</ul>';
            $result .= '</li>';

        } else {
            $link = isset($item['link']) ? $item['link'] : '#';

            $result .= $isActive?'<li class="active">':'<li>';
            $result .= '<a href="'.$link.'"><i class="' . $icon . '"></i>'.$label.'</a>';
            $result .= '</li>';
        }

        return $result;
    }
}