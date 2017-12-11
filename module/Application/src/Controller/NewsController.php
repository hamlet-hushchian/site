<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NewsController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $this->layout()->setTemplate('layout/under-const');
        $this->layout()->setVariable('activeMenuItem', 'news');
        return $viewModel;
    }
}
