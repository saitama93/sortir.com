<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\SortieRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 * @ApiResource()
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     *@Assert\GreaterThan(propertyPath="dateLimiteInscription", message="La date de début doit être plus éloignée que la date limite d'inscriptions.")
     */
    private $dateHeureDebut;

     /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="dateHeureDebut", message="La date de fin doit être plus éloignée que la date de début.")
     */
    private $dateHeureFin;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\GreaterThan(0,  message="Le nombre d'inscription minimum doit être de 1.")
     */
    private $nbInscriptionsMax;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("now", message="La date de début doit être ultérieure à maintenant !")
     */
    private $dateLimiteInscription;

    /**
     *  @ORM\Column(type="text", nullable=true)
     */
    private $infoSortie;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\OneToOne(targetEntity=Lieu::class, cascade={"persist", "remove"})
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="sorties")
     */
    private $organisateur;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateHeureFin(): ?\DateTimeInterface
    {
        return $this->dateHeureFin;
    }

    public function setDateHeureFin(\DateTimeInterface $dateHeureFin): self
    {
        $this->dateHeureFin = $dateHeureFin;

        return $this;
    }


    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getInfoSortie(): ?string
    {
        return $this->infoSortie;
    }

    public function setInfoSortie(?string $infoSortie): self
    {
        $this->infoSortie = $infoSortie;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getOrganisateur(): ?Utilisateur
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Utilisateur $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    
}
