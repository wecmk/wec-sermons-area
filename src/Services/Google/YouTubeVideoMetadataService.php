<?php

namespace App\Services\Google;

use App\Entity\Event;
use Google\Service\YouTube;
use Google\Service\YouTube\LiveBroadcast;
use Google_Client;
use Google_Service_YouTube;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;

class YouTubeVideoMetadataService
{
    public $_redirectURI;
    private ?Google_Service_YouTube $google_Service_YouTube = null;
    private readonly Google_Client $client;

    public function __construct(private readonly LoggerInterface $logger, private readonly RouterInterface $router, private readonly GoogleCredentials $googleCredentials, private readonly string $OAUTH_GOOGLE_CLIENT_ID, private readonly string $OAUTH_GOOGLE_CLIENT_SECRET)
    {
        $config = [
            "client_id" => $this->OAUTH_GOOGLE_CLIENT_ID,
            "client_secret" => $this->OAUTH_GOOGLE_CLIENT_SECRET,
            "access_type" => "offline",
            "api_format_v2" => true,
        ];
        $this->client = new Google_Client($config);
    }

    public function googleServiceYouTube()
    {
        if ($this->google_Service_YouTube != null) {
            return $this->google_Service_YouTube;
        }
        $accessKeys = [];
        $this->logger->debug("Loaded key");
        $accessKeys['expires_in'] = time() - $this->googleCredentials->getExpires();
        $accessKeys['access_token'] = $this->googleCredentials->getAccessToken();
        if ($this->googleCredentials->getRefreshToken() != null) {
            $accessKeys['refresh_token'] = $this->googleCredentials->getRefreshToken();
        }

        $this->client->setAccessToken($accessKeys);

        $this->client->setRedirectUri($this->_redirectURI);
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');

        if ($this->client->isAccessTokenExpired()) {  // if token expired
            // refresh the token
            $this->logger->warning("OAuth2.0 token expired");

            $obj = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());

            $expires = time() + intval($obj['expires_in']);
            $this->googleCredentials->setAccessToken($obj['access_token'], true);
            if ($obj['access_token'] != null) {
                $this->googleCredentials->setRefreshToken($obj['refresh_token'], true);
            }
            $this->googleCredentials->setExpires($expires, true);
            $this->googleCredentials->persist();
        }
        return $this->google_Service_YouTube = new Google_Service_YouTube($this->client);
    }

    public function updateVideo(Event $event)
    {
        $youtube = $this->googleServiceYouTube();

        if (str_contains((string) $event->getYouTubeLink(), "/live/")) {
            $this->logger->debug("Live link detected: " . $event->getYouTubeLink());
            $string = $event->getYouTubeLink();
            $string = str_replace("https", "http", $string);
            $string = str_replace("http", "", $string);
            $string = str_replace("://youtube.com/live/", "", $string);
            if (str_contains($string, "?")) {
                $stringParts = explode("?", $string);
                $string = $stringParts[0];
            }
            $this->logger->debug("Live link detected: " . $string);
        } else {
            if (str_contains((string)$event->getYouTubeLink(), "youtube.com")) {
                $stringParts = explode("=", (string)$event->getYouTubeLink());
                $string = $stringParts[1];
                if (count($stringParts) != 2) {
                    return null;
                }
            }

            if (str_contains((string)$event->getYouTubeLink(), "youtu.be")) {
                $string = str_replace("//", "", $event->getYouTubeLink());
                $stringParts = explode("/", $string);
                if (count($stringParts) != 2) {
                    return null;
                }
                $string = $stringParts[1];
            }
        }

        $this->logger->debug($event->getYouTubeLink());
        $this->logger->debug(print_r($stringParts, true));
        // Call the API's videos.list method to retrieve the video resource.
        $listResponse = $youtube->videos->listVideos(
            "snippet,status",
            ['id' => $string]
        );
        $this->logger->debug("Searched for videos. Count of videos: " . $listResponse->count());

        $video = null;

        // If $listResponse is empty, the specified video was not found.
        if (empty($listResponse)) {
            $htmlBody = sprintf('<h3>Can\'t find a video with video id: %s</h3>', $stringParts[1]);
        } else {
            /* @var Google_Service_YouTube_Video $video */;
            if ($listResponse->count() > 0) {
                // Format Title
                $video = $listResponse->getItems()[0];
                $videoTitle = "";
                $videoTitle = $event->getTitle() == "" ? $event->getDate()->format("l d F Y") : $event->getTitle();
                $videoApm = "";
                $searchText = (date('D') === 'Sun') ? "Today" : "Next Sunday";
                $searchDate = date('U', strtotime($searchText));
                $isTodayOrNextSunday = $searchDate <= $event->getDate()->format("U");
                $this->logger->debug($searchDate . " <= " . $event->getDate()->format("U") . " | " . $searchDate <= $event->getDate()->format("U"));
                if ($isTodayOrNextSunday) {
                    if ($event->getApm() == "AM") {
                        $videoApm = " - 10:30 AM service";
                    } elseif ($event->getApm() == "PM") {
                        $videoApm = " - 6:30 PM service";
                    } else {
                        $videoApm = "";
                    }
                } elseif ($event->getReading() != "") {
                    $videoApm = " | " . $event->getReading();
                }
                $videoSpeaker = ($event->getSpeaker() == "") ? "" : $event->getSpeaker();
                // End FORMAT title

                $video->getSnippet()->setTitle($videoTitle . "" . $videoApm . " | " . $videoSpeaker);
                if ($event->getDate()->format("U") > date('U', strtotime("10 April 2021"))) {
                    if (!$isTodayOrNextSunday) {
                        // Videos once we started singing (23rd May 2021) to be unlisted)
                        if ($event->getDate()->getTimestamp() > 1621724400) {
                            $video->getStatus()->setPrivacyStatus("unlisted");
                        } else {
                            $video->getStatus()->setPrivacyStatus("public");
                        }
                    }
                    $youtube->videos->update("snippet,status", $video);
                }
            } else {
                $this->logger->debug("No videos loaded");
            }
        }
        return $video;
    }

    public function createLiveBroadcast(Event $event)
    {
        $youtube = $this->googleServiceYouTube();

        if (str_contains((string) $event->getYouTubeLink(), "youtube.com")) {
            $stringParts = explode("=", (string) $event->getYouTubeLink());
            if (count($stringParts) != 2) {
                return null;
            }
        }

        if (str_contains((string) $event->getYouTubeLink(), "youtu.be")) {
            $string = str_replace("//", "", $event->getYouTubeLink());
            $stringParts = explode("/", $string);
            if (count($stringParts) != 2) {
                return null;
            }
        }

        // Call the API's videos.list method to retrieve the video resource.
        $listResponse = $youtube->videos->listVideos(
            "snippet,status",
            ['id' => $stringParts[1]]
        );
        $this->logger->debug("Searched for videos. Count of videos: " . $listResponse->count());

        $video = null;


        $liveBroadcast = new LiveBroadcast();
        $liveBroadcastSnippet = new YouTube\LiveBroadcastSnippet();
        $liveBroadcastSnippet->setTitle("TestLiveBroadcast");
        $startDateTime = new DateTime('2021-10-03 23:21:46');
        $liveBroadcastSnippet->setScheduledStartTime($startDateTime->format(\DateTimeInterface::ATOM));
        $liveBroadcastSnippet->setTitle($event->getDate()->format('l j M Y ') . " | ");
        $liveBroadcast->setSnippet($liveBroadcastSnippet);

        $contentDetails = new YouTube\LiveBroadcastContentDetails();
        $contentDetails->setEnableDvr(true);
        $contentDetails->setEnableAutoStart(false);
        $contentDetails->setEnableAutoStop(false);
        $liveBroadcast->setContentDetails($contentDetails);

        $status = new YouTube\LiveBroadcastStatus();
        $status->setPrivacyStatus("Unlisted");
        $liveBroadcast->setStatus($status);

        $result = $youtube->liveBroadcasts->insert('snippet,contentDetails,status', $liveBroadcast);
        return $result->getId();
    }
}
