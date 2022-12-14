<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Participant;
use App\Form\GroupeType;
use App\Repository\GroupeRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupeController extends AbstractController
{
    /**
     * @Route("/modifierGroupePrive", name="modifierGroupePrive")
     */
    public function modifierGroupePrive(GroupeRepository $repo): Response
    {
        $groupe = $repo->findAll();

        if (!$groupe)
            throw $this->createNotFoundException('Pas de groupe avec cet identifiant');

        return $this->render('groupe/modifierGroupePrive.twig', [
            "groupe" => $groupe
        ]);
    }

    /**
     * @Route("/creerGroupePrive", name="creerGroupePrive")
     */
    public function creerGroupePrive(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error', 'Veuillez vous connecter pour créer un groupe');
            return $this->redirectToRoute('app_login');
        }
        $groupe = new Groupe();
        $groupeForm = $this->createForm(GroupeType::class, $groupe);

        $groupeForm->handleRequest($request);

        if ($groupeForm->isSubmitted() && $groupeForm->isValid()) {

            $groupeReal = $groupe->getParticipants();
            $groupeFalsch = new Groupe();

            $groupeFalsch ->setNom($groupe->getNom());
            $groupeFalsch ->setCreateur($this->getUser());
            for ($i = 0; $i < count($groupeReal); $i++) {
                $groupeFalsch->addParticipant($groupeReal[$i]);
            }

            $entityManager->persist($groupeFalsch);
            $entityManager->flush();

            $this->addFlash('success', 'Groupe privé crée avec succès !');

            return $this->redirectToRoute('main_home', [
                'id'=>$groupe->getId()
            ]);

        }
        return $this->render('groupe/creerGroupePrive.twig', [
            'groupeForm' => $groupeForm->createView()
        ]);
    }

    /**
     * @Route("/groupes", name="mesGroupes")
     */
    public function list(GroupeRepository $repo, Request $request){

            $user = $this->getUser();

            $groupes = $repo->findAllGroupUser($user);
//            $groupes = $repo->findAll();

            return $this-> render('groupe/list.html.twig', [
                "groupes" => $groupes
            ]);
    }

}
