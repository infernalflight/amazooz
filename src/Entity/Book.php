<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity()]
#[ORM\Table(name: 'Books')]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(name: 'title', type: Types::STRING, length: 250, nullable: false)]
    #[Assert\Length(max: 100, maxMessage: 'Le titre ne doit pas dépasser {{ limit }} caractères')]
    #[Assert\Length(min: 3, minMessage: 'Le titre doit avoir au moins {{ limit }} caractères')]
    private string $title;

    #[ORM\Column(name: 'author', type: Types::STRING, length: 100, nullable: false)]
    private string $author;

    #[ORM\Column(name: 'nb_pages', type: Types::SMALLINT, nullable: false, options: ['unsigned' => true])]
    #[Assert\Range(min: 10, max: 300, notInRangeMessage: 'Le nombre de page doit etre compris entre {{ min }} et {{ max }} pages')]
    private int $nbPage = 0;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\GreaterThanOrEqual('today UTC')]
    private ?\DateTimeInterface $publishedDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\GreaterThan(propertyPath: 'publishedDate', message: 'Cette date ne peut pas être postérieure à {{ compared_value }}')]
    private ?\DateTimeInterface $retreatedDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedDate = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getNbPage(): int
    {
        return $this->nbPage;
    }

    public function setNbPage(int $nbPage): void
    {
        $this->nbPage = $nbPage;
    }

    public function getPublishedDate(): ?\DateTimeInterface
    {
        return $this->publishedDate;
    }

    public function setPublishedDate(?\DateTimeInterface $publishedDate): static
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    public function getRetreatedDate(): ?\DateTimeInterface
    {
        return $this->retreatedDate;
    }

    public function setRetreatedDate(?\DateTimeInterface $retreatedDate): static
    {
        $this->retreatedDate = $retreatedDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(?\DateTimeInterface $updatedDate): static
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

}