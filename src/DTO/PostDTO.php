<?php declare(strict_types=1);

namespace App\DTO;

use InvalidArgumentException;
use DateTime;

class PostDTO implements DTO
{
    private string $id;
    private string $fromName;
    private string $fromId;
    private string $message;
    private string $type;
    private DateTime $createdTime;

    /**
     * PostDTO constructor.
     * @param array $postData
     */
    public function __construct(array $postData)
    {
        foreach (['id', 'from_name', 'from_id', 'message', 'type', 'created_time'] as $requiredKey) {
            if (!array_key_exists($requiredKey, $postData)) {
                throw new InvalidArgumentException('Wrong post provided data');
            }
        }

        $this->id = $postData['id'];
        $this->fromName = $postData['from_name'];
        $this->fromId = $postData['from_id'];
        $this->message = $postData['message'];
        $this->type = $postData['type'];
        $this->createdTime = DateTime::createFromFormat(DATE_ATOM, $postData['created_time']);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @return string
     */
    public function getFromId(): string
    {
        return $this->fromId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function getCreatedTime(): DateTime
    {
        return $this->createdTime;
    }
}
