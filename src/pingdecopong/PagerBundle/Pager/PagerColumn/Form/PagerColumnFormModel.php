<?php


namespace pingdecopong\PagerBundle\Pager\PagerColumn\Form;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * @Assert\Callback(methods={"isValidSortNameSortType"})
 */
class PagerColumnFormModel {

    private $sortName;
    private $sortType;

    private $sortNameList;

    function __construct()
    {
        $this->sortNameList = array();
        $this->sortNameList[] = null;
    }

    /**
     * @param mixed $sortName
     */
    public function setSortName($sortName)
    {
        $this->sortName = $sortName;
    }

    /**
     * @return mixed
     */
    public function getSortName()
    {
        return $this->sortName;
    }

    /**
     * @param mixed $sortType
     */
    public function setSortType($sortType)
    {
        $this->sortType = $sortType;
    }

    /**
     * @return mixed
     */
    public function getSortType()
    {
        return $this->sortType;
    }

    public function addSortNameList($sortNameList)
    {
        $this->sortNameList[] = $sortNameList;
    }

    public function getSortNameList()
    {
        return $this->sortNameList;
    }

    public function isValidSortNameSortType(ExecutionContextInterface $context)
    {
        if(!in_array($this->sortName, $this->sortNameList))
        {
            $context->addViolationAt('sortName', 'sort name error.', array(), null);
        }
        if(!in_array($this->sortType, array(null, 'asc', 'desc')))
        {
            $context->addViolationAt('sortType', 'sort type error.', array(), null);
        }
    }

}