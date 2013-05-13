<?php

/**
 *
 */

class IndexShell extends AppShell {

	/**
	 *
	 */

	public function getOptionParser( ) {

		$parser = parent::getOptionParser( );

		$parser->addOption(
			'model',
			array(
				'help' => __( 'Model to index.' ),
				'short' => 'm',
				'required' => true
			)
		);

		$parser->addOption(
			'start',
			array(
				'short' => 's',
				'default' => 1
			)
		);

		$parser->addOption(
			'block',
			array(
				'short' => 'b',
				'default' => 1000
			)
		);

		return $parser;
	}



	/**
	 *
	 */

	public function main( ) {

		$modelName = $this->params['model'];

		$this->uses = array( $modelName );
		$this->_loadModels( );

		if ( !isset( $this->{$modelName})) {
			$this->out( '<error>' . __( 'Unable to load model' ) . '</error>' );
			return;
		}

		$records = $this->{$modelName}->find(
			'all',
			array(
				'offset' => $this->params['start'],
				'limit' => $this->params['block'],
				'order' => "$modelName.id"
			)
		);

		if ( empty( $records )) {
			$this->out( '<error>' . __( 'No records found' ) . '</error>' );
			return;
		}

		foreach ( $records as $record ) {
			$this->{$modelName}->index( $record );
		}
	}
}
