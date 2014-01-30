<?php
// Dependencies
require(__DIR__ . '/core.php');

/**
 * The main class for the preview change library
 */
class betterPing extends core {

	/**
	 * The expected url of the tab
	 */
	const url = 'betterPing';

	/**
	 * The map of filters to apply.
	 * The key is the filter you want to hook onto.
	 * The value is the method of this object you wish to add.
	 */
	public static $filterMap = array(
		'admin_menu' => array(
			'method' => 'setupAdminMenu',
			'priority' => 10,
			'args' => 1,
		),
		'publish_post' => array(
			'method' => 'pingPost',
			'priority' => 10,
			'args' => 2,
		),
		'publish_page' => array(
			'method' => 'pingPage',
			'priority' => 10,
			'args' => 2,
		),
	);

	/**
	 * Will iterate the static property $filterMap and apply those filters
	 */
	public function __construct() {
		foreach(self::$filterMap as $filter => $items) {
			add_filter($filter, array($this, $items['method']), $items['priority'], $items['args']);
		}
		add_action('registered_post_type', array($this, 'registerPostType'), 10, 2);
		$this->registerPostTypes();
	}

	/**
	 * Register a specific page type.
	 *
	 * @param string $pType The post type to register.
	 * @return null
	 */
	public function registerPostType($pType) {
		add_filter("publish_{$pType}", array($this, 'pingPage'), 10, 2);
		return;
	}

	/**
	 * Register a specific page type.
	 *
	 * @return null
	 */
	public function registerPostTypes() {
		foreach (get_post_types(array('_builtin' => false)) as $pType) {
			$this->registerPostType($pType);
		}
		return;
	}

	/**
	 * Will ping all ur's if a enabled and a post is published
	 *
	 * @param int $postId
	 * @return null
	 */
	public function pingPost($postId) {
		if(self::getOption('bp_publishPost') == 'true') {
			self::ping('post', $postId);
		}
		return;
	}


	/**
	 * Will ping all ur's if a enabled and a page is published
	 *
	 * @param int $postId
	 * @return null
	 */
	public function pingPage($pageId) {
		if(self::getOption('bp_publishPage') == 'true') {
			self::ping('page', $pageId);
		}
		return;
	}

	/**
	 * Sets up the menu in the admin panel
	 * @return null
	 */
	public static function setupAdminMenu() {
		add_options_page('Better Ping', 'Better Ping', 'manage_options', self::url, array(__CLASS__, 'adminMenu'));
		return;
	}

	/**
	 * Gets the data to/from the view
	 */
	public static function adminMenu() {
		foreach($_POST as $key => &$value) {
			if(strpos($key, 'bp_') === 0) {
				self::saveOption($key, $value);
			}
		}
		self::render('settings', array('url' => self::url));
		return;
	}

	/**
	 * Will return an option.
	 *
	 * @param mixed $key The key you wish to retrieve
	 * @param mixed $default The default value if it does not exist
	 * @return mixed
	 */
	public static function getOption($key, $default = null) {
		return get_option($key, $default);
	}

	/**
	 * Will save wp options
	 *
	 * @param string $key
	 * @param mixed
	 */
	protected static function saveOption($key, $value) {
		$option_exists = (get_option($key, null) !== null);
		if ($option_exists) {
			update_option($key, $value);
		} else {
			add_option($key, $value);
		}
		return;
	}

	/**
	 * Iterates the url's to ping then sends a request to them
	 *
	 * @param string $type The type of publish this is page|post
	 * @param int $id
	 * @return null
	 */
	protected static function ping($type, $id) {
		$name = get_option('blogname');
		$url = trailingslashit(home_url());
		$changeurl = get_permalink($id);
		$category = $id;
		$server = strtok(self::getOption('bp_urls'), "\n");
		while ($server !== false) {
			self::sendPing($server, $name, $url, $changeurl, $category);
			$server = strtok("\n");
		}
	}

	/**
	 * Will send a ping with the url and updatedurl.
	 *
	 * weblogUpdates.ping (weblogname, weblogurl, changesurl=weblogurl, categoryname="none") returns struct
	 *
	 * @link http://xmlrpc.scripting.com/weblogsCom.html
	 * @return null
	 */
	protected static function sendPing($server, $name, $url, $changeurl, $category = null) {
		global $wp_version;

		include_once(ABSPATH . WPINC . '/class-IXR.php');
		include_once(ABSPATH . WPINC . '/class-wp-http-ixr-client.php');

		$client = new WP_HTTP_IXR_Client($server);
		$client->timeout = 3; // using a timeout of 3 seconds should be enough to cover slow servers
		$client->useragent .= ' -- WordPress/'.$wp_version;
		$client->debug = false;

		if (!$client->query('weblogUpdates.extendedPing', $name, $url, $changeurl, $category)) {
			$client->query('weblogUpdates.ping', $name, $url, $changeurl, $category);
		}
	}
}