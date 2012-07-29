<?php

App::uses( 'AuthComponent', 'Controller/Component' );



/**
 *	Extends the capabilities of the original AuthComponent.
 *	Typically meant to be used instead of it, using an alias in your controller:
 *
 *	```
 *		public $components = array(
 *			'Auth' => array(
 *				'className' => 'Sprinkles.ExtendedAuth'
 *			)
 *		);
 *	```
 *	
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Controller.Component
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ExtendedAuthComponent extends AuthComponent {

	/**
	 *	The database field that stores the user role.
	 *
	 *	@var string
	 */

	public $roleField = 'role';



	/**
	 *	A list of actions and corresponding user roles required to access them.
	 *
	 *	@var array
	 */

	protected $_roleBasedAllowedActions = array( );



	/**
	 *	Handles authorizations based on the logged user role. See 
	 *	ExtendedAuthComponent::allowFor( ).
	 *	
	 *	@param Controller $Controller Controller using this component.
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
	 *	@param string $method Name of the virtual method.
	 *	@param array $arguments Arguments to pass to the actual method (never used here).
	 *	@return mixed Return of the method if it can be parsed, otherwise null.
	 */

	public function __call( $method, array $arguments = array( )) {

		if ( strpos( $method, 'user' ) === 0 ) {
			$key = substr( $method, strlen( 'user' ));
			$key = Inflector::tableize( $key );

			return $this->user( $key );
		}

		if ( strpos( $method, 'userIs' ) === 0 ) {
			$role = substr( $method, strlen( 'userIs' ));
			$role = Inflector::tableize( $role );

			return $this->userIs( $role );
		}

		return null;
	}



	/**
	 *	Returns if the logged user has the given role(s).
	 *	This method requires that the user model has a field named 'role',
	 *	containing the user role.
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
	 *	@param array $actions A list of actions and corresponding user roles
	 *		required to access them.
	 */

	public function allowFor( array $actions = array( )) {

		$this->_roleBasedAllowedActions = $actions;
	}
}
