<?php

App::uses( 'Sprinkles', 'Sprinkles.Lib' );



/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Lib
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Thumbnail {

	/**
	 *
	 *
	 *	@param string|array $configuration Config name or config array.
	 */

	public static function path( $key, $levels = 3, $extension = 'jpg' ) {

		$hash = md5( $key );
		$levels = Sprinkles::bound( 2, $levels, strlen( $hash ) - 1 );
		$start = substr( $hash, 0, $levels );

		return $format
			. DS . implode( DS, str_split( $start ))
			. DS . substr( $hash, $levels ) . '.' . $extension;
	}
}
