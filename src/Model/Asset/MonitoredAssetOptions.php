<?php


namespace DCorePHP\Model\Asset;

use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;

class MonitoredAssetOptions
{
    /** @var array */
    private $feeds = [];
    /** @var PriceFeed */
    private $currentFeed;
    /** @var \DateTime */
    private $currentFeedPublicationTime;
    /** @var int */
    private $feedLifetimeSec = 86400;
    /** @var int */
    private $minimumFeeds = 1;

    /**
     * MonitoredAssetOptions constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->currentFeed = new PriceFeed();
        $this->currentFeedPublicationTime = new \DateTime();
    }

    /**
     * @return array
     */
    public function getFeeds(): array
    {
        return $this->feeds;
    }

    /**
     * @param array $feeds
     * @return MonitoredAssetOptions
     */
    public function setFeeds(array $feeds): MonitoredAssetOptions
    {
        $this->feeds = $feeds;

        return $this;
    }

    /**
     * @return PriceFeed
     */
    public function getCurrentFeed(): PriceFeed
    {
        return $this->currentFeed;
    }

    /**
     * @param PriceFeed $currentFeed
     * @return MonitoredAssetOptions
     */
    public function setCurrentFeed(PriceFeed $currentFeed): MonitoredAssetOptions
    {
        $this->currentFeed = $currentFeed;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCurrentFeedPublicationTime(): \DateTime
    {
        return $this->currentFeedPublicationTime;
    }

    /**
     * @param \DateTime | string $currentFeedPublicationTime
     * @return MonitoredAssetOptions
     * @throws \Exception
     */
    public function setCurrentFeedPublicationTime($currentFeedPublicationTime): MonitoredAssetOptions
    {
        $this->currentFeedPublicationTime = $currentFeedPublicationTime instanceof \DateTime ? $currentFeedPublicationTime : new \DateTime($currentFeedPublicationTime);

        return $this;
    }

    /**
     * @return int
     */
    public function getFeedLifetimeSec(): int
    {
        return $this->feedLifetimeSec;
    }

    /**
     * @param int $feedLifetimeSec
     * @return MonitoredAssetOptions
     */
    public function setFeedLifetimeSec(int $feedLifetimeSec): MonitoredAssetOptions
    {
        $this->feedLifetimeSec = $feedLifetimeSec;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinimumFeeds(): int
    {
        return $this->minimumFeeds;
    }

    /**
     * @param int $minimumFeeds
     * @return MonitoredAssetOptions
     */
    public function setMinimumFeeds(int $minimumFeeds): MonitoredAssetOptions
    {
        $this->minimumFeeds = $minimumFeeds;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'feeds' => $this->getFeeds(),
            'current_feed' => $this->getCurrentFeed()->toArray(),
            'current_feed_publication_time' => $this->getCurrentFeedPublicationTime()->format('Y-m-d\TH:i:s'),
            'feed_lifetime_sec' => $this->getFeedLifetimeSec(),
            'minimum_feeds' => $this->getMinimumFeeds(),
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getFeeds() ?
                VarInt::encodeDecToHex(sizeof($this->getFeeds()))
                . '' // TODO array_map each element toBytes()
                : '00',
            $this->getCurrentFeed()->getCoreExchangeRate()->toBytes(),
            Math::getInt32($this->getCurrentFeedPublicationTime()->format('U')),
            Math::getInt32($this->getFeedLifetimeSec()),
            Math::getInt16($this->getMinimumFeeds()),
        ]);
    }
}
