<?php

/**
 *
 */

class Sprinkles {

	/**
	 *	Tests if the string $haystack ends with $needle.
	 *
	 *	@param string $haystack 
	 *	@param string $haystack 
	 *	@return boolean true if $haystack ends with $needle, otherwise false.
	 */

	public static function endsWith( $haystack, $needle ) {

		$needleLength = strlen( $needle );

		if ( $needleLength > strlen( $haystack )) {
			return false;
		}

		return ( substr_compare( $haystack, DS, -$needleLength, $needleLength ) === 0 );
	}
}