<?php

App::uses( 'AuthComponent', 'Controller/Component' );



/**
 *	Extends the capabilities of the original AuthComponent.
 *	Typically meant to be used instead of it, using an alias in your controller :
 *
 *	```
 *		public $components = array(
 *			'Auth' => array(
 *				'className' => 'Sprinkles.ExtendedAuth'
 *			)
 *		);
 *	```
 *	
 *	@package Sprinkles.Controller.Component
 *	@author Félix Girault <felix.girault@gmail.com>
 */

class ExtendedAuthComponent extends AuthComponent {

	/**
	 *	The database field that stores the user role.
	 */

	public $roleField = 'role';



	/**
	 *	A list of actions and corresponding user roles required to access them.
	 */

	protected $_roleBasedAllowedActions = array( );



	/**
	 *	@param Controller $Controller
	 *	@return void
	 */

	public function startup( Controller $Controller ) {

		parent::startup( $Controller );

		$action = $this->request->params['action'];

		if ( isset( $this->_roleBasedAllowedActions[ $action ])) {
			if ( !$this->userIs( $this->_roleBasedAllowedActions[ $action ])) {
				$this->deny( $action );
			}
		}
	}



	/**
	 *	Provides shortcuts to the user( ) and userIs( ) methods.
	 *	For example, instead of userIs( 'admin' ), you could write userIsAdmin( ).
	 *
	 *	@param string $method name of the virtual method
	 *	@param array $arguments arguments to pass to the actual method (never used here)
	 *	@return mixed return of the method if it can be parsed, otherwise null
	 */

	public function __call( $method, array $arguments = array( )) {

		if ( strpos( $method, 'user' ) === 0 ) {
			$key = substr( $method, strlen( 'user' ));
			$key = lcfirst( $key );

			return $this->user( $key );
		}

		if ( strpos( $method, 'userIs' ) === 0 ) {
			$role = substr( $method, strlen( 'userIs' ));
			$role = lcfirst( $role );

			return $this->userIs( $role );
		}

		return null;
	}



	/**
	 *	Returns if the logged user has the given role(s).
	 *	This method requires that the user model has a field named 'role',
	 *	containing the user role.
	 *
	 *	@param mixed $role either a string or an array of roles to test the user against
	 *	@return boolean true if the user has one of the given roles, otherwise false
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



	/**
	 *	Allows a list of action to some users, depending on their role.
	 *	
	 *	```
	 *		// users who have got the 'writer' role can access the add action
	 *		$this->Auth->allowFor( array( 'add' => 'writer' ));
	 *
	 *		// users who have got the 'writer' or 'admin' role can access the
	 *		// delete action
	 *		$this->Auth->allowFor( array( 'delete' => array( 'writer', 'admin' )));
	 *	```
	 *
	 *	@param array $actions a list of actions and corresponding user roles
	 *		required to access them.
	 *	@return void
	 */

	public function allowFor( array $actions = array( )) {

		$this->_roleBasedAllowedActions = $actions;
	}
}
