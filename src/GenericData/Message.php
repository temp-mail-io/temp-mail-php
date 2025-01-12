<?php declare(strict_types=1);

namespace TempMailIo\TempMailPhp\GenericData;

use TempMailIo\TempMailPhp\Data;

class Message extends Data
{
    public string $id;

    public string $from;

    public string $to;

    public array $cc;

    public string $subject;

    public string $bodyText;

    public string $bodyHtml;

    public string $createdAt;

    /**
     * @var \TempMailIo\TempMailPhp\GenericData\Attachment[]
     */
    public array $attachments = [];
}