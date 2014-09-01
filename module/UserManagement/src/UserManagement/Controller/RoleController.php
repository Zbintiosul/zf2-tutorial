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
    UserManagement\Form\RoleForm,
    Doctrine\ORM\EntityManager,
    UserManagement\Entity\Role;

class RoleController extends AbstractActionController
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

    public function indexAction()
    {
        return new ViewModel(array(
            'roles' => $this->getEntityManager()->getRepository('UserManagement\Entity\Role')->findAll()
        ));
    }

    public function addAction()
    {

        $form = new RoleForm();

        //$form->get('genre_id')->setValueOptions($this->getGenreTable()->getSelectArrayGenres());
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $role = new Role();

            $form->setInputFilter($role->getInputFilter());

            $form->setValidationGroup('name');

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $role->populate($form->getData());
                $this->getEntityManager()->persist($role);

                $this->getEntityManager()->flush();

                // Redirect to list of users
                return $this->redirect()->toRoute('role');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('role', array(
                'action' => 'add'
            ));
        }

        // Get the User with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $role = $this->getEntityManager()->find('UserManagement\Entity\Role', $id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('role', array(
                'action' => 'index'
            ));
        }
        //die(print_r($user));
        $form = new RoleForm();
        $form->setBindOnValidate(false);
        $form->bind($role);
        $form->get('submit')->setAttribute('label', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setValidationGroup('name');
            $form->setInputFilter($role->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $form->bindValues();
                $this->getEntityManager()->flush();

                // Redirect to list of albums
                return $this->redirect()->toRoute('role');
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
            return $this->redirect()->toRoute('role');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $role = $this->getEntityManager()->find('UserManagement\Entity\Role', $id);

                $this->getEntityManager()->remove($role);

                $this->getEntityManager()->flush();
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('role');
        }

        return array(
            'id'    => $id,
            'role' => $this->getEntityManager()->find('UserManagement\Entity\Role', $id),
        );
    }
}