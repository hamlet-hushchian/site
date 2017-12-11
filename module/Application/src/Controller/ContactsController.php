<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Application\Form\ContactLetterForm;
use Application\Entity\ContactLetter;

class ContactsController extends AbstractActionController
{
    private $entityManager;

    public function __construct($em)
    {
        $this->entityManager = $em;
    }

    public function onDispatch(MvcEvent $e)
    {
        // Вызываем метод базового класса onDispatch() и получаем ответ
        $response = parent::onDispatch($e);

        // Устанавливаем альтернативный лэйаут
        $this->layout()->setTemplate('layout/red-head-footer');

        // Возвращаем ответ
        return $response;
    }

    public function indexAction()
    {
        $form = new ContactLetterForm();
        $contactLetter = new ContactLetter();
        $form->bind($contactLetter);

        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isPost()) {
                $form->setData($this->params()->fromPost());
                if($form->isValid())
                {
                    $this->entityManager->persist($contactLetter);
                    $this->entityManager->flush();

                    $message = "<p><b>Имя: </b>".$contactLetter->getName().";</p>".
                        "<p><b>Телефон: </b>".$contactLetter->getPhone().";</p>".
                        "<p><b>Сообщение: </b>".$contactLetter->getMessage().";</p>";
                    $headers = "From: Redl \r\n";
                    $headers .= "Reply-To: no-reply@redl.com.ua\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                    mail('guschan12@gmail.com','Контактное письмо',$message,$headers);

                    echo json_encode(['success'=>true]);
                }
                else
                {
                    echo json_encode($form->getMessages());
                }
                exit(200);
            }
        }

        $this->layout()->setVariable('activeMenuItem', 'contacts');
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
