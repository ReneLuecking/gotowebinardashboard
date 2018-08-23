<?php

namespace App\Service;

use App\Entity\Organizer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class WebinarApi
 *
 * @package App\Service
 */
class WebinarApi
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $oauthHost;

    /**
     * @var string
     */
    private $apiHost;

    /**
     * WebinarApi constructor.
     *
     * @param LoggerInterface        $logger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger        = $logger;
        $this->entityManager = $entityManager;

        $this->oauthHost = 'https://api.getgo.com/oauth/v2/token';
        $this->apiHost   = 'https://api.getgo.com/G2W/rest/v2/';
    }

    /**
     * @param String $path
     * @param array  $headers
     * @param String $data
     * @param String $type
     *
     * @return bool|mixed
     */
    private function request(string $path, array $headers, string $data = '', string $type = 'GET')
    {
        if (empty($path)) {
            $url = $this->oauthHost;
        } else {
            $path = trim($path, '/');
            $url  = $this->apiHost . $path;
        }

        $ch = curl_init($url);

        if (false === $ch) {
            $this->logger->error('Curl could not be initiated.');
            return null;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if ('POST' === $type) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        if ($proxy = getenv('https_proxy')) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);

            if ($noproxy = getenv('no_proxy')) {
                curl_setopt($ch, CURLOPT_NOPROXY, $noproxy);
            }
        }

        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $ch_error = curl_error($ch);

        curl_close($ch);

        $httpCodeCategory = substr($httpCode, 0, 1);

        switch ($httpCodeCategory) {
            case '1':
            case '2':
                return json_decode($result);
            case '3':
                $this->logger->warning("Curl redirect (code: {$httpCode}): {$ch_error}");
                return null;
            case '4':
            case '5':
                $this->logger->error("Curl error (code: {$httpCode}): {$ch_error}");
                return null;
            default:
                $this->logger->warning("Unknown http code: {$httpCode}");
        }

        return null;
    }

    /**
     * @param Organizer $organizer
     * @param bool      $contentTypeJson
     * @param bool      $autoRefresh
     *
     * @return array|null
     */
    private function buildHeaders(Organizer $organizer, bool $contentTypeJson = false, bool $autoRefresh = true): ?array
    {
        if ($organizer->getExpiresin() < time()) {
            $access = $this->requestAccessToken($organizer);

            if (null === $access) {
                return null;
            } elseif (true === $autoRefresh) {
                $organizer->setAccesstoken($access->access_token);
                $organizer->setExpiresin(time() + $access->expires_in);
                $organizer->setRefreshtoken($access->refresh_token);
                $organizer->setRefreshexpiresin(time() + 30 * 86400);

                $this->entityManager->persist($organizer);
                $this->entityManager->flush();
            } else {
                return null;
            }
        }

        $headers = array(
            'Authorization: Bearer ' . $organizer->getAccesstoken(),
            'Accept: application/json',
        );

        if (true === $contentTypeJson) {
            $headers[] = 'Content-Type: application/json';
        } else {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }

        return $headers;
    }

    /**
     * @param Organizer $organizer
     * @param string    $tokenType
     *
     * @return bool|mixed
     */
    public function requestAccessToken(Organizer $organizer, string $tokenType = 'refreshtoken')
    {
        $auth = base64_encode($organizer->getConsumerkey() . ':' . $organizer->getConsumersecret());

        $headers = array(
            'Authorization: Basic ' . $auth,
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
        );

        if ('refreshtoken' === $tokenType) {
            if ($organizer->getRefreshexpiresin() > time()) {
                $data = http_build_query(array(
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $organizer->getRefreshtoken(),
                ));
            } else {
                $this->logger->warning("Refresh token of organizer {$organizer->getFirstname()} " .
                                       "{$organizer->getLastname()} ({$organizer->getOrganizerkey()}) expired.");

                return null;
            }
        } else {
            $data = http_build_query(array(
                'grant_type' => 'authorization_code',
                'code'       => $organizer->getResponsekey(),
            ));
        }

        return $this->request('', $headers, $data, 'POST');
    }

    public function requestWebinars(Organizer $organizer, \DateTime $start, \DateTime $end)
    {
        $headers = $this->buildHeaders($organizer);
        $path    = "organizers/{$organizer->getOrganizerkey()}/webinars";

        $start->setTimezone(new \DateTimeZone('UTC'));
        $end->setTimezone(new \DateTimeZone('UTC'));

        $data = http_build_query(array(
            'fromTime' => $start->format('Y-m-d\TH:i:s\Z'),
            'toTime'   => $end->format('Y-m-d\TH:i:s\Z'),
        ));

        return $this->request($path, $headers, $data);
    }
}
