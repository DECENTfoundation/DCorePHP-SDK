<?php

namespace DCorePHP\Model\Content;

use DCorePHP\Model\ChainObject;

class Synopsis
{

    /** @var string */
    private $title;
    /** @var string */
    private $description;
    /** @var ChainObject */
    private $contentTypeId;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Synopsis
     */
    public function setTitle(string $title): Synopsis
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Synopsis
     */
    public function setDescription(string $description): Synopsis
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getContentTypeId(): ChainObject
    {
        return $this->contentTypeId;
    }

    /**
     * @param ChainObject | string $contentTypeId
     * @return Synopsis
     */
    public function setContentTypeId($contentTypeId): Synopsis
    {
        if (is_string($contentTypeId)) {
            $contentTypeId = new ChainObject($contentTypeId);
        }

        $this->contentTypeId = $contentTypeId;

        return $this;
    }

    public function toJson(): string
    {
        return json_encode(['title' => $this->title, 'description' => $this->description, 'content_type_id' => $this->contentTypeId->getId()]);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'content_type_id' => $this->getContentTypeId()->getId()
        ];
    }

}