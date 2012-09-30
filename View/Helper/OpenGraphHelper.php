<?php

/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.View.Helper
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class OpenGraphHelper extends AppHelper {

	/**
	 *
	 *
	 *	@var array
	 */

	public $helpers = array( 'Html' );



	/**
	 *
	 *
	 *	@param string|array $one
	 *	@param string $two
	 */

	public function meta( $one, $two = null ) {

		if ( is_array( $one )) {
			if ( is_array( $two )) {
				$data = array_combine( $one, $two );
			} else {
				$data = $one;
			}
		} else {
			$data = array( $one => $two );
		}

		$html = '';

		foreach ( $data as $property => $content ) {
			$html .= $this->Html->tag(
				'meta',
				null,
				array(
					'property' => 'og:' . $property,
					'content' => $content,
					'escape' => false
				)
			);
		}

		return $html;
	}
}
