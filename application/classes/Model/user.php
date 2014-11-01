<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author denis
 */
class Model_User extends ORM {
    
    public function rules()
    {
        return array(
            'name' => array(
                array('not_empty'),
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 50)),
                array('regex', array(':value', '/^[-\pL\pN_.]++$/uD')),
            ),
            'surname' => array(
                array('not_empty'),
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 50)),
                array('regex', array(':value', '/^[-\pL\pN_.]++$/uD')),
            ),
            'email' => array(
                array('not_empty'),
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 127)),
                array('email'),
            ),
            'personal_code' => array(
                array('not_empty'),
                array('min_length', array(':value', 32)),
                array('max_length', array(':value', 32)),
                array('regex', array(':value', '/[a-z0-9]+/iuD')),
            ),
            'address' => array(
                array('max_length', array(':value', 100)),
                array('regex', array(':value', '/[\w\-\.\,]+/uD')),
            ),
            'country' => array(
                array('not_empty'),
                array('max_length', array(':value', 30)),
                array('regex', array(':value', '/\S+/uD')),
            ),
            'city' => array(
                array('not_empty'),
                array('max_length', array(':value', 30)),
                array('regex', array(':value', '/\S+/uD')),
            ),
        );
    }
    
}
