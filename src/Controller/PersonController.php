<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/person', name: 'app_person')]
    public function index(): Response
    {
        return $this->render('person/index.html.twig', [
            'controller_name' => 'PersonController',
        ]);
    }

    #[Route('/create_person', name: 'create_person')]
    public function createPerson(EntityManagerInterface $entityManager): Response
    {
        $date_anniversaire = new \DateTime('10-08-2013');
        $person = new Person();
        $person->setNom('tata');
        $person->setPrenom('toto');
        $person->setVille('Marseille');
        $person->setAge('20');
        $person->setDateAnniversaire($date_anniversaire);

        //persist prepare les donnée de l objet ds l orm

        $entityManager->persist($person);

        // flush va inserer les donnée ds la bdd

        $entityManager->flush();

        return new Response('Saved new person with id ' . $person->getId());

    }

    #[Route('/persons', name: 'app_all_persons', methods: ['GET'])]

    public function all_persons(PersonRepository $personRepository): Response
    {
        return $this->render('person/all_persons.html.twig', ['persons' => $personRepository->findAll()]);

    }
    #[Route('/person/{id}', name: 'person_show', methods: ['GET'])]

    public function person_show(int $id, PersonRepository $personRepository): Response
    {
        $person = $personRepository->find($id);

        if (!$person) {
            throw $this->createNotFoundException('pas de personne' . $id);
        }
        return $this->render('person/show.html.twig', ['person' => $personRepository->find($id)]);
    }

    #[Route('/personne/{id}', name: 'person_edit')]
    public function edit(int $id, PersonRepository $persoRepo)
    {
    $personne = $persoRepo->find($id);
    //var_dump($personne);
    return $this->render('person/edit.html.twig', [
    'personne' => $personne,
    ]);
    }

    #[Route('/personne/{id}/delete', name: 'person_delete')]
public function delete(int $id, Person $personne, PersonRepository $persoRepository): Response
{
$persoRepository->remove($personne, true);
$persoRepository->flush;
return $this->redirectToRoute('app_personnes', [], Response::HTTP_SEE_OTHER);
}

   
}
