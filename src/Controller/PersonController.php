<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Form\PersonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/person/', name: 'app_person')]
    public function index(): Response
    {
        return $this->render('person/index.html.twig', [
            'controller_name' => 'PersonController',
        ]);
    }


    // TODO : Voir doc : https://symfony.com/doc/current/forms.html
    #[Route('/person/create', name: 'create_person')]
    public function createPerson(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // $date_anniversaire = new \DateTime('10-08-2013');
        // $person = new Person();
        // $person->setNom('regnier');
        // $person->setPrenom('sylvain');
        // $person->setVille('Marseille');
        // $person->setAge('20');
        // $person->setDateAnniversaire($date_anniversaire);

        // //persist prepare les donnée de l objet ds l orm

        // $entityManager->persist($person);

        // // flush va inserer les donnée ds la bdd

        // $entityManager->flush();

       // return new Response('Saved new person with id ' . $person->getId());
        $person = new Person();

        $form = $this->createForm(PersonType::class, $person);
      
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();

            $entityManager->persist($person);
            $entityManager->flush();


            return $this->redirectToRoute('list_person');
        }

       return $this->render('person/create.html.twig', [
        'form' => $form->createView()
    ]);


    }

    #[Route('/persons/list', name: 'list_person', methods: ['GET'])]

    public function personList(PersonRepository $personRepository): Response
    {
        return $this->render('person/all_persons.html.twig', ['persons' => $personRepository->findAll()]);
    }

    #[Route('/person/show/{id}', name: 'person_show', methods: ['GET'])]
    public function personShow(int $id, PersonRepository $personRepository): Response
    {
        $person = $personRepository->find($id);

        if (!$person) {
            throw $this->createNotFoundException('pas de personne' . $id);
        }

        $form = $this->createForm(UserType::class, $person);

        return $this->render('person/show.html.twig', [
            'person' => $personRepository->find($id),
            'form' => $form->createView()
        
        
        ]);
    }




    #[Route('/personne/{id}/delete', name: 'person_delete')]
    public function delete(int $id, Person $personne, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($personne);
        $entityManager->flush();

        return $this->redirectToRoute('app_all_persons', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/person/{id}/edit/request', name: 'person_edit')]
    public function editRequest(int $id, PersonRepository $personRepository, EntityManagerInterface $entityManager)
    {
        $person = $personRepository->find($id);
        $person->setNom($_POST['nom']);
        $person->setPrenom($_POST['prenom']);
        $person->setVille($_POST['ville']);
        $person->setAge($_POST['age']);
        $person->setDateAnniversaire(new \DateTime($_POST['date_anniversaire']));

        $entityManager->persist($person);
        $entityManager->flush;
        return $this->redirectToRoute('app_all_persons', [], Response::HTTP_SEE_OTHER);
    }
    // #[Route('/form', name: 'form_user')]
    // public function forme(): Response
    // {
    // $form = $this->createForm(UserType::class);
    // return $this->render('user/formulaire.html.twig', [
    // 'form' => $form->createView()
    // ]);
    // }




    #[Route('/form', name: 'form')]
    public function form(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('/user/formulaire.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
