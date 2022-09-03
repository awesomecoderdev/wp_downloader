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
	 *  It is the shortcode functions of the template
	 *
	 * It will reutn the search box on a page
	 *
	 */
	public static function extract_playstore_data($app)
	{
		$content = wp_remote_get($app);

		if (!is_wp_error($content)) {
			$content = wp_remote_retrieve_body($content);
			$output = [];
			$htmlDom = new DOMDocument();
			@$htmlDom->loadHTML($content);
			$name = $htmlDom->getElementsByTagName('h1')->item(0);
			$name = (isset($name->textContent) && is_string($name->textContent)) ? trim($name->textContent) : "";

			$icon = $htmlDom->getElementsByTagName('img')->item(0);
			$icon = $icon->getAttribute('src') ? $icon->getAttribute('src') : "";

			$rating = "";
			$ratings =  $htmlDom->getElementsByTagName('div');
			foreach ($ratings as $key => $r) {
				if ($r->getAttribute("itemprop") && $r->getAttribute("itemprop") == "starRating") {
					$r = (isset($r->textContent) && is_string($r->textContent)) ? trim($r->textContent) : "";
					$rating = str_replace("star", "", $r);
				}
			}
			$downloaded = "";
			$downloads =  $htmlDom->getElementsByTagName('div');
			foreach ($downloads as $key => $d) {
				if ($d->childElementCount == 2) {
					$text = (isset($d->nodeValue) && is_string($d->nodeValue)) ? trim($d->nodeValue) : "Unknown";
					$down = strstr($text, 'Downloads', true);
					$load = strstr($down, 'reviews');
					if (strpos($load, "reviews") !== false) {
						$downloaded = str_replace("reviews", "", $load);
						break;
					}
				}
			}
			$stared = "";
			$satars =  $htmlDom->getElementsByTagName('div');
			foreach ($satars as $key => $d) {
				if ($d->childElementCount == 2) {
					$text = (isset($d->nodeValue) && is_string($d->nodeValue)) ? trim($d->nodeValue) : "Unknown";
					$st = strstr($text, 'Downloads', true);
					$ar = strstr($st, 'star');
					$ed = strstr($ar, 'reviews', true);
					if (strpos($ed, "star") !== false) {
						$stared = str_replace("star", "", $ed);
						break;
					}
				}
			}
			$developer = "";
			$devName = "";
			$devLink = "";
			$links =  $htmlDom->getElementsByTagName('a');
			foreach ($links as $key => $link) {
				if ($link->getAttribute("href")) {
					if (strpos($link->getAttribute("href"), "/store/apps/dev") !== false) {
						$devName = $link->nodeValue;
						$devLink = "https://play.google.com" . $link->getAttribute("href");
						break;
					}
				}
			}

			$output = [
				"name" => $name,
				"icon" => (!empty($name) && !empty($devName) && !empty($devLink)) ? $icon : "",
				"downloads" => $downloaded,
				"stars" => $stared,
				"ratings" => $rating,
				"devName" => $devName,
				"devLink" => $devLink,
			];
		} else {

			$output = [
				"name" => "",
				"icon" => "",
				"downloads" => "",
				"stars" => "",
				"ratings" => "",
				"devName" => "",
				"devLink" => "",
			];
		}

		return $output;
	}

	/**
	 *  It is the shortcode functions of the template
	 *
	 * It will reutn the search box on a page
	 *
	 */
	public static function backend_ajax_handler()
	{
		$request = json_decode(file_get_contents('php://input'));
		if (isset($request->app)) {
			echo json_encode(self::extract_playstore_data($request->app));
		} else {
			echo json_encode([
				"success" => false,
				"msg" => "Something went wrong!",
			]);
		}

		// end ajax
		wp_die();
	}


	/**
	 *  It is the shortcode functions of the template
	 *
	 * It will reutn the search box on a page
	 *
	 */
	public static function the_content($content)
	{
		if ((is_single() || is_page()) && in_the_loop() && is_main_query()) {
			$content = preg_replace(
				'/<h2(.*?)<\/h2>/si',
				'<div class="w-full my-1 bg-black relative awesomecoder overflow-hidden h-10 text-white">
				<div class="relative flex lg:ml-32 ml-3 h-full items-center w-full z-10">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
						<path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
			  		</svg>
					<span class="font-poppins font-semibold mx-2"$1</span>
				</div>
				<div class="absolute -left-14 top-0 h-52 w-52 bg-primary-400 rounded-tr-[100px]">
				</div>
				<div class="absolute md:block hidden right-[-65px] top-1 h-[90px] w-[130px] bg-white rounded-tl-[65px]">
				</div>
			</div>',
				$content
			);
		}
		return $content;
	}

	/**
	 *  It is the shortcode functions of the template
	 *
	 * It will reutn the search box on a page
	 *
	 */
	public static function init()
	{
		// add_action('template_redirect', [__CLASS__, 'redirect_to']);
		// add_action('init', array(__CLASS__, 'init'));

		// content
		add_filter('the_content', [__CLASS__, 'the_content'], 10);

		// backend
		add_action("wp_ajax_awesomecoder_backend", [__CLASS__, 'backend_ajax_handler']);
		add_action("wp_ajax_nopriv_awesomecoder_backend", [__CLASS__, 'backend_ajax_handler']);

		// frontend
		add_action("wp_ajax_awesomecoder_frontend", [__CLASS__, 'frontend_ajax_handler']);
		add_action("wp_ajax_nopriv_awesomecoder_frontend", [__CLASS__, 'frontend_ajax_handler']);
	}
}
