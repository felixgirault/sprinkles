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
 *	@package Sprinkles.View.Helper
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ExtendedHtmlHelper extends HtmlHelper {

	/**
	 *	
	 */

	public function link( $text, $url = null, array $options = array( ), $confirmMessage = false ) {

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

	public function accessibleImage( $text, $alt, array $options = array( )) {

		$options['alt'] = $alt;

		return $this->image( $text, $options );
	}



	/**
	 *
	 */

	public function title( $level, $text, array $htmlAttributes = array( )) {

		return $this->tag( 'h' . $level, $text, $htmlAttributes );
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
	 *
	 */

	public function scripts( array $urls, $root = '' ) {

		$html = '';

		if ( $root !== '' && Sprinkles::endsWith( $root, '/' )) {
			$root .= '/';
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
