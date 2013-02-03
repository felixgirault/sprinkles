<?php

App::uses( 'Thumbnail', 'Sprinkles.Lib/Thumbnail' );



/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.View.Helper
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ThumbnailHelper extends AppHelper {

	/**
	 *
	 */

	public $helpers = array( 'Html' );



	/**
	 *
	 */

	public function image( $format, $key, $options ) {

		return $this->Html->image( Thumbnail::path( $format, $key ), $options );
	}
}
