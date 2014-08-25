<?php
/**
 * Created by PhpStorm.
 * User: mracu
 * Date: 8/22/14
 * Time: 10:35 AM
 */

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Genre;
use Album\Form\GenreForm;

class GenreController extends AbstractActionController
{
    protected $genreTable;

    public function getGenreTable()
    {
        if (!$this->genreTable) {
            $sm = $this->getServiceLocator();
            $this->genreTable = $sm->get('Album\Model\GenreTable');
        }
        return $this->genreTable;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'genres' => $this->getGenreTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new GenreForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $genre = new Genre();
            $form->setInputFilter($genre->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $genre->exchangeArray($form->getData());
                $this->getGenreTable()->saveGenre($genre);

                // Redirect to list of genres
                return $this->redirect()->toRoute('genre');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('genre', array(
                'action' => 'add'
            ));
        }

        // Get the Genre with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $genre = $this->getGenreTable()->getGenre($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('genre', array(
                'action' => 'index'
            ));
        }

        $form  = new GenreForm();
        $form->bind($genre);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($genre->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getGenreTable()->saveGenre($genre);

                // Redirect to list of genres
                return $this->redirect()->toRoute('genre');
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
            return $this->redirect()->toRoute('genre');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getGenreTable()->deleteGenre($id);
            }

            // Redirect to list of genres
            return $this->redirect()->toRoute('genre');
        }

        return array(
            'id'    => $id,
            'genre' => $this->getGenreTable()->getGenre($id)
        );
    }


}