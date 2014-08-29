<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Auth\Form\UserForm;
use Auth\Model\User;

class AuthController extends AbstractActionController
{

    protected $userTable;
    protected $storage;
    protected $authservice;

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                ->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
                ->get('Auth\Model\AppAuthStorage');
        }

        return $this->storage;
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Auth\Model\UserTable');
        }
        return $this->userTable;
    }


    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('home');
        }

        $form = new UserForm();
        $form->get('submit')->setValue('Sign In');

        return array(
            'form'      => $form,
        );
    }

    public function authenticateAction()
    {
        $form = new UserForm();
        $form->get('submit')->setValue('Sign In');

        $redirect = 'login';

        $request = $this->getRequest();
        if ($request->isPost()){

            $user = new User();
            $form->setInputFilter($user->getInputFilter());
            $form->setValidationGroup('username', 'password');
            $form->setData($request->getPost());

            if ($form->isValid()){

                //check authentication...
                $this->getAuthService()->getAdapter()
                    ->setIdentity($request->getPost('username'))
                    ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();
                foreach($result->getMessages() as $message)
                {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addErrorMessage($message);
                }

                if ($result->isValid()) {
                    $redirect = 'home';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->getSessionStorage()
                            ->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    $this->getAuthService()->getStorage()->write($request->getPost('username'));
                }
            }else
            {
                $this->flashmessenger()->addErrorMessage("Your username or password is incorrect.");
            }
        }
        return $this->redirect()->toRoute($redirect);
    }


    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }

    public function myprofileAction()
    {
        //if already login, redirect to success page
        if (!$this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('home');
        }

        try {
            $user = $this->getUserTable()->getUserByUsername($this->getAuthService()->getIdentity());
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('my-profile', array());
        }

        $user_id = $user->id;
        $form = new UserForm();
        $form->bind($user);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setValidationGroup('email', 'firstname', 'lastname');
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $user->id = $user_id;
                $this->getUserTable()->saveMyProfile($user);
                $this->flashmessenger()->addSuccessMessage("Data saved successfully");
                // Redirect to list of albums
                return $this->redirect()->toRoute('my-profile');
            }else
            {
                $this->flashmessenger()->addMessage($form->getMessages());
            }
        }

        return array(
            'form' => $form,
            'messages'  => $this->flashmessenger()->getMessages()
        );


    }

}