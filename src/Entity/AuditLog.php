<?php

namespace App\Entity;

use App\Repository\AuditLogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuditLogRepository::class)]
class AuditLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(length: 50)]
    private ?string $actionType = null;

    /**
     * @var Collection<int, Jar>
     */
    #[ORM\ManyToMany(targetEntity: Jar::class, inversedBy: 'auditLogs')]
    private Collection $jars;

    /**
     * @var Collection<int, EmptyJarStat>
     */
    #[ORM\ManyToMany(targetEntity: EmptyJarStat::class)]
    private Collection $emptyJarStats;

    public function __construct()
    {
        $this->jars = new ArrayCollection();
        $this->emptyJarStats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getActionType(): ?string
    {
        return $this->actionType;
    }

    public function setActionType(string $actionType): static
    {
        $this->actionType = $actionType;

        return $this;
    }

    /**
     * @return Collection<int, Jar>
     */
    public function getJars(): Collection
    {
        return $this->jars;
    }

    public function addJar(Jar $jar): static
    {
        if (!$this->jars->contains($jar)) {
            $this->jars->add($jar);
        }

        return $this;
    }

    public function removeJar(Jar $jar): static
    {
        $this->jars->removeElement($jar);

        return $this;
    }

    /**
     * @return Collection<int, EmptyJarStat>
     */
    public function getEmptyJarStats(): Collection
    {
        return $this->emptyJarStats;
    }

    public function addEmptyJarStat(EmptyJarStat $emptyJarStat): static
    {
        if (!$this->emptyJarStats->contains($emptyJarStat)) {
            $this->emptyJarStats->add($emptyJarStat);
        }

        return $this;
    }

    public function removeEmptyJarStat(EmptyJarStat $emptyJarStat): static
    {
        $this->emptyJarStats->removeElement($emptyJarStat);

        return $this;
    }
}
