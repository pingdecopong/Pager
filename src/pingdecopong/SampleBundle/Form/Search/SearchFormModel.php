<?php
namespace pingdecopong\SampleBundle\Form\Search;

use Symfony\Component\Validator\Constraints as Assert;

class SearchFormModel {

    /**
     * @Assert\Length(
     *      min = "2",
     *      max = "5",
     *      minMessage = "{{ limit }}文字以上で入力して下さい。",
     *      maxMessage = "{{ limit }}文字以下で入力して下さい。"
     * )
     */
    private $name;

    private $kana;

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $kana
     */
    public function setKana($kana)
    {
        $this->kana = $kana;
    }

    /**
     * @return mixed
     */
    public function getKana()
    {
        return $this->kana;
    }

}