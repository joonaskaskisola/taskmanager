<?php

namespace AppBundle\Entity;

abstract class AbstractEntity
{
    public $skipFill = [
        'id',
        'createdAt'
    ];

    /**
     * @param array $data
     * @return $this
     */
    public function fill(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (!is_array($value) && !in_array($key, $this->skipFill)) {
                if (method_exists($this, 'set' . ucfirst($key))) {
                    $this->{'set' . ucfirst($key)}($value);
                } else {
                    throw new \InvalidArgumentException("Invalid key " . $key);
                }
            }
        }

        return $this;
    }
}
