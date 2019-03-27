<?php

namespace DCorePHP\Model;

class Publishing
{
    /** @var bool */
    private $isPublishingManager;
    /** @var array */
    private $publishRightsReceived;
    /** @var array */
    private $publishRightsForwarded;

    public function getIsPublishingManager(): bool
    {
        return $this->isPublishingManager;
    }

    public function setIsPublishingManager(bool $isPublishingManager): Publishing
    {
        $this->isPublishingManager = $isPublishingManager;

        return $this;
    }

    public function getPublishRightsReceived(): array
    {
        return $this->publishRightsReceived;
    }

    public function setPublishRightsReceived(array $publishRightsReceived): Publishing
    {
        $this->publishRightsReceived = $publishRightsReceived;

        return $this;
    }

    public function getPublishRightsForwarded(): array
    {
        return $this->publishRightsForwarded;
    }

    public function setPublishRightsForwarded(array $publishRightsForwarded): Publishing
    {
        $this->publishRightsForwarded = $publishRightsForwarded;

        return $this;
    }
}
