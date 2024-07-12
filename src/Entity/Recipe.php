<?php

namespace App\Entity;

use App\Entity\User;
use Vich\UploadableField;
use App\Validator\BanWord;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[UniqueEntity('title')]
#[UniqueEntity('slug') ]
#[Vich\Uploadable]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(min: 2)]
    #[ORM\Column(length: 255)]
    #[BanWord()]
    private ?string $title = null;

    #[Assert\Length(min: 2)]
    #[Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Ceci n\'est pas un slug valable')]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 5)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Assert\NotBlank()]
    #[Assert\Positive()]
    #[Assert\LessThan(value: 200)]
    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recipeFilename = null;

    #[Vich\UploadableField(mapping: 'recipes', fileNameProperty: 'recipeFilename')] //UploadableField à un constructeur avec des propièté mapping: indiqué dans vich_uploader.yaml, filenameProperty: la propriété du fichier dans la classe
    #[Assert\Image()]
    private ?File $recipeFile = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?User $user = null;

    // /**
    //  * @var Collection<int, User>
    //  */
    // #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'recipeLikes')]
    // private Collection $userLikes;

    /**
     * @var Collection<int, RecipeLike>
     */
    #[ORM\OneToMany(targetEntity: RecipeLike::class, mappedBy: 'recipe')]
    private Collection $likes;

    /**
     * Permet de gérer les dates automatiquement
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        // $this->userLikes = new ArrayCollection();
        $this->likes = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getRecipeFilename(): ?string
    {
        return $this->recipeFilename;
    }

    public function setRecipeFilename(?string $recipeFilename): static
    {
        $this->recipeFilename = $recipeFilename;

        return $this;
    }

    public function getRecipeFile(): ?File
    {
        return $this->recipeFile;
    }

    public function setRecipeFile(?File $recipeFile = null): static
    {
        $this->recipeFile = $recipeFile;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // /**
    //  * @return Collection<int, User>
    //  */
    // public function getUserLikes(): Collection
    // {
    //     return $this->userLikes;
    // }

    // public function addUserLike(User $userLike): static
    // {
    //     if (!$this->userLikes->contains($userLike)) {
    //         $this->userLikes->add($userLike);
    //         $userLike->addRecipeLike($this);
    //     }

    //     return $this;
    // }

    // public function removeUserLike(User $userLike): static
    // {
    //     if ($this->userLikes->removeElement($userLike)) {
    //         $userLike->removeRecipeLike($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, RecipeLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(RecipeLike $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setRecipe($this);
        }

        return $this;
    }

    public function removeLike(RecipeLike $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getRecipe() === $this) {
                $like->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * Permet de savoir si ce recipe a été aimé par un utilisateur
     *
     * @param User $user
     * @return boolean
     */
    public function isLikeByUser(User $user): bool
    {
        
        foreach ($this->likes as $like) {
            if ($like->getUser() == $user) {
                return true;
            }
        }

        return false;
    }
    
}
