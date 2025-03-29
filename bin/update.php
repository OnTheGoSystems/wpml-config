<?php
/**
 * Parses the XML index file and generates the corresponding JSON file.
 *
 * The WPML plugin will read the JSON file to keep up to date with changes.
 */

error_reporting( E_ALL );

$config = new WPML_Config_Index;
$config->update();
exit;

class WPML_Config_Index {

	var $configs;
	var $errors;

	function update() {
		$this->load_xml();
		if ( ! $this->validate_paths() ) {
			die( implode( PHP_EOL, $this->errors ) . PHP_EOL );
		}
		$this->save_json();
	}

	function load_xml() {
		$this->configs = simplexml_load_file( 'config-index.xml' );
	}

	function validate_paths() {
		$this->errors = array_merge(
			$this->validate_path( $this->configs->global->item ),
			$this->validate_path( $this->configs->plugins->item ),
			$this->validate_path( $this->configs->themes->item )
		);

		return empty( $this->errors );
	}

	function validate_path( $items ) {
		$errors = array();
		foreach ( $items as $config ) {
			$slug = $config->attributes()['id'];
			$path = $slug . '/wpml-config.xml';
			if ( ! file_exists( $path ) ) {
				$errors[] = "File <$path> not found.";
			}
		}

		return $errors;
	}

	function save_json() {
		$data = new stdClass;
		$data->global = $this->get_items( $this->configs->global->item );
		$data->plugins = $this->get_items( $this->configs->plugins->item );
		$data->themes = $this->get_items( $this->configs->themes->item );

		return file_put_contents( 'config-index.json', json_encode( $data ) );
	}

	function get_items( $items ) {
		$data = array();
		foreach ( $items as $item ) {
			$data[] = $this->get_item( $item );
		}
		return $data;
	}

	function get_item( $item ) {
		$name = (string) $item;
		$slug = (string) $item->attributes()['id'];
		$path = $slug . '/wpml-config.xml';
		$override_local = (bool) $item->attributes()['override_local'];

		$data = new stdClass;
		$data->name = $name;
		$data->override_local = $override_local;
		$data->path = 'wpml-config/' . $path;
		$data->updated = filemtime( $path );
		$data->hash = md5( file_get_contents( $path ) );

		return $data;
	}

}

