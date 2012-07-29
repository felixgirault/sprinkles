<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Lib
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Sprinkles {

	/**
	 *	Tests if the string $haystack ends with $needle.
	 *
	 *	@param string $haystack String to test against.
	 *	@param string $needle Supposed end of the string.
	 *	@return boolean True if $haystack ends with $needle, otherwise false.
	 */

	public static function endsWith( $haystack, $needle ) {

		$needleLength = strlen( $needle );

		if ( $needleLength > strlen( $haystack )) {
			return false;
		}

		return ( substr_compare( $haystack, DS, -$needleLength, $needleLength ) === 0 );
	}
}