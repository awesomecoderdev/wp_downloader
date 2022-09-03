<?php

namespace AwesomeCoder\Plugin\AC_Downloader\Controller;

use DOMDocument;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Awesomecoder
 * @subpackage Awesomecoder/controller
 * @author     MD Ibrahim Kholil <awesomecoder.dev@gmail.com>
 *                                                              __
 *                                                             | |
 *    __ ___      _____  ___  ___  _ __ ___   ___  ___ ___   __| | ___ _ ____
 *   / _` \ \ /\ / / _ \/ __|/ _ \| '_ ` _ \ / _ \/ __/ _ \ / _` |/ _ \ ' __|
 *  | (_| |\ V  V /  __/\__ \ (_) | | | | | |  __/ (_| (_) | (_| |  __/	 |
 *  \__,_| \_/\_/ \___||___/\___/|_| |_| |_|\___|\___\___/ \__,_|\___|__|
 *
 */
class Awesomecoder_Handler
{

	/**
	 * The array of error registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $error    The error registered with WordPress to fire when the login errors.
	 */
	public static $error = null;

	/**
	 * The array of error registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $error    The error registered with WordPress to fire when the login errors.
	 */
	public static $success = null;

	/**
	 * The array of page_id registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $page_id    The error registered with WordPress to fire when the page_id page.
	 */
	public static $page;

	/**
	 * The instacne of the woocommerce.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool    $instance    The instacne of the woocommerce.
	 */
	private static $instance = false;

	/**
	 * Define the core functionality of the woocommerce.
	 *
	 * Check woocommerce activated or not.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		// do somethings
	}


	/**
	 *  It is the shortcode functions of the template
	 *
	 * It will reutn the search box on a page
	 *
	 */
	public static function frontend_ajax_handler()
	{
		$request = json_decode(file_get_contents('php://input'));
		echo json_encode($_REQUEST);

		// end ajax
		wp_die();
	}



	/**
	 * Register the corn schedules
	 *
	 * @since    1.0.0
	 */
	public static function tiktok_cron_manager($schedules)
	{
		$schedules['tiktok_schedules'] = array(
			'interval' => 86400,
			'display'  => esc_html__('TikTok Schedules'),
		);
		return $schedules;
	}

	/**
	 * set schedules if not scheduled.
	 *
	 * @since    1.0.0
	 */
	public static function tiktok_cron_processor()
	{
		$videos = glob(ABSPATH . "tiktok/*");
		foreach ($videos as $video) {
			if (is_file($video)) {
				unlink($video);
			}
		}
	}


	/**
	 * set schedules if not scheduled.
	 *
	 * @since    1.0.0
	 */
	public static function backend_ajax_handler()
	{
		echo json_encode($_REQUEST);
		// if (isset($_REQUEST["vid"])) {
		// 	echo $url = "https://api.savetube.me/info?url=https://www.youtube.com/watch?v=SN1pMtF5ipw" . $_REQUEST["vid"];
		// } else {
		// 	echo json_encode([
		// 		"success" => false,
		// 		"message" => "Something went wrong."
		// 	]);
		// }

		die;
	}

	/**
	 *  It is the shortcode functions of the template
	 *
	 * It will reutn the search box on a page
	 *
	 */
	public static function init()
	{

		/**
		 * set schedules if not scheduled.
		 *
		 * @since    1.0.0
		 */
		if (!wp_next_scheduled('tiktok_cron_hook')) {
			wp_schedule_event(time(), 'tiktok_schedules', 'tiktok_cron_hook');
		}

		// cron job
		add_filter('cron_schedules', [__CLASS__, 'tiktok_cron_manager']);
		add_action("tiktok_cron_hook", [__CLASS__, 'tiktok_cron_processor']);

		// backend
		add_action("wp_ajax_awesomecoder_backend", [__CLASS__, 'backend_ajax_handler']);
		add_action("wp_ajax_nopriv_awesomecoder_backend", [__CLASS__, 'backend_ajax_handler']);

		// frontend
		add_action("wp_ajax_awesomecoder_frontend", [__CLASS__, 'frontend_ajax_handler']);
		add_action("wp_ajax_nopriv_awesomecoder_frontend", [__CLASS__, 'frontend_ajax_handler']);
	}
}
