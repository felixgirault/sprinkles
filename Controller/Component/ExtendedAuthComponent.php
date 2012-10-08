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
	 *
	 */

	protected $_roles = array( );



	/**
	 *	A list of actions and corresponding user roles required to access them.
	 *
	 *	@var array
	 */

	protected $_roleBasedAllowedActions = array( );



	/**
	 *	Constructor.
	 *
	 *	@param ComponentCollection $Collection A ComponentCollection this
	 *		component can use to lazy load its components.
	 *	@param array $settings Array of configuration settings.
	 */

	public function __construct( ComponentCollection $Collection, $settings = array( )) {

		parent::__construct( $Collection, $settings );

		if ( isset( $settings['roles']) && is_array( $settings['roles'])) {
			$this->_flatten( $settings['roles']);
		}
	}



	/**
	 *
	 */

	protected function _flatten( $roles ) {

		$flattened = array( );

		foreach ( $roles as $role => $contained ) {
			if ( is_array( $contained )) {
				$flattened = array_merge(
					$flattened,
					$this->_flatten( $contained )
				);
			} else {
				$role = $contained;
			}

			$flattened[ ] = $role;

			if ( isset( $this->_roles[ $role ])) {
				$this->_roles[ $role ] = array_merge(
					$this->_roles[ $role ],
					$flattened
				);
			} else {
				$this->_roles[ $role ] = $flattened;
			}
		}

		return $flattened;
	}



	/**
	 *	Handles authorizations based on the logged user role.
	 *
	 *	@param Controller $Controller Controller using this component.
	 */

	public function startup( Controller $Controller ) {

		$action = strtolower( $this->request->params['action']);

		if ( isset( $this->_roleBasedAllowedActions[ $action ])
			&& $this->userIs( $this->_roleBasedAllowedActions[ $action ])
		) {
			return true;
		}

		return parent::startup( $Controller );
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
	 *
	 */

	public function allow( $actions = null ) {

		$args = func_get_args( );

		if ( empty( $args ) || $actions === null ) {
			$this->allowedActions = $this->_methods;
		} else {
			$allowed = array( );

			if ( isset( $args[ 0 ]) && is_array( $args[ 0 ])) {
				foreach ( $args[ 0 ] as $action => $roles ) {
					if ( is_numeric( $action )) {
						$allowed[ ] = $roles;
					} else {
						$this->_roleBasedAllowedActions[ $action ] = $roles;
					}
				}
			} else {
				$allowed = $args;
			}

			$this->allowedActions = array_merge( $this->allowedActions, $allowed );
		}
	}



	/**
	 *	Returns if the logged user has the given role(s).
	 *
	 *	@param mixed $role Either a string or an array of roles to test the user against.
	 *	@return boolean True if the user has one of the given roles, otherwise false.
	 */

	public function userIs( $roles ) {

		if ( !$this->loggedIn( )) {
			return false;
		}

		if ( !is_array( $roles )) {
			$roles = array( $roles );
		}

		$userRole = $this->user( $this->roleField );

		foreach ( $roles as $role ) {
			if (
				( $role == $userRole )
				|| (
					isset( $this->_roles[ $role ])
					&& in_array( $userRole, $this->_roles[ $role ])
				)
			) {
				return true;
			}
		}

		return false;
	}
}
