<?php


namespace DCorePHP\Model\Asset;

class MonitoredAssetOptions
{
    /** @var array */
    private $feeds;
    /** @var AssetCurrentFeed */
    private $currentFeed;
    /** @var int */
    private $currentFeedPublicationTime;
    /** @var int */
    private $feedLifetimeSec;
    /** @var int */
    private $minimumFeeds;

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
     * @return AssetCurrentFeed
     */
    public function getCurrentFeed(): AssetCurrentFeed
    {
        return $this->currentFeed;
    }

    /**
     * @param AssetCurrentFeed $currentFeed
     * @return MonitoredAssetOptions
     */
    public function setCurrentFeed(AssetCurrentFeed $currentFeed): MonitoredAssetOptions
    {
        $this->currentFeed = $currentFeed;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentFeedPublicationTime(): int
    {
        return $this->currentFeedPublicationTime;
    }

    /**
     * @param int $currentFeedPublicationTime
     * @return MonitoredAssetOptions
     */
    public function setCurrentFeedPublicationTime(int $currentFeedPublicationTime): MonitoredAssetOptions
    {
        $this->currentFeedPublicationTime = $currentFeedPublicationTime;

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

}