<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Template {

        public $template = 'user/list';
        
	public function action_index()
	{
            $users = ORM::factory( 'Member' )->order_by( 'id', 'asc' )->find_all();
            View::set_global( "users", $users );
	}
        
        public function action_save()
        {
            if( $this->request->is_ajax() ){
                $this->auto_render = false;
                $user_data = $this->request->query( 'user' );
                $user_id = !empty( $user_data[ 'id' ] ) ? ( int ) $user_data[ 'id' ] : NULL;
                unset( $user_data[ 'id' ] );
                
                $user = ORM::factory( 'Member', $user_id );
                foreach ( $user_data as $field_name => $field_value ){
                    if( in_array( $field_name, array_keys( $user->list_columns() ) ) ){
                        $user->set( $field_name, $field_value );
                    }
                }
                
                $user->recalculatePersonalCode();
                if( $user->changed() ){
                    $user->saveMemberData();
                }
                
                $this->response->headers( sprintf( 'Content-type','application/json; charset=%s', Kohana::$charset ) );
                $this->response->body( json_encode( array( 'success' => true, 'user' => $user->as_array(), 
                    'errors' => [] ) ) );
            }
        }
        
        public function action_get()
	{
            if( $this->request->is_ajax() ){
                $this->auto_render = false;
                $user_id = ( int ) $this->request->query( 'id' );
                $user = ORM::factory( 'Member', $user_id );
                $success = true;
                $error = '';
                if( empty( $user ) ){
                    $success = false;
                    $error = 'User does not exist';
                }
                $this->response->headers( sprintf( 'Content-type','application/json; charset=%s', Kohana::$charset ) );
                $this->response->body( json_encode( array( 'success' => $success, 'user' => $user->as_array(), 
                    'error' => $error ) ) );
            }
	}
        
        public function action_delete()
	{
            if( $this->request->is_ajax() ){
                $this->auto_render = false;
                $user_id = ( int ) $this->request->query( 'id' );
                $user = ORM::factory( 'Member', $user_id );
                $user->deleteUserData();
                $success = true;
                $error = "";
                if( empty( $user_id ) || empty( $user->id ) ){
                    $success = false;
                    $error = "User does not exist.";
                }
                $this->response->headers( sprintf( 'Content-type','application/json; charset=%s', Kohana::$charset ) );
                $this->response->body( json_encode( array( 'success' => $success, 'user_id' => $user_id, "error" => $error ) ) );
            }
	}

} // End User
