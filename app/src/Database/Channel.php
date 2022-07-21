<?php

namespace App\Database;

use Cycle\Annotated\Annotation as Cycle;

#[Cycle\Entity(repository: ChannelRepository::class)]
class Channel
{
    #[Cycle\Column(type: "string", primary: true)]
    public ?string $id;

    #[Cycle\Column(type: "string", nullable: true)]
    public ?string $route = null;

    #[Cycle\Column(type: "integer")]
    public int $count;

    /**
     * Channel constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
