<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\Users;
use App\Repository\ProjectRepository;
use App\Repository\ProjectStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectStatusRepository $projectStatusRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function createProject(
        string $name,
        string $requestedBudget,
        string $illustrationPath,
        string $description,
        Users $requester
    ): array {
        if (!$name) {
            throw new BadRequestHttpException('Missing project name');
        }

        $project = new Project();
        $project->setName($name);
        $project->setRequestedBudget($requestedBudget);
        $project->setAllocatedBudget(0);
        $project->setIllustrationPath($illustrationPath);
        $project->setDescription($description);
        $project->setCreationDate(new \DateTime());
        $project->setRequester($requester);

        // Get "Pending" status
        $pendingStatus = $this->projectStatusRepository->findOneBy(['status_name' => 'Pending']);
        if (!$pendingStatus) {
            throw new BadRequestHttpException('Pending status not found');
        }
        $project->setStatus($pendingStatus);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'message' => 'Project created successfully'
        ];
    }

    public function getProjects(
        ?string $status = null,
        ?string $name = null,
        ?string $createdBefore = null,
        ?string $createdAfter = null,
        ?string $requesterName = null,
        ?string $approverName = null
    ): array {
        $query = $this->projectRepository->createQueryBuilder('p');

        if ($status) {
            $query->join('p.status', 's')
                ->andWhere('s.status_name = :status')
                ->setParameter('status', $status);
        }

        if ($name) {
            $query->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($createdBefore) {
            $query->andWhere('p.creation_date <= :createdBefore')
                ->setParameter('createdBefore', new \DateTime($createdBefore));
        }

        if ($createdAfter) {
            $query->andWhere('p.creation_date >= :createdAfter')
                ->setParameter('createdAfter', new \DateTime($createdAfter));
        }

        if ($requesterName) {
            $query->join('p.requester', 'u1')
                ->andWhere('u1.username LIKE :requesterName OR u1.first_name LIKE :requesterName OR u1.last_name LIKE :requesterName')
                ->setParameter('requesterName', '%' . $requesterName . '%');
        }

        if ($approverName) {
            $query->leftJoin('p.approver', 'u2')
                ->andWhere('u2.username LIKE :approverName OR u2.first_name LIKE :approverName OR u2.last_name LIKE :approverName')
                ->setParameter('approverName', '%' . $approverName . '%');
        }

        $projects = $query->getQuery()->getResult();

        return [
            'total' => count($projects),
            'projects' => array_map(fn(Project $p) => $this->projectToArray($p), $projects)
        ];
    }

    public function updateProject(
        int $projectId,
        Users $user,
        ?string $name = null,
        ?string $requestedBudget = null,
        ?string $illustrationPath = null,
        ?string $description = null
    ): array {
        $project = $this->projectRepository->find($projectId);

        if (!$project) {
            throw new NotFoundHttpException('Project not found');
        }

        if ($project->getRequester()->getId() !== $user->getId()) {
            throw new HttpException(403, 'Not the owner');
        }

        // Check if project is already validated
        if ($project->getStatus()->isValidated()) {
            throw new HttpException(422, 'Project already validated');
        }

        if ($name) {
            $project->setName($name);
        }
        if ($requestedBudget) {
            $project->setRequestedBudget($requestedBudget);
        }
        if ($illustrationPath) {
            $project->setIllustrationPath($illustrationPath);
        }
        if ($description) {
            $project->setDescription($description);
        }

        $this->entityManager->flush();

        return ['message' => 'Project updated successfully'];
    }

    public function deleteProject(int $projectId, Users $user): void
    {
        $project = $this->projectRepository->find($projectId);

        if (!$project) {
            throw new NotFoundHttpException('Project not found');
        }

        if ($project->getRequester()->getId() !== $user->getId()) {
            throw new HttpException(403, 'Not the owner');
        }

        // Check if project is already validated
        if ($project->getStatus()->isValidated()) {
            throw new HttpException(422, 'Project already validated');
        }

        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }

    public function joinProject(int $projectId, Users $user): array
    {
        $project = $this->projectRepository->find($projectId);

        if (!$project) {
            throw new NotFoundHttpException('Project not found');
        }

        // Check if project status is not Rejected
        if ($project->getStatus()->getStatusName() === 'Rejected') {
            throw new ConflictHttpException('Cannot join rejected project');
        }

        // Check if user is already an attendee
        if ($project->getAttendees()->contains($user)) {
            throw new ConflictHttpException('Already joined');
        }

        $project->addAttendee($user);
        $this->entityManager->flush();

        return ['message' => 'Joined project successfully'];
    }

    public function getJoinedUsers(int $projectId): array
    {
        $project = $this->projectRepository->find($projectId);

        if (!$project) {
            throw new NotFoundHttpException('Project not found');
        }

        $attendees = $project->getAttendees();

        return [
            'total' => count($attendees),
            'users' => array_map(fn(Users $u) => [
                'id' => $u->getId(),
                'username' => $u->getUsername(),
                'first_name' => $u->getFirstName(),
                'last_name' => $u->getLastName(),
                'email' => $u->getEmail(),
            ], $attendees->toArray())
        ];
    }

    public function getAllStatuses(): array
    {
        $statuses = $this->projectStatusRepository->findAll();

        return array_map(fn($s) => [
            'id' => $s->getId(),
            'status_name' => $s->getStatusName()
        ], $statuses);  
    }

    private function projectToArray(Project $project): array
    {
        return [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
            'requested_budget' => $project->getRequestedBudget(),
            'allocated_budget' => $project->getAllocatedBudget(),
            'illustration_path' => $project->getIllustrationPath(),
            'creation_date' => $project->getCreationDate()?->format('Y-m-d H:i:s'),
            'status' => $project->getStatus()?->getStatusName(),
            'requester' => $project->getRequester()?->getUsername(),
            'approver' => $project->getApprover()?->getUsername(),
        ];
    }

    
}
