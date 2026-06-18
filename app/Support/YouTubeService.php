<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class YouTubeService
{
    /**
     * Extract playlist ID from YouTube Playlist URL.
     */
    public static function extractPlaylistId($url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $queries);
        return $queries['list'] ?? null;
    }

    /**
     * Fetch all videos (IDs and titles) in a YouTube Playlist.
     */
    public function fetchPlaylistVideos($playlistUrl)
    {
        $playlistId = self::extractPlaylistId($playlistUrl);
        if (!$playlistId) {
            return [];
        }

        $url = "https://www.youtube.com/playlist?list=" . $playlistId;
        
        try {
            // Make request with standard user agent to avoid bot detection
            $response = Http::withoutVerifying()->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            ])->timeout(10)->get($url);

            if (!$response->successful()) {
                return [];
            }

            $html = $response->body();

            // Extract ytInitialData json which has all playlist information
            preg_match('/var ytInitialData = (\{.*?\});/', $html, $matches);
            if (isset($matches[1])) {
                $data = json_decode($matches[1], true);
                $videos = [];
                
                try {
                    $tabs = $data['contents']['twoColumnBrowseResultsRenderer']['tabs'] ?? [];
                    $contents = [];
                    foreach ($tabs as $tab) {
                        $sectionContents = $tab['tabRenderer']['content']['sectionListRenderer']['contents'][0]['itemSectionRenderer']['contents'] ?? null;
                        if ($sectionContents) {
                            if (isset($sectionContents[0]['playlistVideoListRenderer']['contents'])) {
                                $contents = $sectionContents[0]['playlistVideoListRenderer']['contents'];
                            } else {
                                $contents = $sectionContents;
                            }
                            break;
                        }
                    }

                    if (empty($contents) && isset($data['contents']['twoColumnBrowseResultsRenderer']['tabs'][0]['tabRenderer']['content']['richGridRenderer']['contents'])) {
                        // Fallback layout
                        $contents = $data['contents']['twoColumnBrowseResultsRenderer']['tabs'][0]['tabRenderer']['content']['richGridRenderer']['contents'];
                    }

                    foreach ($contents as $item) {
                        $videoRenderer = $item['playlistVideoRenderer'] ?? null;
                        $lockupModel = $item['lockupViewModel'] ?? null;

                        if ($videoRenderer) {
                            $videoId = $videoRenderer['videoId'] ?? null;
                            $title = $videoRenderer['title']['runs'][0]['text'] ?? ($videoRenderer['title']['simpleText'] ?? 'Untitled Lecture');
                        } elseif ($lockupModel) {
                            $videoId = $lockupModel['contentId'] ?? null;
                            $title = $lockupModel['metadata']['lockupMetadataViewModel']['title']['content'] ?? 'Untitled Lecture';
                        } else {
                            continue;
                        }

                        if ($videoId) {
                            $videos[] = [
                                'video_id' => $videoId,
                                'title' => $title,
                                'url' => 'https://www.youtube.com/watch?v=' . $videoId,
                            ];
                        }
                    }
                } catch (\Exception $e) {
                    // Fallback to regex matching if JSON structure changes
                }

                if (!empty($videos)) {
                    return $videos;
                }
            }

            // Simple Regex Fallback if ytInitialData isn't found
            preg_match_all('/"videoId":"([^"]+)"/', $html, $videoIds);
            preg_match_all('/"title":\{"runs":\[\{"text":"([^"]+)"\}/', $html, $titles);

            $videos = [];
            if (!empty($videoIds[1])) {
                $uniqueIds = array_unique($videoIds[1]);
                $i = 0;
                foreach ($uniqueIds as $vidId) {
                    // Filter out channel/playlist links that aren't videos
                    if (strlen($vidId) === 11) {
                        $title = $titles[1][$i] ?? 'Lecture ' . (count($videos) + 1);
                        $videos[] = [
                            'video_id' => $vidId,
                            'title' => $title,
                            'url' => 'https://www.youtube.com/watch?v=' . $vidId,
                        ];
                        $i++;
                    }
                }
            }

            return $videos;

        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Fetch subtitles / transcript text of a YouTube video by video ID.
     */
    public function fetchVideoTranscript($videoId)
    {
        try {
            $url = "https://www.youtube.com/watch?v=" . $videoId;
            $headers = [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Referer' => $url
            ];

            $response = Http::withoutVerifying()->withHeaders($headers)->timeout(10)->get($url);

            if (!$response->successful()) {
                logger()->warning("YouTube watch page request failed for video {$videoId} with status: " . $response->status());
                return null;
            }

            $html = $response->body();

            // Find captionTracks JSON inside ytPlayerResponse
            preg_match('/"captionTracks":\s*(\[.*?\])/', $html, $matches);
            if (!isset($matches[1])) {
                logger()->warning("No captionTracks found in YouTube watch page for video {$videoId}");
                return null;
            }

            $captionTracks = json_decode($matches[1], true);
            if (empty($captionTracks)) {
                logger()->warning("YouTube captionTracks array is empty for video {$videoId}");
                return null;
            }

            // Find English track first, otherwise take the first available
            $trackUrl = null;
            foreach ($captionTracks as $track) {
                if (str_contains(strtolower($track['vssId'] ?? ''), 'en')) {
                    $trackUrl = $track['baseUrl'];
                    break;
                }
            }

            if (!$trackUrl) {
                $trackUrl = $captionTracks[0]['baseUrl'];
            }

            if ($trackUrl) {
                $xmlResponse = Http::withoutVerifying()->withHeaders($headers)->timeout(10)->get($trackUrl);
                
                if ($xmlResponse->successful()) {
                    // Parse YouTube caption XML (e.g. <text start="0" dur="2">Hello</text>)
                    $xml = simplexml_load_string($xmlResponse->body());
                    $transcript = [];
                    foreach ($xml->text as $textNode) {
                        $transcript[] = html_entity_decode((string) $textNode);
                    }
                    return implode(' ', $transcript);
                } else {
                    logger()->warning("YouTube timedtext XML fetch failed for video {$videoId} with status: " . $xmlResponse->status() . " (commonly 429 rate limit)");
                }
            }

            return null;
        } catch (\Exception $e) {
            logger()->error("Exception while fetching YouTube transcript for video {$videoId}: " . $e->getMessage());
            return null;
        }
    }
}
