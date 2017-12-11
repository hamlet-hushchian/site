<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilter;

Class SelectionForm extends Form
{
    public function __construct()
    {
        //Set the form name
        parent::__construct('selection_from');
        $this->setHydrator(new ClassMethods());

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Имя'
            ],
            'options' => [
                'label_attributes' => [
                    'class' => 'col-sm-12 col-form-label'
                ],
                'label' => 'Имя',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'attributes' => [
                'name' => 'phone',
                'class' => 'form-control',
                'placeholder' => 'Телефон'
            ],
            'options' => [
                'label_attributes' => [
                    'class' => 'col-sm-12 col-form-label'
                ],
                'label' => 'Телефон',
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'message',
            'attributes' => [
                'style' => 'max-width: 100%; min-height: 100px; max-height: 250px;',
                'class' => 'form-control',
                'placeholder' => 'Сообщение'
            ],
            'options' => [
                'label_attributes' => [
                    'class' => 'col-sm-12 col-form-label'
                ],
                'label' => 'Сообщение',
            ],
        ]);
    }

    protected function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim']
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'break_chain_on_failure' => true,
                        'messages' => [
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Введите имя',
                        ]
                    ]
                ],
            ]
        ]);

        $inputFilter->add([
            'name' => 'phone',
            'filters' => [
                ['name' => 'StringTrim']
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'break_chain_on_failure' => true,
                        'messages' => [
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Введите номер телефона',
                        ]
                    ]
                ],
                [
                    'name' => 'Digits',
                    'options' => [
                        'break_chain_on_failure' => true,
                        'messages' => [
                            \Zend\Validator\Digits::NOT_DIGITS => "Разрешается вводить только цифры",
                        ]
                    ]
                ],
            ]
        ]);
    }
}