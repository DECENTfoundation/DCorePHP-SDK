<?php


namespace DCorePHP\Model\Content;


class Key
{

    /** @var string */
    private $s;

    /**
     * @return string
     */
    public function getS(): string
    {
        return $this->s;
    }

    /**
     * @param string $s
     * @return Key
     */
    public function setS(string $s): Key
    {
        $this->s = $s;

        return $this;
    }

}