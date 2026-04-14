<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\ProjectStatus;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

class ProjectCrudController extends AbstractCrudController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $approveAction = Action::new('approve', 'Approuver', 'fa fa-check-circle')
            ->linkToCrudAction('approveProject')
            ->addCssClass('btn btn-success')
            ->displayIf(static function (Project $project) {
                // On affiche seulement si le statut actuel est "Pending"
                return $project->getStatus()?->getStatusName() === 'Pending';
            });

        return $actions
            ->add(Crud::PAGE_INDEX, $approveAction)
            ->add(Crud::PAGE_DETAIL, $approveAction)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Créer un projet');
            });
    }

    /**
     * Logique pour approuver le projet
     */
    public function approveProject(AdminContext $context, EntityManagerInterface $em): Response
    {
        /** @var Project $project */
        $project = $context->getEntity()->getInstance();
        
        // IMPORTANT : Vérifie que le nom 'Approved' est identique à tes Fixtures
        $approvedStatus = $em->getRepository(ProjectStatus::class)->findOneBy(['status_name' => 'Approved']);

        if ($approvedStatus) {
            $project->setStatus($approvedStatus);
            
            // On valide le budget demandé vers le budget alloué
            if ($project->getRequestedBudget()) {
                $project->setAllocatedBudget($project->getRequestedBudget());
            }
            
            $em->persist($project);
            $em->flush();
            
            $this->addFlash('success', 'Le projet "' . $project->getName() . '" a été approuvé avec succès !');
        } else {
            $this->addFlash('danger', 'Erreur : Statut "Approved" non trouvé en base de données.');
        }

        // On force la redirection vers la liste des projets de ce contrôleur
        $url = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::INDEX)
            ->setEntityId(null)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom du projet'),
            
            MoneyField::new('requested_budget', 'Budget demandé')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),
                
            MoneyField::new('allocated_budget', 'Budget alloué')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            DateTimeField::new('creation_date', 'Date de création')
                ->hideOnForm(),

            AssociationField::new('status', 'Statut actuel'),
            
            AssociationField::new('requester', 'Demandeurs (Users)')
                ->setFormTypeOption('by_reference', false),
            
            TextField::new('illustration_path', 'Chemin de l\'image'),
        ];
    }
}