<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Model\User;
use Auth\Form\LoginForm;

class AuthController extends AbstractActionController
{
//    protected $albumTable;
//
//    public function getAlbumTable()
//    {
//        if (!$this->albumTable) {
//            $sm = $this->getServiceLocator();
//            $this->albumTable = $sm->get('Album\Model\AlbumTable');
//        }
//        return $this->albumTable;
//    }

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

    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('home');
        }

        $form = new LoginForm();
        $form->get('submit')->setValue('Sign In');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $user = new User();

            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                //check authentication...
                $this->getAuthService()->getAdapter()
                    ->setIdentity($request->getPost('username'))
                    ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();
                foreach($result->getMessages() as $message)
                {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addMessage($message);
                }

                if ($result->isValid()) {
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->getSessionStorage()
                            ->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    $this->getAuthService()->getStorage()->write($request->getPost('username'));
                    return $this->redirect()->toRoute('home');
                }
            }
        }
        return array(
            'form' => $form,
            'messages'  => $this->flashmessenger()->getMessages()
        );
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


    }

}