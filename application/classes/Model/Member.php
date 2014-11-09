<?php

/**
 * Description of member
 *
 * @author denis
 */
class Model_Member extends ORM {

    /**
     * Overriden with name of database view created to list the content of 
     * current entity.
     *
     * @var string
     */
    protected $_table_name = "v_members";
    
    /**
     * Contains validation rules for current database entity.
     * 
     * @param void
     * @return array
     */
    public function rules()
    {
        return array(
            'name' => array(
                array('not_empty'),
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 50)),
                array('alpha_dash'),
            ),
            'surname' => array(
                array('not_empty'),
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 50)),
                array('alpha_dash'),
            ),
            'email' => array(
                array('not_empty'),
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 100)),
                array('email'),
                array(array($this, 'isUniqueEmail')),
            ),
            'personal_code' => array(
                array('not_empty'),
                array('exact_length', array(':value', 32)),
                array('alpha_numeric'),
            ),
            'address' => array(
                array('max_length', array(':value', 100)),
                array('regex', array(':value', '/[\w\-\.\,]+/uD')),
            ),
            'country' => array(
                array('not_empty'),
                array('max_length', array(':value', 30)),
                array('alpha'),
            ),
            'city' => array(
                array('not_empty'),
                array('max_length', array(':value', 30)),
                array('alpha'),
            ),
        );
    }
    
    /**
     * Checks if such email is already exist in database or not.
     * 
     * @param string $email
     * @return boolean
     */
    public function isUniqueEmail( $email )
    {
        return ! ( bool ) DB::select( array(DB::expr('COUNT(id)'), 'total') )
            ->from( $this->_table_name )
            ->where( 'email', '=', $email )
            ->where( $this->_primary_key, '!=', $this->pk() )
            ->execute()
            ->get( 'total' );
    }

    /**
     * Generates and saves into corresponding attribute user's personal code.
     * 
     * @param void
     * @return void
     */
    public function recalculatePersonalCode()
    {
        $personal_code = md5( implode( "-", array( $this->name, $this->surname, 
            $this->email, $this->address, $this->country, $this->city ) ) );
        $this->set( "personal_code", $personal_code );
    }
    
    /**
     * Saves current member's data into database using corresponding stored procedure.
     * 
     * @param void
     * @return void
     */
    public function saveMemberData()
    {
        $query = DB::query( Database::SELECT, 'SELECT save_user(:id,:name,:surname,:email,:pcode,:address,:country,:city)');
        $query->parameters(array(
            ":id" => ( int ) $this->id,
            ":name" => $this->name,
            ":surname" => $this->surname,
            ":email" => $this->email,
            ":pcode" => $this->personal_code,
            ":address" => $this->address,
            ":country" => $this->country,
            ":city" => $this->city
        ));
        
        $user_id = ( int ) $query->execute()->get( "save_user" );
        
        if( !empty( $user_id ) ){
            $this->set( 'id', $user_id );
        }
    }
    
    /**
     * Deletes current user's record from database using corresponding stored procedure.
     * 
     * @param void
     * @return void
     */
    public function deleteUserData()
    {
        $query = DB::query( Database::SELECT, 'SELECT delete_user(:id)');
        $query->parameters( array( ":id" => ( int ) $this->id ) );
        $query->execute();
    }
}
