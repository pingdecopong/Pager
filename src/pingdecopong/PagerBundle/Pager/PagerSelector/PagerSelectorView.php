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

    /**
     * @var \pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelectorNoView
     */
    private $pageNo;

    private $pageSize;

    /**
     * @param \pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelectorNoView $pageNo
     */
    public function setPageNo($pageNo)
    {
        $this->pageNo = $pageNo;
    }

    /**
     * @return \pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelectorNoView
     */
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