<?php

App::uses( 'HtmlHelper', 'View/Helper' );



/**
 *	Extends the capabilities of the original HtmlHelper.
 *	Typically meant to be used instead of it, using an alias in your controller:
 *
 *	```
 *		public $helpers = array(
 *			'Html' => array(
 *				'className' => 'Sprinkles.ExtendedHtml'
 *			)
 *		);
 *	```
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.View.Helper
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ExtendedHtmlHelper extends HtmlHelper {

	/**
	 *
	 */

	public function link( $text, $url = null, $options = array( ), $confirmMessage = false ) {

		if ( $url !== null && !isset( $options['title'])) {
			$options['title'] = $text;
		}

		return parent::link( $text, $url, $options, $confirmMessage );
	}



	/**
	 *
	 */

	public function accessibleLink( $text, $title, $url = null, array $options = array( ), $confirmMessage = false ) {

		$options['title'] = $title;

		return $this->link( $text, $url, $options, $confirmMessage );
	}



	/**
	 *
	 */

	public function accessibleImage( $alt, $url, array $options = array( )) {

		$options['alt'] = $alt;

		return $this->image( $url, $options );
	}



	/**
	 *
	 */

	public function title( $level, $text, array $options = array( )) {

		return $this->tag( 'h' . $level, $text, $options );
	}



	/**
	 *	Returns an Html5 `<time>` tag
	 */

	public function time( $date, array $options = array( )) {

		$options = array_merge(
			array(
				'datetime' => CakeTime::format( DATE_W3C, $date ),
				'format' => ''
			),
			$options
		);

		return $this->tag(
			'time',
			CakeTime::format( $options['format'], $date ),
			$options
		);
	}



	/**
	 *
	 */

	public function timeAgo( $date, array $options = array( )) {

		$options = array_merge(
			array(
				'datetime' => CakeTime::format( DATE_W3C, $date ),
				'format' => '',
				'end' => '+1 week'
			),
			$options
		);

		return $this->tag(
			'time',
			CakeTime::timeAgoInWords(
				$date,
				array(
					'format' => $options['format'],
					'end' => $options['format']
				)
			),
			$options
		);
	}



	/**
	 *	Embeds elements markup into javascript variables.
	 *
	 *	@param string|array A single element name, or an array of element names.
	 *	@return string A script tag containing the markup variables.
	 */

	public function markup( $markups, array $options = array( )) {

		if ( !is_array( $markups )) {
			$markups = array( $this->_markupName( $markups ) => $markups );
		}

		$this->scriptStart( $options );

		foreach ( $markups as $var => $element ) {
			printf(
				"var %s = '%s';\n",
				is_string( $var )
					? $var
					: $this->_markupName( $element ),
				$this->_prepareMarkup( $this->_View->element( $element ))
			);
		}

		return $this->scriptEnd( );
	}



	/**
	 *
	 */

	protected function _markupName( $element ) {

		$path = explode( '/', $element );
		$name = array_pop( $path );
		$name = strtoupper( $name ) . '_MARKUP';

		return $name;
	}



	/**
	 *
	 */

	protected function _prepareMarkup( $markup ) {

		$markup = preg_replace( '/[\s\h\v]+/', ' ', $markup );
		$markup = str_replace( '\'', '\\\'', $markup );

		return $markup;
	}



	/**
	 *
	 */

	public function scripts( array $urls, $root = '' ) {

		if ( !empty( $root )) {
			$root .= '/';
		}

		$html = '';

		foreach ( $urls as $folder => $name ) {
			if ( is_numeric( $folder )) {
				$html .= $this->script( $root . $name );
			} else {
				$html .= $this->scripts( $name, $root . $folder );
			}
		}

		return $this->output( $html );
	}



	/**
	 *	Cleans up the default output by adding a carriage return.
	 *
	 *	@see HtmlHelper::output( )
	 */

	public function output( $string ) {

		return parent::output( $string . "\n" );
	}
}
