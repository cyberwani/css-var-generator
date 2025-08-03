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

	public function addVar( string $key, string $value ): void {
		$this->vars[ $key ] = $value;
	}

	public function getCss(): string {
		if ( empty( $this->vars ) ) {
			return '';
		}

		$css = ":root {\n";
		foreach ( $this->vars as $key => $value ) {
			$finalKey   = $this->prefix ? "{$this->prefix}_{$key}" : $key;
			$finalValue = $this->resolveVarReferences( $value );
			$css .= "  --{$finalKey}: {$finalValue};\n";
		}
		$css .= "}\n";

		return $css;
	}

	protected function resolveVarReferences( string $value ): string {
		return preg_replace_callback( '/var\(([\w\-]+)\)/', function ( $matches ) {
			$refKey = $matches[1];

			if ( isset( $this->vars[ $refKey ] ) ) {
				$resolvedKey = $this->prefix ? "{$this->prefix}_{$refKey}" : $refKey;
				return "var(--{$resolvedKey})";
			}

			return $matches[0];
		}, $value );
	}

	public static function getInstance( string $identifier ): ?self {
		return self::$instances[ $identifier ] ?? null;
	}
}
