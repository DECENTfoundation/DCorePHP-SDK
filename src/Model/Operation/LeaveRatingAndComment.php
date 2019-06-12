<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;

class LeaveRatingAndComment extends BaseOperation
{
    public const OPERATION_TYPE = 22;
    public const OPERATION_NAME = 'leave_rating_and_comment';

    /** @var string */
    private $uri;
    /** @var ChainObject */
    private $consumer;
    /** @var int */
    private $rating;
    /** @var string */
    private $comment;

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return LeaveRatingAndComment
     */
    public function setUri(string $uri): LeaveRatingAndComment
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getConsumer(): ChainObject
    {
        return $this->consumer;
    }

    /**
     * @param ChainObject|string $consumer
     * @return LeaveRatingAndComment
     * @throws ValidationException
     */
    public function setConsumer($consumer): LeaveRatingAndComment
    {
        if (is_string($consumer)) {
            $consumer = new ChainObject($consumer);
        }
        $this->consumer = $consumer;

        return $this;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     * @return LeaveRatingAndComment
     */
    public function setRating(int $rating): LeaveRatingAndComment
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return LeaveRatingAndComment
     */
    public function setComment(string $comment): LeaveRatingAndComment
    {
        $this->comment = $comment;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'fee' => $this->getFee()->toArray(),
                'URI' => $this->getUri(),
                'consumer' => $this->getConsumer()->getId(),
                'comment' => $this->getComment(),
                'rating' => $this->getRating()
            ]
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getUri()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getUri())),
            $this->getConsumer()->toBytes(),
            VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getComment()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getComment())),
            Math::getInt64($this->getRating())
        ]);
    }
}
