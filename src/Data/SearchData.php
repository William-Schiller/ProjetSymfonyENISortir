<?php


namespace App\Data;



use App\Repository\CampusRepository;

class SearchData
{

    /**
     * @var string
     */
    public $search = '';

    /**
     * @var CampusRepository[]
     */
    public $campus = [];

    /**
     * @var null/date
     */
    public $dateMax;

    /**
     * @var null/date
     */
    public $dateMin;

    /**
     * @var boolean
     */
    public $isOrganizer = false;

    /**
     * @var boolean
     */
    public $subscribedTo = false;

    /**
     * @var boolean
     */
    public $insubscribedTo = false;

}


