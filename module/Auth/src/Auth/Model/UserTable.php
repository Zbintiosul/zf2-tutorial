<?php

 namespace Auth\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\I18n\Validator\DateTime;

 class UserTable
 {
     protected $tableGateway;
     protected $_name = 'users';
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         $this->table = 'users';
     }

     public function fetchAll(Array $columns = array('id','username', 'firstname', 'lastname'))
     {
         $sqlSelect = $this->tableGateway->select();

         return $sqlSelect;
     }


     public function getUser($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function getUserByUsername($username)
     {
         $rowset = $this->tableGateway->select(array('username' => $username));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $username");
         }
         return $row;
     }

     public function saveUser(User $user,Array $data)
     {
         if (empty($data['updated_at'])){
             $now = new \DateTime();
             $data['updated_at'] = $now->format('Y-m-d H:i:s');
         }

         $id = (int) $user->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getUser($id)) {

                 $this->tableGateway->update($data, array('id' => $id));

             } else {
                 throw new \Exception('User id does not exist');
             }
         }
     }

     public function saveMyProfile(User $user)
     {
         $data = array(
             'email' => $user->email,
             'firstname'  => $user->firstname,
             'lastname'  => $user->lastname,
         );

         if (!empty($user->id))
         {
             return $this->saveUser($user,$data);
         }else {
             throw new \Exception('User id is required');
         }
     }

     public function deleteUser($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }