<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fhirashima
 * Date: 13/07/04
 * Time: 10:55
 * To change this template use File | Settings | File Templates.
 */

namespace pingdecopong\SampleBundle\Form\Search;


class TestModel {
    private  $pageNo;

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

}