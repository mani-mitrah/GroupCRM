<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service {
    public $id;
    public $name;
    public $desc;

    /**
     * Get the value of id
     */ 
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * Get the value of name
     */ 
    public function get_name()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function set_name($name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of desc
     */ 
    public function get_desc()
    {
        return $this->desc;
    }

    /**
     * Set the value of desc
     *
     * @return  self
     */ 
    public function set_desc($desc)
    {
        $this->desc = $desc;
    }
}