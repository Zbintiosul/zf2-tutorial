<?php
/**
 * Created by PhpStorm.
 * User: mracu
 * Date: 8/22/14
 * Time: 6:11 PM
 */
namespace Album\Entity;

use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Album {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $artist;

    /** @ORM\Column(type="string") */
    protected $title;

    public function  getId(){
        return $this->id;
    }
    // getters/setters
    public function  getArtist(){
        return $this->artist;
    }

    public function  getTitle(){
        return $this->title;
    }


    public function  setArtist($artist){
        $this->artist = $artist;
    }

    public function  setTitle($title){
        $this->title = $title;
    }

}