<?php

 namespace Album\Model;

 use Zend\Db\TableGateway\TableGateway;

 class AlbumTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll(Array $columns = array('id','title', 'artist'))
     {
         //$resultSet = $this->tableGateway->select();
         $sqlSelect = $this->tableGateway->getSql()->select();
         $sqlSelect->columns($columns);
         $sqlSelect->join('genres', 'album.genre_id = genres.id', array('genre'=>'name'), 'left');

         $resultSet = $this->tableGateway->selectWith($sqlSelect);
         return $resultSet;
     }


     public function getAlbum($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveAlbum(Album $album)
     {
         $data = array(
             'artist' => $album->artist,
             'title'  => $album->title,
             'genre_id'  => $album->genre_id,
         );

         $id = (int) $album->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getAlbum($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Album id does not exist');
             }
         }
     }

     public function deleteAlbum($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }