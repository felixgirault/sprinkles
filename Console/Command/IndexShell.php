<?php

/**
 *
 */

class IndexShell extends AppShell {

	/**
	 *
	 */

	public function getOptionParser( ) {

		return ConsoleOptionParser::buildFromArray(
			array(
				'description' => array(
					__( 'Use this command to index model data.' )
				),
				'arguments' => array(
					'model' => array(
						'help' => __( 'Model to index.' ),
						'required' => true
					)
				)
			)
		);
	}



	/**
	 *
	 */

	public function main( ) {


	}
}
