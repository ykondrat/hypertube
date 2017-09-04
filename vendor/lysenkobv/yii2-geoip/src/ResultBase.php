<?php


namespace lysenkobv\GeoIP;


use Exception;

class ResultBase {
    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $attributes = [];

    public function __construct($data) {
        $this->data = $data;
    }

    public function __get($name) {
        $getter = 'get' . ucfirst($name);

        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        } elseif (method_exists($this, $getter)) {
            $value = $this->$getter($this->data);
            $this->$name = $value;
            return $value;
        }

        throw new Exception("Unknown property");
    }

    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }
}
