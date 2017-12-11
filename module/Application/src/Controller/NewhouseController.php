<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NewhouseController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $this->layout()->setTemplate('layout/under-const');
        $this->layout()->setVariable('activeMenuItem', 'newhouse');
        return $viewModel;
    }
}
