<?php
namespace Cyberwani;

class CSS_Var_Generator {
	protected static $instances = [];
	protected $identifier;
	protected $prefix;
	protected $vars = [];

	public function __construct( string $identifier, string $prefix = '' ) {
		$this->identifier = $identifier;
		$this->prefix     = $prefix;
		self::$instances[ $identifier ] = $this;
	}

	public function add_var( string $key, string $value ): void {
		$this->vars[ $key ] = $value;
	}

	public function get_css(): string {
		if ( empty( $this->vars ) ) {
			return '';
		}

		$css = ":root {\n";
		foreach ( $this->vars as $key => $value ) {
			$finalKey   = $this->prefix ? "{$this->prefix}_{$key}" : $key;
			$finalValue = $this->resolve_var_references( $value );
			$css .= "  --{$finalKey}: {$finalValue};\n";
		}
		$css .= "}\n";

		return $css;
	}

	protected function resolve_var_references( string $value ): string {
		return preg_replace_callback( '/var\(([\w\-]+)\)/', function ( $matches ) {
			$refKey = $matches[1];

			if ( isset( $this->vars[ $refKey ] ) ) {
				$resolvedKey = $this->prefix ? "{$this->prefix}_{$refKey}" : $refKey;
				return "var(--{$resolvedKey})";
			}

			return $matches[0];
		}, $value );
	}

	public static function get_instance( string $identifier ): ?self {
		return self::$instances[ $identifier ] ?? null;
	}
}
