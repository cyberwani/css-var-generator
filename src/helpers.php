<?php
use Cyberwani\CSS_Var_Generator;

function var_generator_add_var( string $identifier, string $key, string $value ): void {
	$instance = CSS_Var_Generator::getInstance( $identifier );
	if ( ! $instance ) {
		$instance = new CSS_Var_Generator( $identifier );
	}
	$instance->addVar( $key, $value );
}
