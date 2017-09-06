<?php
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
		$this->errors = array();

		foreach ( $this->configs->plugins->item as $config ) {
			$slug = $config->attributes()['id'];
			$path = $slug . '/wpml-config.xml';
			if ( ! file_exists( $path ) ) {
				$this->errors[] = "File <$path> not found.";
			}
		}

		return empty( $this->errors );
	}

	function save_json() {
		$data = new stdClass;
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

