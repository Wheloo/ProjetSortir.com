<?php

namespace App\Data;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Component\Validator\Constraints\Date;

class SearchData{

    /**
     * @var Campus
     */
    public $campus;

    /**
     * @var null|string
     */
    public $search = '';

    /**
     * @var null|date
     */
    public $dateDebut;

    /**
     * @var null|date
     */
    public $dateFin;

    /**
     * @var boolean
     */
    public $organisateur;

    /**
     * @var boolean
     */
    public $inscrit;

    /**
     * @var boolean
     */
    public $non_inscrit;

    /**
     * @var boolean
     */
    public $passees;

}