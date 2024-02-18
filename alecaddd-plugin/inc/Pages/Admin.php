<?php 
/**
 * @package  AlecadddPlugin
 */
namespace Inc\Pages;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ManagerCallbacks;

/**
* 
*/
class Admin extends BaseController
{
	public $settings;

	public $callbacks;
	public $callbacks_mngr;

	public $pages = array();

	public $subpages = array();

	public function register() 
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();
		$this->callbacks_mngr = new ManagerCallbacks();

		$this->setPages();

		//$this->setSubpages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register();
	}

	public function setPages() 
	{
		$this->pages = array(
			array(
				'page_title' => 'Alecaddd Plugin', 
				'menu_title' => 'Alecaddd', 
				'capability' => 'manage_options', 
				'menu_slug' => 'alecaddd_plugin', 
				'callback' => array( $this->callbacks, 'adminDashboard' ), 
				'icon_url' => 'dashicons-store', 
				'position' => 110
			)
		);
	}

	// public function setSubpages()
	// {
	// 	$this->subpages = array(
	// 		array(
	// 			'parent_slug' => 'alecaddd_plugin', 
	// 			'page_title' => 'Custom Post Types', 
	// 			'menu_title' => 'CPT', 
	// 			'capability' => 'manage_options', 
	// 			'menu_slug' => 'alecaddd_cpt', 
	// 			'callback' => array( $this->callbacks, 'adminCpt' )
	// 		),
	// 		array(
	// 			'parent_slug' => 'alecaddd_plugin', 
	// 			'page_title' => 'Custom Taxonomies', 
	// 			'menu_title' => 'Taxonomies', 
	// 			'capability' => 'manage_options', 
	// 			'menu_slug' => 'alecaddd_taxonomies', 
	// 			'callback' => array( $this->callbacks, 'adminTaxonomy' )
	// 		),
	// 		array(
	// 			'parent_slug' => 'alecaddd_plugin', 
	// 			'page_title' => 'Custom Widgets', 
	// 			'menu_title' => 'Widgets', 
	// 			'capability' => 'manage_options', 
	// 			'menu_slug' => 'alecaddd_widgets', 
	// 			'callback' => array( $this->callbacks, 'adminWidget' )
	// 		),
	// 		array(
	// 			'parent_slug' => 'alecaddd_plugin', 
	// 			'page_title' => 'Login Manager', 
	// 			'menu_title' => 'Login Manager', 
	// 			'capability' => 'manage_options', 
	// 			'menu_slug' => 'alecaddd_plugin_login',
	// 			'callback' => array( $this->callbacks, 'adminLogin' )
	// 		),
	// 		array(
	// 			'parent_slug' => 'alecaddd_plugin', 
	// 			'page_title' => 'Gallery Manager', 
	// 			'menu_title' => 'Gallery Manager', 
	// 			'capability' => 'manage_options', 
	// 			'menu_slug' => 'alecaddd_plugin_gallery',
	// 			'callback' => array( $this->callbacks, 'adminGallery' )
	// 		),
	// 		array(
	// 			'parent_slug' => 'alecaddd_plugin', 
	// 			'page_title' => 'Testimonial Manager', 
	// 			'menu_title' => 'Testimonial Manager', 
	// 			'capability' => 'manage_options', 
	// 			'menu_slug' => 'alecaddd_plugin_testimonial',
	// 			'callback' => array( $this->callbacks, 'adminTestimonial' )
	// 		),
			
	// 	);
	// }

	public function setSettings()
	{
		$args = array();

		foreach ( $this->managers as $key => $value ) {
			$args[] = array(
				'option_group' => 'alecaddd_plugin_settings',
				'option_name' => $key,
				'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			);
		}

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'alecaddd_admin_index',
				'title' => 'Settings Manager',
				'callback' => array( $this->callbacks_mngr, 'adminSectionManager' ),
				'page' => 'alecaddd_plugin'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array();

		foreach ( $this->managers as $key => $value ) {
			$args[] = array(
				'id' => $key,
				'title' => $value,
				'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
				'page' => 'alecaddd_plugin',
				'section' => 'alecaddd_admin_index',
				'args' => array(
					'label_for' => $key,
					'class' => 'ui-toggle'
				)
			);
		}

		$this->settings->setFields( $args );
	}
}