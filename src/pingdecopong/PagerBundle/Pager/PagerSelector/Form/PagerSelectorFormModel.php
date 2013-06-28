<?php


namespace pingdecopong\PagerBundle\Pager\PagerSelector\Form;


class PagerSelectorFormModel {
    private  $pageNo;
    private  $pageSize;

    /**
     * @param mixed $pageNo
     */
    public function setPageNo($pageNo)
    {
        $this->pageNo = $pageNo;
    }

    /**
     * @return mixed
     */
    public function getPageNo()
    {
        return $this->pageNo;
    }

    /**
     * @param mixed $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return mixed
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }
}