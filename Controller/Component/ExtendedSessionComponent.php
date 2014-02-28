<?php

App::uses( 'SessionComponent', 'Controller/Component' );



/**
 *	Extends the capabilities of the original SessionComponent.
 *	Typically meant to be used instead of it, using an alias in your controller:
 *
 *	```
 *		public $components = array(
 *			'Session' => array(
 *				'className' => 'Sprinkles.ExtendedSession'
 *			)
 *		);
 *	```
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Controller.Component
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ExtendedSessionComponent extends SessionComponent {

	/**
	 *	The Html classes that will be used for flash messages markup.
	 *
	 *	@var array
	 */

	public $classes = [
		'success' => 'success',
		'error' => 'error'
	];



	/**
	 *	A reference to the controller that is using the component.
	 *
	 *	@var Controller
	 */

	protected $_Controller = null;



	/**
	 *	Stores a reference to the controller that is using the component.
	 *
	 *	@param Controller $Controller Controller using this component.
	 */

	public function initialize( Controller $Controller ) {

		$this->_Controller = $Controller;
	}



	/**
	 *	Returns the referer url if it is local, otherwise the home url.
	 *	This method can be used in conjunction with flash( ).
	 *
	 *	@param string $default Default URL to use if HTTP_REFERER cannot be
	 *		read from headers.
	 *	@return string Referring URL.
	 */

	public function localReferer( $default = '/' ) {

		return $this->_Controller->referer( $default, true );
	}



	/**
	 *	Flashes a message of the given type, and redirects to another url
	 *	if any.
	 *	If type is a key of ExtendedSessionComponent::$classes, the
	 *	corresponding value will be set as the message class.
	 *
	 *	@param string $type Type of message to show.
	 *	@param string $message Message to show.
	 *	@param mixed $url Url to redirect to. If false, there will be no
	 *		redirection.
	 */

	public function flash( $type, $message, $url = false ) {

		$params = isset( $this->classes[ $type ])
			? [ 'class' => $this->classes[ $type ]]
			: [ ];

		$this->setFlash( $message, 'flash', $params, $type );

		if ( $url ) {
			$this->_Controller->redirect( $url );
		}
	}
}
