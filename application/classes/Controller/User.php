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
            $this->auto_render = false;
            $headers = sprintf( 'Content-type','application/json; charset=%s', Kohana::$charset );
            $body = json_encode( array( 'success' => true, 'user' => [], 'errors' => [ 'Available only via ajax requests.' ] ) );
            if( $this->request->is_ajax() ){
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
                $errors = [];
                try{
                    if( $user->changed() && $user->check() ){
                        $user->saveMemberData();
                    }
                } catch ( ORM_Validation_Exception $e ){
                    $errors = array_values( $e->errors( 'member' ) );
                } catch ( Database_Exception $e ){
                    $errors[] = $e->getMessage();
                }
                
                $body = json_encode( array( 'success' => empty( $errors ), 'user' => $user->as_array(), 'errors' => $errors ) );
            }
            
            $this->response->headers( $headers );
            $this->response->body( $body );
        }
        
        public function action_get()
	{
            $this->auto_render = false;
            $headers = sprintf( 'Content-type','application/json; charset=%s', Kohana::$charset );
            $body = json_encode( array( 'success' => false, 'user' => [], "error" => 'Available only via ajax requests.' ) );
            if( $this->request->is_ajax() ){
                $user_id = ( int ) $this->request->query( 'id' );
                $user = ORM::factory( 'Member', $user_id );
                $success = $user->loaded();
                $error = '';
                if( !$user->loaded() ){
                    $error = 'User does not exist';
                }
                
                $body = json_encode( array( 'success' => $success, 'user' => $user->as_array(), 'error' => $error ) );
            }
            $this->response->headers( $headers );
            $this->response->body( $body );
	}
        
        public function action_delete()
	{
            $this->auto_render = false;
            $headers = sprintf( 'Content-type','application/json; charset=%s', Kohana::$charset );
            $body = json_encode( array( 'success' => false, 'user_id' => 0, "error" => 'Available only via ajax requests.' ) );
            if( $this->request->is_ajax() ){
                $user_id = ( int ) $this->request->query( 'id' );
                $user = ORM::factory( 'Member', $user_id );
                $success = $user->loaded();
                $error = "";
                if( !$user->loaded() ){
                    $error = "User does not exist.";
                }
                $user->deleteUserData();
                
                $body = json_encode( array( 'success' => $success, 'user_id' => $user_id, "error" => $error ) );
            }
            
            $this->response->headers( $headers );
            $this->response->body( $body );
	}

} // End User
