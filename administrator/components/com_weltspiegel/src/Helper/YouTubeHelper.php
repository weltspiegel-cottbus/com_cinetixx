<?php
/**
 * @package     Weltspiegel\Component\Weltspiegel\Administrator\Helper
 *
 * @copyright   Weltspiegel Cottbus
 * @license     MIT; see LICENSE file
 */

namespace Weltspiegel\Component\Weltspiegel\Administrator\Helper;

\defined('_JEXEC') or die;

/**
 * YouTube link helpers
 *
 * @since 1.0.0
 */
abstract class YouTubeHelper
{
	/**
	 * Regular expression to parse YouTube Urls for the YouTube video id
	 * See: https://gist.github.com/afeld/1254889
	 *
	 * @since 1.0.0
	 */
	private const string YOUTUBE_REGEX = '#^(?:https?://|//)?(?:www\.|m\.|.+\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|shorts/|feeds/api/videos/|watch\?v=|watch\?.+&v=))([\w-]{11})(?![\w-])#';

	/**
	 * Parses a YouTube Video ID from a YouTube url
	 * @param $url
	 *
	 * @return false|string
	 *
	 * @since 1.0.0
	 */
	public static function parseYoutubeId($url): false|string
	{
		if(!$url) return false;

		preg_match(self::YOUTUBE_REGEX, $url, $matches);
		return $matches[1] ?? false;
	}
}