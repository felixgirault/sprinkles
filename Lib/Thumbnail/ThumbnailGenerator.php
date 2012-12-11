<?php

/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model.Behavior
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

abstract class ThumbnailGenerator {

	/**
	 *
	 */

	public abstract function generate( array $options, $source, $destination );

}
