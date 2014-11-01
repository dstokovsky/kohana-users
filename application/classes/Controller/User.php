<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Template {

        public $template = 'user/list';
        
	public function action_index()
	{
            $users = ORM::factory('User')->find_all();
            View::set_global( "users", $users );
	}
        
        public function action_edit()
        {
            $this->template = 'user/edit';
            parent::before();
            $user_id = ( int ) $this->request->param( 'id' );
            $user = ORM::factory( 'User', $user_id );
            View::set_global( "user", $user );
        }

} // End User
