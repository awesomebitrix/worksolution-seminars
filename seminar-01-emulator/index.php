<?php

class ArrayEmulator implements ArrayAccess, Countable {

    private $data = array();

    public function __construct(array $data){
        $this->data = $data;
    }
    public function __get($name) {
        return $this->offsetGet($name);
    }

    public function __set($name, $value) {
        return $this->offsetSet($name, $value);
    }

    public function count() {
        return count($this->data);
    }

    public function offsetGet($offset) {
        if (!$this->offsetExists($offset)){
            return null;
        }
        if (is_array($this->data[$offset])){
            return new self($this->data[$offset]);
        }
        return $this->data[$offset];
    }

    public function offsetExists($offset) {
        return array_key_exists($offset,$this->data);
    }

    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
        return $value;
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function __toString() {
        return var_export($this->data, true);
    }
}

$array = new ArrayEmulator(array(
    'data' => array(
        'name' => 'Name',
        'width' => 10,
        'height' => 15
    ),
    'messages' => array(
        'error' => 'Object has error',
        'notify' => 'Object'
    ),
    'report' => null
));

$array['data'] instanceof ArrayEmulator or die('object not instance of base class');
$array['data']['name'] == 'Name' or die('Name is not valid');
$array['data']['width'] == 10 or die('Width is not valid');

$data = $array['data'];
$data['height'] == 15 or die('Width is not valid');

isset($array['report']) == true or die('Report is not exists');
$array['report'] === null or die('Report doesn`t have valid type');

count($array) == 3 or die('Count not valid');
$array['data'] = 'string';
$array['data'] == 'string' or die('Data is not changed');
echo $array;
echo "Done";