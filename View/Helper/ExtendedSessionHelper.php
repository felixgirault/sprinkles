<?php

App::uses( 'SessionHelper', 'View/Helper' );



/**
 *	Extends the capabilities of the original SessionHelper.
 *	Typically meant to be used instead of it, using an alias in your controller:
 *
 *	```
 *		public $helpers = array(
 *			'Session' => array(
 *				'className' => 'Sprinkles.ExtendedSession'
 *			)
 *		);
 *	```
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.View.Helper
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ExtendedSessionHelper extends SessionHelper {

	/**
	 *	Returns if there is pending flash messages in session.
	 *
	 *	@return boolean True if there is pending messages, false otherwise.
	 */

	public function hasFlash( ) {

		return ( bool )$this->read( 'Message' );
	}



	/**
	 *	Returns all pending flash messages from the session.
	 *
	 *	@return string Messages.
	 */

	public function flashAll( ) {
		
		$messages = CakeSession::read( 'Message' );
		$html = '';

		foreach ( $messages as $type => $flash ) {
			$vars = $flash['params'];
			$vars['message'] = $flash['message'];

			$html .= $this->_View->element( $flash['element'], $vars );

			CakeSession::delete( "Message.$type" );
		}

		return $html;
	}
}
