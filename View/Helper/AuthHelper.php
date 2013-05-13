<?php

/**
 *
 */

class AuthHelper extends AppHelper {

	/**
	 *
	 */

	public $roleField = 'role';



	/**
	 *
	 */

	public function loggedIn( ) {

		return ( boolean ) $this->user( 'id' );
	}



	/**
	 *
	 */

	public function user( $property ) {

		return CakeSession::read( "Auth.User.$property" );
	}



	/**
	 *	Returns if the logged user has the given role(s).
	 *
	 *	@param mixed $role Either a string or an array of roles to test the user against.
	 *	@return boolean True if the user has one of the given roles, otherwise false.
	 */

	public function userIs( $role ) {

		if ( !$this->loggedIn( )) {
			return false;
		}

		if ( !is_array( $role )) {
			$role = array( $role );
		}

		return in_array( $this->user( $this->roleField ), $role );
	}
}
