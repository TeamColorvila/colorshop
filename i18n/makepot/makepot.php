<?php
/**
 * ColorShop POT Generator
 *
 * Contains the methods for generating the colorshop.pot and colorshop-admin.pot files.
 * Code is based on: http://i18n.trac.wordpress.org/browser/tools/trunk/makepot.php
 *
 * @class CS_Makepot
 * @since 2.0
 * @package ColorShop
 * @author Geert De Deckere
 */
class CS_Makepot {

	/**
	 * @var string Filesystem directory path for the ColorShop plugin (with trailing slash)
	 */
	public $colorshop_path;

	/**
	 * @var array All available projects with their settings
	 */
	public $projects;

	/**
	 * @var object StringExtractor
	 */
	public $extractor;

	/**
	 * @var array Rules for StringExtractor
	 */
	public $rules = array(
		'_'               => array( 'string' ),
		'__'              => array( 'string' ),
		'_e'              => array( 'string' ),
		'_c'              => array( 'string' ),
		'_n'              => array( 'singular', 'plural' ),
		'_n_noop'         => array( 'singular', 'plural' ),
		'_nc'             => array( 'singular', 'plural' ),
		'__ngettext'      => array( 'singular', 'plural' ),
		'__ngettext_noop' => array( 'singular', 'plural' ),
		'_x'              => array( 'string', 'context' ),
		'_ex'             => array( 'string', 'context' ),
		'_nx'             => array( 'singular', 'plural', null, 'context' ),
		'_nx_noop'        => array( 'singular', 'plural', 'context' ),
		'_n_js'           => array( 'singular', 'plural' ),
		'_nx_js'          => array( 'singular', 'plural', 'context' ),
		'esc_attr__'      => array( 'string' ),
		'esc_html__'      => array( 'string' ),
		'esc_attr_e'      => array( 'string' ),
		'esc_html_e'      => array( 'string' ),
		'esc_attr_x'      => array( 'string', 'context' ),
		'esc_html_x'      => array( 'string', 'context' ),
	);

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		// Default path
		$this->set_colorshop_path( '../../' );

		// All available projects with their settings
		$this->projects = array(
			'colorshop' => array(
				'title'    => 'Front-end',
				'file'     => $this->colorshop_path . 'i18n/languages/colorshop.pot',
				'excludes' => array( 'admin/.*' ),
				'includes' => array(),
			),
			'colorshop-admin' => array(
				'title'    => 'Admin',
				'file'     => $this->colorshop_path . 'i18n/languages/colorshop-admin.pot',
				'excludes' => array(),
				'includes' => array( 'admin/.*' ),
			),
		);

		// Ignore some strict standards notices caused by extract/extract.php
		error_reporting(E_ALL);

		// Load required files and objects
		require_once './not-gettexted.php';
		require_once './pot-ext-meta.php';
		require_once './extract/extract.php';
		$this->extractor = new StringExtractor( $this->rules );
	}

	/**
	 * Sets the ColorShop filesystem directory path
	 *
	 * @param string $path
	 * @return void
	 */
	public function set_colorshop_path( $path ) {
		$this->colorshop_path = realpath( $path ) . '/';
	}

	/**
	 * POT generator
	 *
	 * @param string $project "colorshop" or "colorshop-admin"
	 * @return bool true on success, false on error
	 */
	public function generate_pot( $project = 'colorshop' ) {
		// Unknown project
		if ( empty( $this->projects[ $project ] ) )
			return false;

		// Project config
		$config = $this->projects[ $project ];

		// Extract translatable strings from the ColorShop plugin
		$originals = $this->extractor->extract_from_directory( $this->colorshop_path, $config['excludes'], $config['includes'] );

		// Build POT file
		$pot = new PO;
		$pot->entries = $originals->entries;
		$pot->set_header( 'Project-Id-Version', 'ColorShop ' . $this->colorshop_version() . ' ' . $config['title'] );
		$pot->set_header( 'Report-Msgid-Bugs-To', 'https://github.com/colorvila/colorshop/issues' );
		$pot->set_header( 'POT-Creation-Date', gmdate( 'Y-m-d H:i:s+00:00' ) );
		$pot->set_header( 'MIME-Version', '1.0' );
		$pot->set_header( 'Content-Type', 'text/plain; charset=UTF-8' );
		$pot->set_header( 'Content-Transfer-Encoding', '8bit' );
		$pot->set_header( 'PO-Revision-Date', gmdate( 'Y' ) . '-MO-DA HO:MI+ZONE' );
		$pot->set_header( 'Last-Translator', 'FULL NAME <EMAIL@ADDRESS>' );
		$pot->set_header( 'Language-Team', 'LANGUAGE <EMAIL@ADDRESS>' );

		// Write POT file
		return $pot->export_to_file( $config['file'] );
	}

	/**
	 * Retrieves the ColorShop version from the colorshop.php file.
	 *
	 * @access public
	 * @return string|false ColorShop version number, false if not found
	 */
	public function colorshop_version() {
		// Only run this method once
		static $version;
		if ( null !== $version )
			return $version;

		// File that contains the ColorShop version number
		$file = $this->colorshop_path . 'colorshop.php';

 		if ( is_readable( $file ) && preg_match( '/\bVersion:\s*+(\S+)/i', file_get_contents( $file ), $matches ) )
			$version = $matches[1];
		else
			$version = false;

		return $version;
	}

}