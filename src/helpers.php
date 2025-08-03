<?php
use Cyberwani\CSS_Var_Generator;

function var_generator_add_var( string $identifier, string $key, string $value ): void {
	$instance = CSS_Var_Generator::get_instance( $identifier );
	if ( ! $instance ) {
		$instance = new CSS_Var_Generator( $identifier );
	}
	$instance->add_var( $key, $value );
}
