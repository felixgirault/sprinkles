<?php

App::uses( 'SessionComponent', 'Controller/Component' );



/**
 *	Extends the capabilities of the original SessionComponent.
 *	Typically meant to be used instead of it, using an alias in your controller :
 *
 *	```
 *		public $components = array(
 *			'Session' => array(
 *				'className' => 'Sprinkles.ExtendedSession'
 *			)
 *		);
 *	```
 *
 *	@package Sprinkles.Controller.Component
 *	@author Félix Girault <felix.girault@gmail.com>
 */

class ExtendedSessionComponent extends SessionComponent {

	/**
	 *	The Html classes that will be used for flash messages markup.
	 */

	public $classes = array(
		'success' => 'success',
		'error' => 'error'
	);



	/**
	 *	A reference to the controller that is using the component.
	 */

	protected $_Controller = null;



	/**
	 *	Stores a reference to the controller that is using the component.
	 *
	 *	@see Component::initialize( ).
	 */

	public function initialize( &$Controller ) {

		parent::initialize( $Controller );

		$this->_Controller =& $Controller;
	}



	/**
	 *	Returns the referer url if it is local, otherwise the home url.
	 *
	 *	@return string url.
	 */

	public function localReferer( ) {

		return $this->_Controller->referer( '/', true );
	}



	/**
	 *	Flashes a message of the given type, and redirects to another url
	 *	if any.
	 *
	 *	@param string $type type of message to show
	 *	@param string $message message to show.
	 *	@param string $class class to use when rendering.
	 *	@param mixed $url url to redirect to.
	 *		If false, there will be no redirection.
	 */

	public function flash( $type, $message, $url = false ) {

		$params = isset( $this->classes[ $type ])
			? array( 'class' => $this->classes[ $type ])
			: array( );

		$this->setFlash( $message, 'flash', $params, $type );

		if ( $url ) {
			$this->_Controller->redirect( $url );
		}
	}
}
