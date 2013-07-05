<?php


namespace pingdecopong\PagerBundle\Pager\PagerColumn;


class PagerColumnRowView {


    private $keyName;
    private $label;
    private $enable;
    private $sortSelected;
    private $sortType;
    private $query;

    /**
     * @param mixed $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $keyName
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
    }

    /**
     * @return mixed
     */
    public function getKeyName()
    {
        return $this->keyName;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $enable
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    }

    /**
     * @return mixed
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param mixed $sortSelected
     */
    public function setSortSelected($sortSelected)
    {
        $this->sortSelected = $sortSelected;
    }

    /**
     * @return mixed
     */
    public function getSortSelected()
    {
        return $this->sortSelected;
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



}