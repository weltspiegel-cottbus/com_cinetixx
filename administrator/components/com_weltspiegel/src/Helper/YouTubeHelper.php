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

	/**
	 * Get/download YouTube thumbnail for a video
	 *
	 * @param string $videoId YouTube video ID
	 *
	 * @return string|null Relative path to thumbnail or null if failed
	 *
	 * @since 1.0.0
	 */
	public static function getThumbnailPath(string $videoId): ?string
	{
		$filename = $videoId . '.jpg';
		$relativePath = 'images/youtube-thumbnails/' . $filename;
		$absolutePath = JPATH_ROOT . '/' . $relativePath;

		// Check if file already exists
		if (file_exists($absolutePath)) {
			return '/' . $relativePath;
		}

		// Ensure directory exists
		$dir = dirname($absolutePath);
		if (!is_dir($dir)) {
			if (!mkdir($dir, 0755, true)) {
				return null; // Failed to create directory
			}
		}

		// Try to download thumbnail (maxresdefault first, fallback to hqdefault)
		$urls = [
			"https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
			"https://img.youtube.com/vi/{$videoId}/hqdefault.jpg"
		];

		foreach ($urls as $url) {
			$image = @file_get_contents($url);
			if ($image !== false && strlen($image) > 0) {
				if (file_put_contents($absolutePath, $image)) {
					return '/' . $relativePath;
				}
			}
		}

		return null; // Download failed
	}
}