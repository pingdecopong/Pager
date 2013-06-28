<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fhirashima
 * Date: 13/06/28
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
 */

namespace pingdecopong\PagerBundle\Pager\PagerSelector;


class PagerSelectorView {


    private $pageNo;

    private $pageSize;

    public function setPageNo($pageNo)
    {
        $this->pageNo = $pageNo;
    }

    public function getPageNo()
    {
        return $this->pageNo;
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

}