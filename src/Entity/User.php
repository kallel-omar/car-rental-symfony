<?php

namespace App\Entity;

use App\Repository\UserRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(
    fields: ['email'],
    message: 'There is already an account with this email'
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'Please enter a valid email address.'
    )]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $isBlocked = false;


    /**
     * @var list<string>
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    // PROFILE FIELDS

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cinImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $licenseImage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $licenseIssueDate = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(
        targetEntity: Reservation::class,
        mappedBy: 'user'
    )]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes.
     */
    public function __serialize(): array
    {
        $data = (array) $this;

        $data["\0" . self::class . "\0password"] =
            hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // deprecated
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    // PROFILE GETTERS / SETTERS

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCinImage(): ?string
    {
        return $this->cinImage;
    }

    public function setCinImage(?string $cinImage): static
    {
        $this->cinImage = $cinImage;

        return $this;
    }

    public function getLicenseImage(): ?string
    {
        return $this->licenseImage;
    }

    public function setLicenseImage(?string $licenseImage): static
    {
        $this->licenseImage = $licenseImage;

        return $this;
    }

    public function getLicenseIssueDate(): ?\DateTimeInterface
    {
        return $this->licenseIssueDate;
    }

    public function setLicenseIssueDate(
        ?\DateTimeInterface $licenseIssueDate
    ): static {

        $this->licenseIssueDate = $licenseIssueDate;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(
        Reservation $reservation
    ): static {

        if (!$this->reservations->contains($reservation)) {

            $this->reservations->add($reservation);

            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(
        Reservation $reservation
    ): static {

        if ($this->reservations->removeElement($reservation)) {

            if ($reservation->getUser() === $this) {

                $reservation->setUser(null);
            }
        }

        return $this;
    }
    public function isBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): static
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }
}
