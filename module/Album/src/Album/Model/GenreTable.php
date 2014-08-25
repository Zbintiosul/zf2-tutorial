<?php

 namespace Album\Model;

 use Zend\Db\TableGateway\TableGateway;

 class GenreTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll(Array $columns = array('id','name'))
     {
         $sqlSelect = $this->tableGateway->getSql()->select();
         $sqlSelect->columns($columns);

         $resultSet = $this->tableGateway->selectWith($sqlSelect);
         return $resultSet;
     }

     public function getSelectArrayGenres()
     {
         $genres = $this->fetchAll();
         $return = array();
         foreach($genres as $genre){
             $return["".$genre->id.""] = "".$genre->name."";
         }
         return $return;
     }

     public function getGenre($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveGenre(Genre $genre)
     {
         $data = array(
             'name' => $genre->name,
         );

         $id = (int) $genre->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getGenre($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Genre id does not exist');
             }
         }
     }

     public function deleteGenre($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }