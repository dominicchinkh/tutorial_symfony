<?php

namespace App\Entity;

use App\Enum\PullRequestState;
use App\Repository\PullRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PullRequestRepository::class)]
class PullRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private PullRequestState $state;

    #[ORM\Column]
    private bool $rejectable = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getState(): PullRequestState
    {
        return $this->state;
    }

    public function setState(PullRequestState $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function isRejectable(): bool
    {
        return $this->rejectable;
    }

    public function setRejectable(bool $rejectable): static
    {
        $this->rejectable = $rejectable;

        return $this;
    }
}
