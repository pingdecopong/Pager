<?php


namespace pingdecopong\PagerBundle\Pager\PagerSelector\Form;

use Symfony\Component\Validator\Constraints as Assert;

class PagerSelectorFormModel {

    /**
     * @Assert\Type(type="numeric")
     * @var int
     */
    private  $pageNo;

    /**
     * @Assert\Type(type="numeric")
     * @var int
     */
    private  $pageSize;

    function __construct()
    {
        $this->pageNo = 1;
        $this->pageSize = 10;
    }

    /**
     * @param int $pageNo
     */
    public function setPageNo($pageNo)
    {
        $this->pageNo = $pageNo;
    }

    /**
     * @return int
     */
    public function getPageNo()
    {
        return $this->pageNo;
    }

    /**
     * @param int $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }
}