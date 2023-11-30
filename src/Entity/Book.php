<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert; // we need this for the validation

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[UniqueEntity(fields: ['isbn'], message: 'There is already a Book with this ISBN')]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Your title must be at least {{ limit }} characters long',
        maxMessage: 'Your title cannot be longer than {{ limit }} characters'
    )]
    #[Assert\NotBlank(message: 'Please enter a title')]
    private ?string $title = null;

    #[ORM\Column(length: 13)]
    // If the ISBN is 10 digits long, this total must be divisible by 11. If it is 13 digits long, the total must be divisible by 10.
    // https://en.wikipedia.org/wiki/International_Standard_Book_Number#ISBN-10_check_digits
    #[Assert\Length(
        min: 10,
        max: 13,
        minMessage: 'Your ISBN must be at least {{ limit }} characters long',
        maxMessage: 'Your ISBN cannot be longer than {{ limit }} characters'
    )]
    #[Assert\NotBlank(message: 'Please enter an ISBN')]
    private ?string $isbn = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'books')]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: true)] // nullable: true because we want to be able to create a Book without an author (for example, if we don't know the author yet)
    private ?Author $author = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

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

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function getCategoriesArray(): array
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->getTitle();
        }
        return $categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addBook($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeBook($this);
        }

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }
}
