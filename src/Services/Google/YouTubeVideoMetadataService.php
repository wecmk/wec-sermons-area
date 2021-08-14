<?php

namespace App\Services\Google;

use App\Entity\Event;
use App\Entity\User;
use Google_Client;
use Google_Service_YouTube;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class YouTubeVideoMetadataService
{
    private LoggerInterface $logger;
    private RouterInterface $router;

    private string $OAUTH_GOOGLE_CLIENT_ID;
    private string $OAUTH_GOOGLE_CLIENT_SECRET;
    private User $user;
    private Google_Client $client;

    public function __construct(LoggerInterface $logger, RouterInterface $router, Security $security, string $OAUTH_GOOGLE_CLIENT_ID, string $OAUTH_GOOGLE_CLIENT_SECRET)
    {
        $this->logger = $logger;
        $this->router = $router;
        $this->OAUTH_GOOGLE_CLIENT_ID = $OAUTH_GOOGLE_CLIENT_ID;
        $this->OAUTH_GOOGLE_CLIENT_SECRET = $OAUTH_GOOGLE_CLIENT_SECRET;
        $config = [
            "client_id" => $OAUTH_GOOGLE_CLIENT_ID,
            "client_secret" => $OAUTH_GOOGLE_CLIENT_SECRET,
            "access_type" => "offline",
            "api_format_v2" => true,
        ];
        $this->client = new Google_Client($config);

        $user = $security->getUser();
        if ($user instanceof User) {
            $this->user = $user;
        }

    }

    public function updateVideo(Event $event) {

        $accessKeys = [];
        $this->logger->debug("Loaded key");
        $accessKeys['expires_in'] = time() - $this->user->getExpires();
        $accessKeys['access_token'] = $this->user->getAccessToken();
        if ($this->user->getRefreshToken() != null) {
            $accessKeys['refresh_token'] = $this->user->getRefreshToken();
        }

        $this->client->setAccessToken($accessKeys);

        $this->client->setRedirectUri($this->_redirectURI);
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');

        if($this->client->isAccessTokenExpired()){  // if token expired
            // refresh the token
            $this->logger->warning("OAuth2.0 token expired");
            $this->client->refreshToken($accessKeys['refresh_token']);
        }
        $youtube = new Google_Service_YouTube($this->client);

        if (strpos($event->getYouTubeLink(), "youtube.com") !== false) {
            $stringParts = explode("=", $event->getYouTubeLink());
            if (count($stringParts) != 2) {
                return null;
            }
        }

        if (str_contains($event->getYouTubeLink(), "youtu.be")) {
            $string = str_replace("//", "", $event->getYouTubeLink());
            $stringParts = explode("/", $string);
            if (count($stringParts) != 2) {
                return null;
            }
        }
        // Call the API's videos.list method to retrieve the video resource.
        $listResponse = $youtube->videos->listVideos("snippet,status",
            array('id' => $stringParts[1]));
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
                if ($event->getTitle() == "") {
                    $videoTitle = $event->getDate()->format("l d F Y");
                } else {
                    $videoTitle = $event->getTitle();
                }
                $videoApm = "";
                $searchText = (date('D') == 'Sun') ? "Today" : "Next Sunday";
                $searchDate = date('U', strtotime($searchText));
                $isTodayOrNextSunday = $searchDate <= $event->getDate()->format("U");
                $this->logger->debug($searchDate . " <= " . $event->getDate()->format("U") . " | " . $searchDate <= $event->getDate()->format("U"));
                if ($isTodayOrNextSunday) {
                    if ($event->getApm() == "AM") {
                        $videoApm = " - 10:30 AM service";
                    } else if ($event->getApm() == "PM") {
                        $videoApm = " - 6:30 PM service";
                    } else {
                        $videoApm = "";
                    }
                } else if ($event->getReading() != "") {
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
}