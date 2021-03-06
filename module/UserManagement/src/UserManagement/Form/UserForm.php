<?php
/**
 * Created by PhpStorm.
 * User: mracu
 * Date: 8/22/14
 * Time: 12:17 PM
 */

namespace UserManagement\Form;

use Zend\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserForm extends Form implements ObjectManagerAwareInterface
{
    protected $objectManager;

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('user');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'username',
            'type' => 'text',
            'options' => array(
                'label' => 'Username',
            ),
            'attributes' => array(
                'id' => 'username',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'id' => 'password',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'password_verify',
            'type' => 'password',
            'options' => array(
                'label' => 'Re-Password',
            ),
            'attributes' => array(
                'id' => 'password_verify',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'rememberme',
            'options' => array(
                'label' => 'Remember me',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
            )
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'id' => 'email',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'firstname',
            'type' => 'text',
            'options' => array(
                'label' => 'First name',
            ),
            'attributes' => array(
                'id' => 'firstname',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'lastname',
            'type' => 'text',
            'options' => array(
                'label' => 'Last name',
            ),
            'attributes' => array(
                'id' => 'lastname',
                'class' => 'form-control',
            ),
        ));

        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'name',
                'options' => array(
                    'object_manager' => $this->getObjectManager(),
                    'target_class'   => 'Module\Entity\Role',
                    'property'       => 'ComposedOfSeveralProperties',
                    'is_method'      => true,
                    'find_method'    => array(
                        'name'   => 'findAll',
                    ),
                ),
            )
        );

//        $this->add(array(
//            'type' => 'Zend\Form\Element\DateTime',
//            'name' => 'updated_at',
//            'options' => array(
//                'label' => 'Updated at',
//                'format' => 'Y-m-d\TH:iP'
//            ),
//            'attributes' => array(
//                'min' => '2010-01-01T00:00:00Z',
//                'max' => '2020-01-01T00:00:00Z',
//                'step' => '1', // minutes; default step interval is 1 min
//                'id' => 'updated_at',
//                'class' => 'form-control',
//            ),
//        ));
//
//        $this->add(array(
//            'type' => 'Zend\Form\Element\DateTime',
//            'name' => 'created_at',
//            'options' => array(
//                'label' => 'Created at',
//                'format' => 'Y-m-d\TH:iP'
//            ),
//            'attributes' => array(
//                'min' => '2010-01-01T00:00:00Z',
//                'max' => '2020-01-01T00:00:00Z',
//                'step' => '1', // minutes; default step interval is 1 min
//                'id' => 'created_at',
//                'class' => 'form-control',
//            ),
//        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary',
            ),
        ));
    }



    public function init()
    {
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'name',
                'options' => array(
                    'object_manager' => $this->getObjectManager(),
                    'target_class'   => 'Module\Entity\Role',
                    'property'       => 'ComposedOfSeveralProperties',
                    'is_method'      => true,
                    'find_method'    => array(
                        'name'   => 'findAll',
                    ),
                ),
            )
        );
    }

    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }
}