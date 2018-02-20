<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilter;

Class AddListingForm extends Form
{
    protected $entityManager;

    protected $stepData;

    /**
     * AddListing constructor.
     * @param $step
     * @throws \Exception
     */
    public function __construct($step, $entityManager, $stepData)
    {
        if (!is_int($step) || $step < 1 || $step > 3)
            throw new \Exception('Не корректный шаг');

        if ($entityManager)
            $this->entityManager = $entityManager;

        if ($stepData)
            $this->stepData = $stepData;

        //определяем имя формы
        parent::__construct('add_listing-from');

        // Задаем метод POST для этой формы
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-wizard');
        if ($step == 3)
            $this->setAttribute('enctype', 'multipart/form-data');

        $this->addElements($step);
        $this->addInputFilter($step);
    }

    /**
     * Этот метод добавляет элементы к форме (поля ввода и кнопку отправки формы).
     */
    protected function addElements($step)
    {
        if ($step == 1)
        {
            //deal_type
            $this->add([
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'deal_type',
                'attributes' => [
                    'class' => 'form-control'
                ],
                'options' => [
                    'label' => 'Тип сделки',
                    'object_manager' => $this->entityManager,
                    'target_class' => 'Admin\Entity\DealCategories',
                    'property' => 'name',
                ],
            ]);

            //property_type
            $this->add([
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'property_type',
                'attributes' => [
                    'class' => 'form-control'
                ],
                'options' => [
                    'label' => 'Тип недвижимости',
                    'object_manager' => $this->entityManager,
                    'target_class' => 'Admin\Entity\PropertyCategory',
                    'property' => 'name',
                ],
            ]);

            //com_property_type
            $this->add([
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'com_property_type',
                'attributes' => [
                    'class' => 'form-control'
                ],
                'options' => [
                    'label' => 'Тип коммерческой недвижимости',
                    'object_manager' => $this->entityManager,
                    'target_class' => 'Admin\Entity\PropertyType',
                    'property' => 'name',
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => ['criteria' => ['categoryId' => 4],]
                    ),
                ],
            ]);

            //city
            $this->add([
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'city',
                'attributes' => [
                    'class' => 'form-control'
                ],
                'options' => [
                    'label' => 'Город',
                    'object_manager' => $this->entityManager,
                    'target_class' => 'Admin\Entity\City',
                    'property' => 'name',
                ],
            ]);

            //district
            $this->add([
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'district',
                'attributes' => [
                    'class' => 'form-control'
                ],
                'options' => [
                    'label' => 'Район',
                    'object_manager' => $this->entityManager,
                    'target_class' => 'Admin\Entity\District',
                    'property' => 'name',
                ],
            ]);

            //microdistrict
            $this->add([
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'microdistrict',
                'attributes' => [
                    'class' => 'form-control'
                ],
                'options' => [
                    'disable_inarray_validator' => true,
                    'label' => 'Микрорайон',
                    'object_manager' => $this->entityManager,
                    'target_class' => 'Admin\Entity\Microdistrict',
                    'property' => 'name',
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => ['criteria' => ['districtId' => 1],]
                    ),
                ],
            ]);

            //subway_station
            $this->add([
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'subway_station',
                'attributes' => [
                    'class' => 'form-control'
                ],
                'options' => [
                    'label' => 'Станция метро',
                    'object_manager' => $this->entityManager,
                    'target_class' => 'Admin\Entity\SubwayStation',
                    'property' => 'name',
                ],
            ]);

            //street
            $this->add([
                'type' => 'text',
                'name' => 'street',
                'attributes' => [
                    'id' => 'street',
                    'class' => 'form-control',
                    'placeholder' => 'Название улицы'
                ],
                'options' => [
                    'label' => 'Улица',
                ],
            ]);

            //house_number
            $this->add([
                'type' => 'text',
                'name' => 'house_number',
                'attributes' => [
                    'class' => 'form-control',
                    'placeholder' => 'Введите номер дома'
                ],
                'options' => [
                    'label' => 'Номер дома',
                ],
            ]);

            //flat_number
            $this->add([
                'type' => 'text',
                'name' => 'flat_number',
                'attributes' => [
                    'class' => 'form-control digital',
                    'placeholder' => 'Введите номер квартиры'
                ],
                'options' => [
                    'label' => 'Номер квартиры',
                ],
            ]);

            //phone
            $this->add([
                'type' => 'Zend\Form\Element\Collection',
                'name' => 'phone',
                'options' => [
                    'label' => 'Телефон',
                    'count' => 1,
                    'should_create_template' => true,
                    'allow_add' => true,
                    'target_element' => [
                        'type' => \Zend\Form\Element\Text::class,
                        'attributes' => [
                            'class' => 'form-control phone_number',
                            'placeholder' => 'Введите номер телефона'
                        ],
                    ],
                ],
            ]);

            //submit
            $this->add([
                'type' => 'submit',
                'name' => 'submit',
                'attributes' => [
                    'value' => 'Следующий шаг',
                    'id' => 'submitbutton',
                    'class' => ''
                ],
            ]);
        }
        elseif ($step == 2)
        {
            if ($this->stepData['step1']['property_type'] == 1)//Flat
            {
                //q_rooms
                $this->add([
                    'type' => 'select',
                    'name' => 'q_rooms',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Количество комнат',
                        'value_options' => [
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ]
                    ],
                ]);

                //plan
                $this->add([
                    'type' => 'select',
                    'name' => 'plan',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Планировка',
                        'value_options' => [
                            'Раздельные комнаты' => 'Раздельные комнаты',
                            'Проходные комнаты' => 'Проходные комнаты',
                            'Студия' => 'Студия',
                            'Свободная планировка' => 'Свободная планировка',
                        ]
                    ],
                ]);

                //common_square
                $this->add([
                    'type' => 'text',
                    'name' => 'common_square',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Общая площадь'
                    ],
                    'options' => [
                        'label' => 'Площадь общая',
                    ],
                ]);

                //real_square
                $this->add([
                    'type' => 'text',
                    'name' => 'real_square',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Жилая площадь'
                    ],
                    'options' => [
                        'label' => 'Жилая',
                    ],
                ]);

                //kitchen_square
                $this->add([
                    'type' => 'text',
                    'name' => 'kitchen_square',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Площадь кухни'
                    ],
                    'options' => [
                        'label' => 'Кухня',
                    ],
                ]);

                //balkon_square
                $this->add([
                    'type' => 'text',
                    'name' => 'balkon_square',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Площадь балкона'
                    ],
                    'options' => [
                        'label' => 'Балкон',
                    ],
                ]);

                //level
                $this->add([
                    'type' => 'text',
                    'name' => 'level',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'От 1 до 50'
                    ],
                    'options' => [
                        'label' => 'Этаж',
                    ],
                ]);

                //levels
                $this->add([
                    'type' => 'text',
                    'name' => 'levels',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'От 1 до 50'
                    ],
                    'options' => [
                        'label' => 'Этажность',
                    ],
                ]);

                //price
                $this->add([
                    'type' => 'text',
                    'name' => 'price',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Цена'
                    ],
                    'options' => [
                        'label' => 'Цена',
                    ],
                ]);

                //currency
                $this->add([
                    'type' => 'select',
                    'name' => 'currency',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Валюта',
                        'value_options' => [
                            '1' => 'USD',
                            '2' => 'UAH',
                            '3' => 'EUR',
                        ]
                    ],
                ]);

                //description
                $this->add([
                    'type' => 'textarea',
                    'name' => 'description',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Описание'
                    ],
                    'options' => [
                        'label' => 'Описание',
                    ],
                ]);

                //san_node
                $this->add([
                    'type' => 'select',
                    'name' => 'san_node',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Сан узел',
                        'value_options' => [
                            'Совмещенный' => 'Совмещенный',
                            'Раздельный' => 'Раздельный',
                        ]
                    ],
                ]);

                //build_type
                $this->add([
                    'type' => 'select',
                    'name' => 'build_type',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Тип здания',
                        'value_options' => [
                            'Вторичный рынок' => 'Вторичный рынок',
                            'Новострой' => 'Новострой',
                        ]
                    ],
                ]);

                //type_wall
                $this->add([
                    'type' => 'select',
                    'name' => 'type_wall',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Тип стен',
                        'value_options' => [
                            'Кирпичный' => 'Кирпичный',
                            'Панельный' => 'Панельный',
                            'Монолитный' => 'Монолитный',
                            'Блочный' => 'Блочный',
                            'Деревянный' => 'Деревянный',
                        ]
                    ],
                ]);

                //type_window
                $this->add([
                    'type' => 'select',
                    'name' => 'type_window',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Планировка',
                        'value_options' => [
                            'Пластиковые' => 'Пластиковые',
                            'Деревянные' => 'Деревянные',
                        ]
                    ],
                ]);

                //type_warm
                $this->add([
                    'type' => 'select',
                    'name' => 'type_warm',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Тип отопления',
                        'value_options' => [
                            'Центральное' => 'Центральное',
                            'Индивидуальное' => 'Индивидуальное',
                        ]
                    ],
                ]);
            }
            if ($this->stepData['step1']['property_type'] == 2)//Room
            {
                //common_square
                $this->add([
                    'type' => 'text',
                    'name' => 'common_square',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Общая площадь'
                    ],
                    'options' => [
                        'label' => 'Площадь общая',
                    ],
                ]);

                //kitchen_square
                $this->add([
                    'type' => 'text',
                    'name' => 'kitchen_square',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Площадь кухни'
                    ],
                    'options' => [
                        'label' => 'Кухня',
                    ],
                ]);

                //level
                $this->add([
                    'type' => 'text',
                    'name' => 'level',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'От 1 до 50'
                    ],
                    'options' => [
                        'label' => 'Этаж',
                    ],
                ]);

                //levels
                $this->add([
                    'type' => 'text',
                    'name' => 'levels',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'От 1 до 50'
                    ],
                    'options' => [
                        'label' => 'Этажность',
                    ],
                ]);

                //price
                $this->add([
                    'type' => 'text',
                    'name' => 'price',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Цена'
                    ],
                    'options' => [
                        'label' => 'Цена',
                    ],
                ]);

                //currency
                $this->add([
                    'type' => 'select',
                    'name' => 'currency',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Валюта',
                        'value_options' => [
                            '1' => 'USD',
                            '2' => 'UAH',
                            '3' => 'EUR',
                        ]
                    ],
                ]);

                //description
                $this->add([
                    'type' => 'textarea',
                    'name' => 'description',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Описание'
                    ],
                    'options' => [
                        'label' => 'Описание',
                    ],
                ]);

                //san_node
                $this->add([
                    'type' => 'select',
                    'name' => 'san_node',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Сан узел',
                        'value_options' => [
                            'Совмещенный' => 'Совмещенный',
                            'Раздельный' => 'Раздельный',
                        ]
                    ],
                ]);

                //build_type
                $this->add([
                    'type' => 'select',
                    'name' => 'build_type',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Тип здания',
                        'value_options' => [
                            'Вторичный рынок' => 'Вторичный рынок',
                            'Новострой' => 'Новострой',
                        ]
                    ],
                ]);

                //type_wall
                $this->add([
                    'type' => 'select',
                    'name' => 'type_wall',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Тип стен',
                        'value_options' => [
                            'Кирпичный' => 'Кирпичный',
                            'Панельный' => 'Панельный',
                            'Монолитный' => 'Монолитный',
                            'Блочный' => 'Блочный',
                            'Деревянный' => 'Деревянный',
                        ]
                    ],
                ]);

                //type_window
                $this->add([
                    'type' => 'select',
                    'name' => 'type_window',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Планировка',
                        'value_options' => [
                            'Пластиковые' => 'Пластиковые',
                            'Деревянные' => 'Деревянные',
                        ]
                    ],
                ]);

                //type_wall
                $this->add([
                    'type' => 'select',
                    'name' => 'type_warm',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Тип отопления',
                        'value_options' => [
                            'Центральное' => 'Центральное',
                            'Индивидуальное' => 'Индивидуальное',
                        ]
                    ],
                ]);
            }
            if ($this->stepData['step1']['property_type'] == 3)//House
            {
                //q_rooms
                $this->add([
                    'type' => 'select',
                    'name' => 'q_rooms',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Количество комнат',
                        'value_options' => [
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ]
                    ],
                ]);

                //plan
                $this->add([
                    'type' => 'select',
                    'name' => 'plan',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Планировка',
                        'value_options' => [
                            'Раздельные комнаты' => 'Раздельные комнаты',
                            'Проходные комнаты' => 'Проходные комнаты',
                            'Студия' => 'Студия',
                            'Свободная планировка' => 'Свободная планировка',
                        ]
                    ],
                ]);

                //levels
                $this->add([
                    'type' => 'text',
                    'name' => 'levels',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'От 1 до 50'
                    ],
                    'options' => [
                        'label' => 'Этажность',
                    ],
                ]);

                //san_node
                $this->add([
                    'type' => 'select',
                    'name' => 'san_node',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Сан узел',
                        'value_options' => [
                            'Совмещенный' => 'Совмещенный',
                            'Раздельный' => 'Раздельный',
                        ]
                    ],
                ]);

                //price
                $this->add([
                    'type' => 'text',
                    'name' => 'price',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Цена'
                    ],
                    'options' => [
                        'label' => 'Цена',
                    ],
                ]);

                //currency
                $this->add([
                    'type' => 'select',
                    'name' => 'currency',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Валюта',
                        'value_options' => [
                            '1' => 'USD',
                            '2' => 'UAH',
                            '3' => 'EUR',
                        ]
                    ],
                ]);

                //description
                $this->add([
                    'type' => 'textarea',
                    'name' => 'description',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Описание'
                    ],
                    'options' => [
                        'label' => 'Описание',
                    ],
                ]);

                //common_square
                $this->add([
                    'type' => 'text',
                    'name' => 'common_square',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Общая площадь'
                    ],
                    'options' => [
                        'label' => 'Площадь общая',
                    ],
                ]);

                //real_square
                $this->add([
                    'type' => 'text',
                    'name' => 'real_square',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Жилая площадь'
                    ],
                    'options' => [
                        'label' => 'Жилая',
                    ],
                ]);

                //kitchen_square
                $this->add([
                    'type' => 'text',
                    'name' => 'kitchen_square',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Площадь кухни'
                    ],
                    'options' => [
                        'label' => 'Кухня',
                    ],
                ]);

                //size_land
                $this->add([
                    'type' => 'text',
                    'name' => 'size_land',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Соток'
                    ],
                    'options' => [
                        'label' => 'Размер участка',
                    ],
                ]);

                //type_wall
                $this->add([
                    'type' => 'select',
                    'name' => 'type_wall',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Тип стен',
                        'value_options' => [
                            'Кирпичный' => 'Кирпичный',
                            'Панельный' => 'Панельный',
                            'Монолитный' => 'Монолитный',
                            'Блочный' => 'Блочный',
                            'Деревянный' => 'Деревянный',
                        ]
                    ],
                ]);

                //type_window
                $this->add([
                    'type' => 'select',
                    'name' => 'type_window',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'options' => [
                        'label' => 'Планировка',
                        'value_options' => [
                            'Пластиковые' => 'Пластиковые',
                            'Деревянные' => 'Деревянные',
                        ]
                    ],
                ]);
            }
            if ($this->stepData['step1']['property_type'] == 4)//Коммерческая недвижимость
            {
                //
            }
        }
        elseif ($step == 3)
        {
            $this->add([
                'type' => 'file',
                'isArray' => true,
                'multiple' => true,
                'name' => 'listing_images',
                'attributes' => [
                    'id' => 'listing_images',
                    'multiple' => 'multiple'
                ],
                'options' => [
                    'label' => ' ',
                ],
            ]);

            $this->add([
                'type' => 'hidden',
                'name' => 'crop',
                'required' => false
            ]);

            $this->add([
                'type' => 'hidden',
                'name' => 'order',
                'required' => false
            ]);
        }

    }

    /**
     * Этот метод создает фильтр входных данных (используется для фильтрации/валидации).
     */
    private function addInputFilter($step)
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        if ($step == 1) {
            $inputFilter->add([
                'name' => 'deal_type',
                'required' => true,
                'filters' => [

                ],
                'validators' => [
                    [
                        'name' => 'Between',
                        'options' => [
                            'min' => 1,
                            'max' => 2,
                            'messages' => [
                                \Zend\Validator\Between::NOT_BETWEEN => "Выбрано неподходящее значение",
                            ]
                        ]
                    ]
                ]
            ]);

            $inputFilter->add([
                'name' => 'property_type',
                'required' => true,
                'filters' => [

                ],
                'validators' => [
                    [
                        'name' => 'Between',
                        'options' => [
                            'min' => 1,
                            'max' => 4,
                            'messages' => [
                                \Zend\Validator\Between::NOT_BETWEEN => "Выбрано неподходящее значение",
                            ]
                        ]
                    ]
                ]
            ]);

            $inputFilter->add([
                'name' => 'com_property_type',
                'required' => false,
            ]);

            $inputFilter->add([
                'name' => 'city',
                'required' => true,
                'filters' => [

                ],
                'validators' => [
                    [
                        'name' => 'Between',
                        'options' => [
                            'min' => 1,
                            'max' => 1,
                            'messages' => [
                                \Zend\Validator\Between::NOT_BETWEEN => "Выбрано неподходящее значение",
                            ]
                        ]
                    ]
                ]
            ]);

            $inputFilter->add([
                'name' => 'district',
                'required' => true,
                'filters' => [

                ],
                'validators' => [
                    [
                        'name' => 'Between',
                        'options' => [
                            'min' => 1,
                            'max' => 10,
                            'messages' => [
                                \Zend\Validator\Between::NOT_BETWEEN => "Выбрано неподходящее значение",
                            ]
                        ]
                    ]
                ]
            ]);

            $inputFilter->add([
                'name' => 'street',
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
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Поле не должно быть пустым',
                            ]
                        ]
                    ],
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 5,
                            'max' => 4096,
                            'messages' => [
                                \Zend\Validator\StringLength::TOO_SHORT => 'Улица слишном короткая',
                            ]
                        ],
                    ],
                ]
            ]);

            $inputFilter->add([
                'name' => 'house_number',
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
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Поле не должно быть пустым',
                            ]
                        ]
                    ],
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'max' => 8,
                            'messages' => [
                                \Zend\Validator\StringLength::TOO_LONG => "Максимум 8 символов",
                            ]
                        ]
                    ]
                ]
            ]);

            $inputFilter->add([
                'name' => 'flat_number',
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'break_chain_on_failure' => true,
                            'messages' => [
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Поле не должно быть пустым',
                            ]
                        ]
                    ],
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'break_chain_on_failure' => true,
                            'max' => '4',
                            'messages' => [
                                \Zend\Validator\StringLength::TOO_LONG => "Максимум 4 символа",
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
                    [
                        'name' => 'GreaterThan',
                        'options' => [
                            'break_chain_on_failure' => true,
                            'min' => 1,
                            'inclusive' => true,
                            'messages' => [
                                \Zend\Validator\GreaterThan::NOT_GREATER_INCLUSIVE => "Число должно быть боьше 0",
                            ]
                        ]
                    ]
                ]
            ]);

            $inputFilter->add([
                'name' => 'phone',
                'type' => \Zend\InputFilter\ArrayInput::class,
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'break_chain_on_failure' => true,
                            'messages' => [
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Поле не должно быть пустым',
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
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 10,
                            'max' => 10,
                            'messages' => [
                                \Zend\Validator\StringLength::TOO_SHORT => "Телефон должен состоять из '%max%' символов",
                                \Zend\Validator\StringLength::TOO_LONG => "Телефон должен состоять из '%max%' символов",
                            ]
                        ]
                    ]
                ]
            ]);

        } elseif ($step == 2) {
            if (in_array($this->stepData['step1']['property_type'], [1, 2])) {
                $inputFilter->add([
                    'name' => 'common_square',
                    'required' => false,
                    'filters' => [
                        ['name' => 'StringTrim']
                    ],
                    'validators' => [
                        [
                            'name' => 'Digits',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'messages' => [
                                    \Zend\Validator\Digits::NOT_DIGITS => "Разрешается вводить только цифры",
                                ]
                            ]
                        ],
                        [
                            'name' => 'GreaterThan',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'min' => 1,
                                'inclusive' => true,
                                'messages' => [
                                    \Zend\Validator\GreaterThan::NOT_GREATER_INCLUSIVE => "Число должно быть боьше 0",
                                ]
                            ]
                        ],
                        [
                            'name' => 'StringLength',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'max' => '4',
                                'messages' => [
                                    \Zend\Validator\StringLength::TOO_LONG => "Максимум 4 символа",
                                ]
                            ]
                        ],
                    ]

                ]);
                $inputFilter->add([
                    'name' => 'kitchen_square',
                    'required' => false,
                    'filters' => [
                        ['name' => 'StringTrim']
                    ],
                    'validators' => [
                        [
                            'name' => 'Digits',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'messages' => [
                                    \Zend\Validator\Digits::NOT_DIGITS => "Разрешается вводить только цифры",
                                ]
                            ]
                        ],
                        [
                            'name' => 'GreaterThan',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'min' => 1,
                                'inclusive' => true,
                                'messages' => [
                                    \Zend\Validator\GreaterThan::NOT_GREATER_INCLUSIVE => "Число должно быть боьше 0",
                                ]
                            ]
                        ],
                        [
                            'name' => 'StringLength',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'max' => '4',
                                'messages' => [
                                    \Zend\Validator\StringLength::TOO_LONG => "Максимум 4 символа",
                                ]
                            ]
                        ],
                    ]

                ]);
                $inputFilter->add([
                    'name' => 'level',
                    'required' => false,
                    'filters' => [
                        ['name' => 'StringTrim']
                    ],
                    'validators' => [
                        [
                            'name' => 'Digits',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'messages' => [
                                    \Zend\Validator\Digits::NOT_DIGITS => "Разрешается вводить только цифры",
                                ]
                            ]
                        ],
                        [
                            'name' => 'Between',
                            'options' => [
                                'min' => 1,
                                'max' => 50,
                                'break_chain_on_failure' => true,
                                'messages' => [
                                    \Zend\Validator\Between::NOT_BETWEEN => "Значение должно быть от 1 до 50",
                                ]
                            ]
                        ]
                    ]
                ]);
                $inputFilter->add([
                    'name' => 'levels',
                    'required' => false,
                    'filters' => [
                        ['name' => 'StringTrim']
                    ],
                    'validators' => [
                        [
                            'name' => 'Digits',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'messages' => [
                                    \Zend\Validator\Digits::NOT_DIGITS => "Разрешается вводить только цифры",
                                ]
                            ]
                        ],
                        [
                            'name' => 'Between',
                            'options' => [
                                'min' => 1,
                                'max' => 50,
                                'break_chain_on_failure' => true,
                                'messages' => [
                                    \Zend\Validator\Between::NOT_BETWEEN => "Значение должно быть от 1 до 50",
                                ]
                            ]
                        ]
                    ]
                ]);
            }

            if ($this->stepData['step1']['property_type'] == 1) {
                $inputFilter->add([
                    'name' => 'real_square',
                    'required' => false,
                    'filters' => [
                        ['name' => 'StringTrim']
                    ],
                    'validators' => [
                        [
                            'name' => 'Digits',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'messages' => [
                                    \Zend\Validator\Digits::NOT_DIGITS => "Разрешается вводить только цифры",
                                ]
                            ]
                        ],
                        [
                            'name' => 'GreaterThan',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'min' => 1,
                                'inclusive' => true,
                                'messages' => [
                                    \Zend\Validator\GreaterThan::NOT_GREATER_INCLUSIVE => "Число должно быть боьше 0",
                                ]
                            ]
                        ],
                        [
                            'name' => 'StringLength',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'max' => '4',
                                'messages' => [
                                    \Zend\Validator\StringLength::TOO_LONG => "Максимум 4 символа",
                                ]
                            ]
                        ],
                    ]

                ]);
                $inputFilter->add([
                    'name' => 'balkon_square',
                    'required' => false,
                    'filters' => [
                        ['name' => 'StringTrim']
                    ],
                    'validators' => [
                        [
                            'name' => 'Digits',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'messages' => [
                                    \Zend\Validator\Digits::NOT_DIGITS => "Разрешается вводить только цифры",
                                ]
                            ]
                        ],
                        [
                            'name' => 'GreaterThan',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'min' => 1,
                                'inclusive' => true,
                                'messages' => [
                                    \Zend\Validator\GreaterThan::NOT_GREATER_INCLUSIVE => "Число должно быть боьше 0",
                                ]
                            ]
                        ],
                        [
                            'name' => 'StringLength',
                            'options' => [
                                'break_chain_on_failure' => true,
                                'max' => '4',
                                'messages' => [
                                    \Zend\Validator\StringLength::TOO_LONG => "Максимум 4 символа",
                                ]
                            ]
                        ],
                    ]

                ]);
            }

            $inputFilter->add([
                'name' => 'price',
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
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Поле не должно быть пустым',
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
                    [
                        'name' => 'GreaterThan',
                        'options' => [
                            'break_chain_on_failure' => true,
                            'min' => 1,
                            'inclusive' => true,
                            'messages' => [
                                \Zend\Validator\GreaterThan::NOT_GREATER_INCLUSIVE => "Число должно быть боьше 0",
                            ]
                        ]
                    ],
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'break_chain_on_failure' => true,
                            'max' => '15',
                            'messages' => [
                                \Zend\Validator\StringLength::TOO_LONG => "Максимум 4 символа",
                            ]
                        ]
                    ],
                ]

            ]);

        } elseif ($step == 3) {
            $inputFilter->add([
                'type' => \Zend\InputFilter\FileInput::class,
                'name' => 'listing_images',
                'required' => false,
                'validators' => [
                    ['name' => 'FileUploadFile'],
                    [
                        'name' => 'FileMimeType',
                        'options' => [
                            'mimeType' => ['image/jpeg', 'image/png'],
                            'messages' => [
                                \Zend\Validator\File\MimeType::FALSE_TYPE => "Похоже вы пытаетесь загрузить файл(ы), которые не является изображением. Разрешается загружать только jpeg или png",
                            ]
                        ]
                    ],
                    ['name' => 'FileIsImage'],
                    [
                        'name' => 'FileImageSize',
                        'options' => [
                            'minWidth' => 128,
                            'minHeight' => 128,
                            'maxWidth' => 7000,
                            'maxHeight' => 7000,
                            'messages' => [
                                \Zend\Validator\File\ImageSize::WIDTH_TOO_SMALL => "Минимальная ширина изображения должна быть '%minwidth%px', а вы загружаете шириной '%width%px'",
                                \Zend\Validator\File\ImageSize::HEIGHT_TOO_SMALL => "Минимальная высота изображения должна быть '%minheight%px', а вы загружаете высота '%height%px'",
                            ]
                        ]
                    ],
                ],
                'filters' => [
//                    [
//                        'name' => 'FileRenameUpload',
//                        'options' => [
//                            'target'=> __DIR__ . '/../../../../public/assets/uploads/listing-images/1/',
////                            'target'=> __DIR__ .'/../../data/tmp-upload',
//                            'useUploadName'=>true,
//                            'useUploadExtension'=>true,
//                            'overwrite'=>true,
//                            'randomize'=>true
//                        ]
//                    ]
                ],
            ]);
        }
    }

}