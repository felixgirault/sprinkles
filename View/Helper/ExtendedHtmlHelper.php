<?php

App::uses( 'HtmlHelper', 'View/Helper' );



/**
 *	Extends the capabilities of the original HtmlHelper.
 *	Typically meant to be used instead of it, using an alias in your controller :
 *
 *	```
 *		public $helpers = array(
 *			'Html' => array(
 *				'className' => 'Sprinkles.ExtendedHtml'
 *			)
 *		);
 *	```
 *
 *	@package Sprinkles.View.Helper
 *	@author Félix Girault <felix.girault@gmail.com>
 */

class ExtendedHtmlHelper extends HtmlHelper {

	/**
	 *
	 */

	public $helpers = array( 'Time' );



	/**
	 *	
	 */

	protected $_typographicReplacements = array(
		'simple' => array(
			'...' => '…',
			'\'' => '’',
			'--' => '—'
		),
		'advanced' => array(
			'/"([^"]*)"/U' => '«&thinsp;$1&thinsp;»'
		)
	);



	/**
	 *
	 */

	public function escape( $string, array $options = array( )) {

		$options = array_merge(
			array(
				'typography' => true,
				'lineEndings' => true,
				'whitespace' => true
			),
			$options
		)

		extract( $options );

		$string = htmlspecialchars( trim( $string ), ENT_NOQUOTES/*, 'UTF-8' */);

		// typographic corrections

		if ( $typography ) {
			if ( !empty( $this->_typographicReplacements['simple'])) {
				$string = str_replace(
					array_keys( $this->_typographicReplacements['simple']),
					array_values( $this->_typographicReplacements['simple']),
					$string
				);
			}

			if ( !empty( $this->_typographicReplacements['advanced'])) {
				foreach ( $this->_typographicReplacements['advanced'] as $pattern => $replacement ) {
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

	public function accessibleLink( $text, $title, $url = null, $options = array( ), $confirmMessage = false ) {

		$options['title'] = $title;

		return $this->link( $text, $url, $options, $confirmMessage );
	}



	/**
	 *
	 */

	public function accessibleImage( $text, $alt, $options = array( )) {

		$options['alt'] = $alt;

		return $this->image( $text, $options );
	}



	/**
	 *
	 */

	public function title( $level, $text, $htmlAttributes = array( )) {

		return $this->tag( 'h' . $level, $text, $htmlAttributes );
	}



	/**
	 *
	 */

	public function time( $date, $format, $pubdate = true ) {

		$options = array(
			'datetime' => $this->Time->format( DATE_W3C, $date )
		);

		if ( $pubdate ) {
			$options[] = 'pubdate';
		}

		return $this->tag( 'time', $this->Time->format( $format, $date ), $options );
	}



	/**
	 *
	 */

	public function timeAgo( $date, $format, $end = '+1 week', $pubdate = true ) {

		$options = array(
			'datetime' => $this->Time->format( DATE_W3C, $date )
		);

		if ( $pubdate ) {
			$options[] = 'pubdate';
		}

		return $this->tag(
			'time',
			$this->Time->timeAgoInWords(
				$date,
				array(
					'format' => $format,
					'end' => $end
				)
			),
			$options
		);
	}



	/**
	 *
	 */

	public function scripts( array $urls, $root = '' ) {

		$html = '';

		if ( $root !== '' && Sprinkles::endsWith( $root, DS )) {
			$root .= DS;
		}

		foreach ( $urls as $folder => $name ) {
			if ( is_numeric( $folder )) {
				$html .= $this->script( $root . $name );
			} else {
				foreach ( $name as $name ) {
					$html .= $this->scripts( $names, $root . $folder );
				}
			}
		}

		return $html;
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
