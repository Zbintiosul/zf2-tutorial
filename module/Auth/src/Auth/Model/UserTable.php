<?php

 namespace Auth\Model;

 use Zend\Db\TableGateway\TableGateway;

 class UserTable
 {
     protected $tableGateway;
     protected $_name = 'users';
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
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
         $now = new \DateTime();
         if (empty($data['updated_at'])){
             $data['updated_at'] = $now->format('Y-m-d H:i:s');
         }

         $id = (int) $user->id;
         if ($id == 0) {

             $this->tableGateway->insert($data);
         } else {
             if ($this->getUser($id)) {
                 $data['created_at'] = $now->format('Y-m-d H:i:s');
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

 }