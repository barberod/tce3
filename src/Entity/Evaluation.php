<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $serialNum = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $requester = null;

    #[ORM\Column(length: 6)]
    private ?string $reqAdmin = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?Institution $institution = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $institutionOther = null;

    #[ORM\Column(length: 90, nullable: true)]
    private ?string $institutionCountry = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $courseSubjCode = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $courseCrseNum = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $courseTitle = null;

    #[ORM\Column(length: 24, nullable: true)]
    private ?string $courseTerm = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $courseCreditHrs = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $courseCreditBasis = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $labSubjCode = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $labCrseNum = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $labTitle = null;

    #[ORM\Column(length: 24, nullable: true)]
    private ?string $labTerm = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $labCreditHrs = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $labCreditBasis = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $phase = null;

    #[ORM\Column(options: ["default" => 1])]
    private ?int $status = 1;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated = null;

    #[ORM\ManyToOne(inversedBy: 'assignedEvaluations')]
    private ?User $assignee = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $draftEquiv1Course = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $draftEquiv1CreditHrs = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $draftEquiv1Operator = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $draftEquiv2Course = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $draftEquiv2CreditHrs = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $draftEquiv2Operator = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $draftEquiv3Course = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $draftEquiv3CreditHrs = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $draftEquiv3Operator = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $draftEquiv4Course = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $draftEquiv4CreditHours = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $draftPolicy = null;

    #[ORM\ManyToOne(inversedBy: 'finalEquiv1Course')]
    private ?Course $finalEquiv1Course = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $finalEquiv1CreditHrs = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $finalEquiv1Operator = null;

    #[ORM\ManyToOne(inversedBy: 'finalEquiv2Course')]
    private ?Course $finalEquiv2Course = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $finalEquiv2CreditHrs = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $finalEquiv2Operator = null;

    #[ORM\ManyToOne(inversedBy: 'finalEquiv3Course')]
    private ?Course $finalEquiv3Course = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $finalEquiv3CreditHrs = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $finalEquiv3Operator = null;

    #[ORM\ManyToOne(inversedBy: 'finalEquiv4Course')]
    private ?Course $finalEquiv4Course = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $finalEquiv4CreditHrs = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $finalPolicy = null;

    #[ORM\Column(length: 24, nullable: true)]
    private ?string $requesterType = null;

    #[ORM\Column(nullable: true)]
    private ?int $courseInSis = null;

    #[ORM\Column(nullable: true)]
    private ?int $transcriptOnHand = null;

    #[ORM\Column(nullable: true)]
    private ?int $holdForRequesterAdmit = null;

    #[ORM\Column(nullable: true)]
    private ?int $holdForCourseInput = null;

    #[ORM\Column(nullable: true)]
    private ?int $holdForTranscript = null;

    #[ORM\Column(nullable: true)]
    private ?int $tagSpotArticulated = null;

    #[ORM\Column(nullable: true)]
    private ?int $tagR1ToStudent = null;

    #[ORM\Column(nullable: true)]
    private ?int $tagDeptToStudent = null;

    #[ORM\Column(nullable: true)]
    private ?int $tagDeptToR1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $tagR2ToStudent = null;

    #[ORM\Column(nullable: true)]
    private ?int $tagR2ToDept = null;

    #[ORM\Column(nullable: true)]
    private ?int $tagReassigned = null;

    #[ORM\Column(nullable: true)]
    private ?int $d7Nid = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $d7Uuid = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $loadedFrom = null;

    #[ORM\OneToMany(mappedBy: 'evaluation', targetEntity: Trail::class)]
    #[ORM\OrderBy(['created' => 'DESC'])]
    private Collection $trails;

    #[ORM\OneToMany(mappedBy: 'evaluation', targetEntity: Note::class)]
    #[ORM\OrderBy(['created' => 'DESC'])]
    private Collection $notes;

    public function __construct()
    {
        $this->trails = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerialNum(): ?int
    {
        return $this->serialNum;
    }

    public function setSerialNum(int $serialNum): static
    {
        $this->serialNum = $serialNum;

        return $this;
    }

    public function getRequester(): ?User
    {
        return $this->requester;
    }

    public function setRequester(?User $requester): static
    {
        $this->requester = $requester;

        return $this;
    }

    public function getReqAdmin(): ?string
    {
        return $this->reqAdmin;
    }

    public function setReqAdmin(string $reqAdmin): static
    {
        $this->reqAdmin = $reqAdmin;

        return $this;
    }

    public function getInstitution(): ?Institution
    {
        return $this->institution;
    }

    public function setInstitution(?Institution $institution): static
    {
        $this->institution = $institution;

        return $this;
    }

    public function getInstitutionOther(): ?string
    {
        return $this->institutionOther;
    }

    public function setInstitutionOther(?string $institutionOther): static
    {
        $this->institutionOther = $institutionOther;

        return $this;
    }

    public function getInstitutionCountry(): ?string
    {
        return $this->institutionCountry;
    }

    public function setInstitutionCountry(?string $institutionCountry): static
    {
        $this->institutionCountry = $institutionCountry;

        return $this;
    }

    public function getCourseSubjCode(): ?string
    {
        return $this->courseSubjCode;
    }

    public function setCourseSubjCode(?string $courseSubjCode): static
    {
        $this->courseSubjCode = $courseSubjCode;

        return $this;
    }

    public function getCourseCrseNum(): ?string
    {
        return $this->courseCrseNum;
    }

    public function setCourseCrseNum(?string $courseCrseNum): static
    {
        $this->courseCrseNum = $courseCrseNum;

        return $this;
    }

    public function getCourseTitle(): ?string
    {
        return $this->courseTitle;
    }

    public function setCourseTitle(?string $courseTitle): static
    {
        $this->courseTitle = $courseTitle;

        return $this;
    }

    public function getCourseTerm(): ?string
    {
        return $this->courseTerm;
    }

    public function setCourseTerm(?string $courseTerm): static
    {
        $this->courseTerm = $courseTerm;

        return $this;
    }

    public function getCourseCreditHrs(): ?string
    {
        return $this->courseCreditHrs;
    }

    public function setCourseCreditHrs(?string $courseCreditHrs): static
    {
        $this->courseCreditHrs = $courseCreditHrs;

        return $this;
    }

    public function getCourseCreditBasis(): ?string
    {
        return $this->courseCreditBasis;
    }

    public function setCourseCreditBasis(?string $courseCreditBasis): static
    {
        $this->courseCreditBasis = $courseCreditBasis;

        return $this;
    }

    public function getLabSubjCode(): ?string
    {
        return $this->labSubjCode;
    }

    public function setLabSubjCode(string $labSubjCode): static
    {
        $this->labSubjCode = $labSubjCode;

        return $this;
    }

    public function getLabCrseNum(): ?string
    {
        return $this->labCrseNum;
    }

    public function setLabCrseNum(?string $labCrseNum): static
    {
        $this->labCrseNum = $labCrseNum;

        return $this;
    }

    public function getLabTitle(): ?string
    {
        return $this->labTitle;
    }

    public function setLabTitle(?string $labTitle): static
    {
        $this->labTitle = $labTitle;

        return $this;
    }

    public function getLabTerm(): ?string
    {
        return $this->labTerm;
    }

    public function setLabTerm(?string $labTerm): static
    {
        $this->labTerm = $labTerm;

        return $this;
    }

    public function getLabCreditHrs(): ?string
    {
        return $this->labCreditHrs;
    }

    // 15
    public function setLabCreditHrs(?string $labCreditHrs): static
    {
        $this->labCreditHrs = $labCreditHrs;

        return $this;
    }

    public function getLabCreditBasis(): ?string
    {
        return $this->labCreditBasis;
    }

    public function setLabCreditBasis(?string $labCreditBasis): static
    {
        $this->labCreditBasis = $labCreditBasis;

        return $this;
    }

    public function getPhase(): ?string
    {
        return $this->phase;
    }

    public function setPhase(string $phase): static
    {
        $this->phase = $phase;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): static
    {
        $this->updated = $updated;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    // 20
    public function setAssignee(?User $assignee): static
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function getDraftEquiv1Course(): ?string
    {
        return $this->draftEquiv1Course;
    }

    public function setDraftEquiv1Course(?string $draftEquiv1Course): static
    {
        $this->draftEquiv1Course = $draftEquiv1Course;

        return $this;
    }

    public function getDraftEquiv1CreditHrs(): ?string
    {
        return $this->draftEquiv1CreditHrs;
    }

    public function setDraftEquiv1CreditHrs(?string $draftEquiv1CreditHrs): static
    {
        $this->draftEquiv1CreditHrs = $draftEquiv1CreditHrs;

        return $this;
    }

    public function getDraftEquiv1Operator(): ?string
    {
        return $this->draftEquiv1Operator;
    }

    public function setDraftEquiv1Operator(?string $draftEquiv1Operator): static
    {
        $this->draftEquiv1Operator = $draftEquiv1Operator;

        return $this;
    }

    public function getDraftEquiv2Course(): ?string
    {
        return $this->draftEquiv2Course;
    }

    public function setDraftEquiv2Course(?string $draftEquiv2Course): static
    {
        $this->draftEquiv2Course = $draftEquiv2Course;

        return $this;
    }

    public function getDraftEquiv2CreditHrs(): ?string
    {
        return $this->draftEquiv2CreditHrs;
    }

    // 25
    public function setDraftEquiv2CreditHrs(?string $draftEquiv2CreditHrs): static
    {
        $this->draftEquiv2CreditHrs = $draftEquiv2CreditHrs;

        return $this;
    }

    public function getDraftEquiv2Operator(): ?string
    {
        return $this->draftEquiv2Operator;
    }

    public function setDraftEquiv2Operator(?string $draftEquiv2Operator): static
    {
        $this->draftEquiv2Operator = $draftEquiv2Operator;

        return $this;
    }

    public function getDraftEquiv3Course(): ?string
    {
        return $this->draftEquiv3Course;
    }

    public function setDraftEquiv3Course(?string $draftEquiv3Course): static
    {
        $this->draftEquiv3Course = $draftEquiv3Course;

        return $this;
    }

    public function getDraftEquiv3CreditHrs(): ?string
    {
        return $this->draftEquiv3CreditHrs;
    }

    public function setDraftEquiv3CreditHrs(?string $draftEquiv3CreditHrs): static
    {
        $this->draftEquiv3CreditHrs = $draftEquiv3CreditHrs;

        return $this;
    }

    public function getDraftEquiv3Operator(): ?string
    {
        return $this->draftEquiv3Operator;
    }

    public function setDraftEquiv3Operator(?string $draftEquiv3Operator): static
    {
        $this->draftEquiv3Operator = $draftEquiv3Operator;

        return $this;
    }

    public function getDraftEquiv4Course(): ?string
    {
        return $this->draftEquiv4Course;
    }

    // 30
    public function setDraftEquiv4Course(?string $draftEquiv4Course): static
    {
        $this->draftEquiv4Course = $draftEquiv4Course;

        return $this;
    }

    public function getDraftEquiv4CreditHours(): ?string
    {
        return $this->draftEquiv4CreditHours;
    }

    public function setDraftEquiv4CreditHours(?string $draftEquiv4CreditHours): static
    {
        $this->draftEquiv4CreditHours = $draftEquiv4CreditHours;

        return $this;
    }

    public function getDraftPolicy(): ?string
    {
        return $this->draftPolicy;
    }

    public function setDraftPolicy(?string $draftPolicy): static
    {
        $this->draftPolicy = $draftPolicy;

        return $this;
    }

    public function getFinalEquiv1Course(): ?Course
    {
        return $this->finalEquiv1Course;
    }

    public function setFinalEquiv1Course(?Course $finalEquiv1Course): static
    {
        $this->finalEquiv1Course = $finalEquiv1Course;

        return $this;
    }

    public function getFinalEquiv1CreditHrs(): ?string
    {
        return $this->finalEquiv1CreditHrs;
    }

    public function setFinalEquiv1CreditHrs(?string $finalEquiv1CreditHrs): static
    {
        $this->finalEquiv1CreditHrs = $finalEquiv1CreditHrs;

        return $this;
    }

    public function getFinalEquiv1Operator(): ?string
    {
        return $this->finalEquiv1Operator;
    }

    // 35
    public function setFinalEquiv1Operator(?string $finalEquiv1Operator): static
    {
        $this->finalEquiv1Operator = $finalEquiv1Operator;

        return $this;
    }

    public function getFinalEquiv2Course(): ?Course
    {
        return $this->finalEquiv2Course;
    }

    public function setFinalEquiv2Course(?Course $finalEquiv2Course): static
    {
        $this->finalEquiv2Course = $finalEquiv2Course;

        return $this;
    }

    public function getFinalEquiv2CreditHrs(): ?string
    {
        return $this->finalEquiv2CreditHrs;
    }

    public function setFinalEquiv2CreditHrs(?string $finalEquiv2CreditHrs): static
    {
        $this->finalEquiv2CreditHrs = $finalEquiv2CreditHrs;

        return $this;
    }

    public function getFinalEquiv2Operator(): ?string
    {
        return $this->finalEquiv2Operator;
    }

    public function setFinalEquiv2Operator(?string $finalEquiv2Operator): static
    {
        $this->finalEquiv2Operator = $finalEquiv2Operator;

        return $this;
    }

    public function getFinalEquiv3Course(): ?Course
    {
        return $this->finalEquiv3Course;
    }

    public function setFinalEquiv3Course(?Course $finalEquiv3Course): static
    {
        $this->finalEquiv3Course = $finalEquiv3Course;

        return $this;
    }

    public function getFinalEquiv3CreditHrs(): ?string
    {
        return $this->finalEquiv3CreditHrs;
    }

    // 40
    public function setFinalEquiv3CreditHrs(?string $finalEquiv3CreditHrs): static
    {
        $this->finalEquiv3CreditHrs = $finalEquiv3CreditHrs;

        return $this;
    }

    public function getFinalEquiv3Operator(): ?string
    {
        return $this->finalEquiv3Operator;
    }

    public function setFinalEquiv3Operator(?string $finalEquiv3Operator): static
    {
        $this->finalEquiv3Operator = $finalEquiv3Operator;

        return $this;
    }

    public function getFinalEquiv4Course(): ?Course
    {
        return $this->finalEquiv4Course;
    }

    public function setFinalEquiv4Course(?Course $finalEquiv4Course): static
    {
        $this->finalEquiv4Course = $finalEquiv4Course;

        return $this;
    }

    public function getFinalEquiv4CreditHrs(): ?string
    {
        return $this->finalEquiv4CreditHrs;
    }

    public function setFinalEquiv4CreditHrs(?string $finalEquiv4CreditHrs): static
    {
        $this->finalEquiv4CreditHrs = $finalEquiv4CreditHrs;

        return $this;
    }

    public function getFinalPolicy(): ?string
    {
        return $this->finalPolicy;
    }

    public function setFinalPolicy(?string $finalPolicy): static
    {
        $this->finalPolicy = $finalPolicy;

        return $this;
    }

    // 45
    public function getRequesterType(): ?string
    {
        return $this->requesterType;
    }

    public function setRequesterType(?string $requesterType): static
    {
        $this->requesterType = $requesterType;

        return $this;
    }

    public function getCourseInSis(): ?int
    {
        return $this->courseInSis;
    }

    public function setCourseInSis(?int $courseInSis): static
    {
        $this->courseInSis = $courseInSis;

        return $this;
    }

    public function getTranscriptOnHand(): ?int
    {
        return $this->transcriptOnHand;
    }

    public function setTranscriptOnHand(?int $transcriptOnHand): static
    {
        $this->transcriptOnHand = $transcriptOnHand;

        return $this;
    }

    public function getHoldForRequesterAdmit(): ?int
    {
        return $this->holdForRequesterAdmit;
    }

    public function setHoldForRequesterAdmit(?int $holdForRequesterAdmit): static
    {
        $this->holdForRequesterAdmit = $holdForRequesterAdmit;

        return $this;
    }

    public function getHoldForCourseInput(): ?int
    {
        return $this->holdForCourseInput;
    }

    public function setHoldForCourseInput(?int $holdForCourseInput): static
    {
        $this->holdForCourseInput = $holdForCourseInput;

        return $this;
    }

    public function getHoldForTranscript(): ?int
    {
        return $this->holdForTranscript;
    }

    // 50
    public function setHoldForTranscript(?int $holdForTranscript): static
    {
        $this->holdForTranscript = $holdForTranscript;

        return $this;
    }

    public function getTagSpotArticulated(): ?int
    {
        return $this->tagSpotArticulated;
    }

    public function setTagSpotArticulated(?int $tagSpotArticulated): static
    {
        $this->tagSpotArticulated = $tagSpotArticulated;

        return $this;
    }

    public function getTagR1ToStudent(): ?int
    {
        return $this->tagR1ToStudent;
    }

    public function setTagR1ToStudent(?int $tagR1ToStudent): static
    {
        $this->tagR1ToStudent = $tagR1ToStudent;

        return $this;
    }

    public function getTagDeptToStudent(): ?int
    {
        return $this->tagDeptToStudent;
    }

    public function setTagDeptToStudent(?int $tagDeptToStudent): static
    {
        $this->tagDeptToStudent = $tagDeptToStudent;

        return $this;
    }

    public function getTagDeptToR1(): ?int
    {
        return $this->tagDeptToR1;
    }

    public function setTagDeptToR1(?int $tagDeptToR1): static
    {
        $this->tagDeptToR1 = $tagDeptToR1;

        return $this;
    }

    public function getTagR2ToStudent(): ?int
    {
        return $this->tagR2ToStudent;
    }

    // 55
    public function setTagR2ToStudent(?int $tagR2ToStudent): static
    {
        $this->tagR2ToStudent = $tagR2ToStudent;

        return $this;
    }

    public function getTagR2ToDept(): ?int
    {
        return $this->tagR2ToDept;
    }

    public function setTagR2ToDept(?int $tagR2ToDept): static
    {
        $this->tagR2ToDept = $tagR2ToDept;

        return $this;
    }

    public function getTagReassigned(): ?int
    {
        return $this->tagReassigned;
    }

    public function setTagReassigned(?int $tagReassigned): static
    {
        $this->tagReassigned = $tagReassigned;

        return $this;
    }

    public function getD7Nid(): ?int
    {
        return $this->d7Nid;
    }

    public function setD7Nid(?int $d7Nid): static
    {
        $this->d7Nid = $d7Nid;

        return $this;
    }

    public function getD7Uuid(): ?string
    {
        return $this->d7Uuid;
    }

    public function setD7Uuid(?string $d7Uuid): static
    {
        $this->d7Uuid = $d7Uuid;

        return $this;
    }

    public function getLoadedFrom(): ?string
    {
        return $this->loadedFrom;
    }

    public function setLoadedFrom(string $loadedFrom): static
    {
        $this->loadedFrom = $loadedFrom;

        return $this;
    }

    /**
     * @return Collection<int, Trail>
     */
    public function getTrails(): Collection
    {
        return $this->trails;
    }

    public function addTrail(Trail $trail): static
    {
        if (!$this->trails->contains($trail)) {
            $this->trails->add($trail);
            $trail->setEvaluation($this);
        }

        return $this;
    }

    public function removeTrail(Trail $trail): static
    {
        if ($this->trails->removeElement($trail)) {
            // set the owning side to null (unless already changed)
            if ($trail->getEvaluation() === $this) {
                $trail->setEvaluation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setEvaluation($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEvaluation() === $this) {
                $note->setEvaluation(null);
            }
        }

        return $this;
    }
}
