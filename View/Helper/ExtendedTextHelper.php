<?php

App::uses( 'TextHelper', 'View/Helper' );



/**
 *	Extends the capabilities of the original TextHelper.
 *	Typically meant to be used instead of it, using an alias in your controller:
 *
 *	```
 *		public $helpers = array(
 *			'Text' => array(
 *				'className' => 'Sprinkles.ExtendedText'
 *			)
 *		);
 *	```
 *
 *	@package Sprinkles.View.Helper
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ExtendedTextHelper extends TextHelper {

	/**
	 *	Default options for prepare( ).
	 *
	 *	@var array
	 *	@see ExtendedTextHelper::prepare( )
	 */

	public $prepare = array(
		'flags' => ( ENT_COMPAT | ENT_HTML401 ),
		'escape' => true,
		'typography' => true,
		'lineEndings' => true,
		'whitespace' => true,
		'simpleReplacements' => array(
			'...' => '…',	// ellipsis
			'\'' => '’',	// apostrophe
			'--' => '—'	// em-dash
		),
		'advancedReplacements' => array(
			'/"([^"]*)"/U' => '«&thinsp;$1&thinsp;»'	// quotation marks
		)
	);



	/**
	 *	Prepares a string for output. Depending on the given $options, it can:
	 *	- escape the string
	 *	- apply typographic corrections
	 *	- convert regular line endings to html ones
	 *	- clean whitespace
	 *
	 *	### Options
	 *
	 *	- 'flags' - Flags to be used by htmlspecialchars( ), see
	 *		http://php.net/manual/fr/function.htmlspecialchars.php.
	 *	- 'typography' - Wether or not to do some typographic corrections.
	 *		See the 'simpleReplacements' and 'advancedReplacements'
	 *		options below.
	 *	- 'lineEndings' - Whether or not to replace line endings by a `<br />` 
	 *		html tag.
	 *	- 'whitespace' - Whether or not to clean whitespace. This means
	 *		replacing multiple spaces with a unique space.
	 *	- 'simpleReplacements' - An array of simple typographic replacements
	 *		to be done. Keys will be replaced by their values.
	 *	- 'advancedReplacements' - An array of regex based typographic to be
	 *		done. Keys are the pattern, values the replacements.
	 *
	 *	@param string $string The string to escape.
	 *	@param array $options An array of processing options.
	 *	@return string The escaped string.
	 */

	public function prepare( $string, array $options = array( )) {

		extract( array_merge( $this->prepare, $options ));

		// escaping

		if ( $escape ) {
			$string = htmlspecialchars( trim( $string ), $flags );
		}

		// typographic corrections

		if ( $typography ) {
			if ( !empty( $simpleReplacements )) {
				$string = str_replace(
					array_keys( $simpleReplacements ),
					array_values( $simpleReplacements ),
					$string
				);
			}

			if ( !empty( $advancedReplacements )) {
				foreach ( $advancedReplacements as $pattern => $replacement ) {
					$string = preg_replace( $pattern, $replacement, $string );
				}
			}
		}

		// line endings conversion

		if ( $lineEndings ) {
			$string = nl2br( $string );
		}

		// whitespace cleaning

		if ( $whitespace ) {
			$string = preg_replace( '/\s\s+/U', ' ', $string );
		}

		return $string;
	}
}
