<?php

namespace UserManagement\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * A music album.
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="users")
 * @property string $username
 * @property string $password
 * @property string $password_verify
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property int $id
 */
class User implements InputFilterAwareInterface
{
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /** @ORM\Column(name="created_at", type="string", length=255) */
    protected $created_at;

    /** @ORM\Column(name="updated_at", type="string", length=255) */
    protected $updated_at;

    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    public function setUpdatedAt($now)
    {
        // WILL be saved in the database
        $this->updated_at = $now->format('Y-m-d H:i:s');
    }

    public function setCreatedAt($now)
    {
        // WILL be saved in the database
        $this->created_at = $now->format('Y-m-d H:i:s');
    }

    public function getCreatedAt()
    {
        // WILL be saved in the database
        $this->created_at;
    }

    public function setUsernameAndPassword($username,$password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        if (!empty($data['id']))
        $this->id = $data['id'];

        if (!empty($data['username']))
        $this->username =  $data['username'];

        if (!empty($data['password']))
        $this->password = $data['password'];

        if (!empty($data['email']))
        $this->email = $data['email'];

        if (!empty($data['firstname']))
        $this->firstname = $data['firstname'];

        if (!empty($data['lastname']))
        $this->lastname = $data['lastname'];
        //$this->setUpdated();

       // $this->updated_at = date('Y-m-d H:m:s');
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'       => 'id',
                'required'   => true,
                'filters' => array(
                    array('name'    => 'Int'),
                ),
            )));

            $inputFilter->add(array(
                'name'     => 'username',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Zend\Validator\EmailAddress',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 5,
                            'max'      => 255,
                            'messages' => array(
                                \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email address format is invalid'
                            )
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'firstname',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'lastname',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'password',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'password_verify',
                'required' => true,
                'filters' => [ ['name' => 'StringTrim'], ],
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array( 'min' => 6 ),
                    ),
                    array(
                        'name' => 'identical',
                        'options' => array(
                            'token' => 'password',
                            'messages' => array(
                                \Zend\Validator\Identical::NOT_SAME => 'The passwords do not match.',
                            ),
                        ),
                    )),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}