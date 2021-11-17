<?php
namespace App\Data;

use App\Entity\Site;

class SearchData
{

   

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Site
     */
    public $sites = null;

   

    /**
     * @var boolean
     */
    public $organisateur = false;


    /**
     * @var boolean
     */
    public $participant = false;


    /**
     * @var boolean
     */
    public $nonparticipant = false;



     /**
     * @var boolean
     */
    public $passee = false;


     /**
     * @var DateTime
     */
    public $debut = null;


    /**
     * @var DateTime
     */
    public $fin = null;

}