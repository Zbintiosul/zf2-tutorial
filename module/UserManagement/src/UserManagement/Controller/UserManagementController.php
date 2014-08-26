<?php
/**
 * Created by PhpStorm.
 * User: mracu
 * Date: 8/22/14
 * Time: 10:35 AM
 */

namespace UserManagement\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UserManagement\Model\User;
use UserManagement\Form\UserForm;

class UserManagementController extends AbstractActionController
{
    protected $userTable;
    protected $genreTable;

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('UserManagement\Model\UserTable');
        }
        return $this->userTable;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'users' => $this->getUserTable()->fetchAll(),
        ));
    }

}