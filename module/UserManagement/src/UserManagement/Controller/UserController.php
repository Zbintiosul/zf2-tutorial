<?php
/**
 * Created by PhpStorm.
 * User: mracu
 * Date: 8/22/14
 * Time: 10:35 AM
 */

namespace UserManagement\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    UserManagement\Form\UserForm,
    Doctrine\ORM\EntityManager,
    UserManagement\Entity\User;

class UserController extends AbstractActionController
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    public function getUserEntity()
    {
        return $this->getEntityManager()->getRepository('UserManagement\Entity\User');
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'users' => $this->getEntityManager()->getRepository('UserManagement\Entity\User')->findAll()
        ));
    }

    public function addAction()
    {

        $form = new UserForm();

        //$form->get('genre_id')->setValueOptions($this->getGenreTable()->getSelectArrayGenres());
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $user = new User();

            $form->setInputFilter($user->getInputFilter());

            $form->setValidationGroup('username', 'password', 'password_verify', 'email', 'firstname', 'lastname');

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user->populate($form->getData());
                $this->getEntityManager()->persist($user);

                $this->getEntityManager()->flush();

                // Redirect to list of users
                return $this->redirect()->toRoute('user-man');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user-man', array(
                'action' => 'add'
            ));
        }

        // Get the User with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $user = $this->getEntityManager()->find('UserManagement\Entity\User', $id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('user-man', array(
                'action' => 'index'
            ));
        }
        $userIni = $user;
        //die(print_r($user));
        $form = new UserForm();
        $form->setBindOnValidate(false);
        $form->bind($user);
        $form->get('submit')->setAttribute('label', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setValidationGroup('email', 'firstname', 'lastname');
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $form->bindValues();
                $user->updatedTimestamps();
                $this->getEntityManager()->flush();

                // Redirect to list of albums
                return $this->redirect()->toRoute('user-man');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user-man');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $user = $this->getEntityManager()->find('UserManagement\Entity\User', $id);

                $this->getEntityManager()->remove($user);

                $this->getEntityManager()->flush();
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('user-man');
        }

        return array(
            'id'    => $id,
            'user' => $this->getEntityManager()->find('UserManagement\Entity\User', $id),
        );
    }
}