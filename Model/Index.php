<?php

/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Index extends AppModel {

	/**
	 *
	 */

	public $recursive = -1;



	/**
	 *
	 */

	public $belongsTo = array(
		'Token' => array(
			'className' => 'Sprinkles.Token'
		)
	);

}