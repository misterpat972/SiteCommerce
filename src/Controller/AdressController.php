<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdressController extends AbstractController
{
    // function pour afficher les adresses du compte
    #[Route('/compte/adresses', name: 'app_adress')]
    public function index(AddressRepository $addressRepository): Response
    {   
       $addresse = $addressRepository->findBy(['user' => $this->getUser()]);
        
        return $this->render('account/address.html.twig',[                
                'addresse' => $addresse,            
        ]);
        
    }

    // function pour ajouter une adresse au compte        
    #[Route('/compte/ajouter-une-adresse', name: 'add_adress')]
    public function addAdresse(Request $request, EntityManagerInterface $entityManager): Response
    {   
        // on crée une nouvelle adresse
        $address = new Address();
        // on crée le formulaire 
        $form = $this->createForm(AddressType::class, $address);
        // on récupère les données du formulaire 
        $form->handleRequest($request);
        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère les données du formulaire
            $address = $form->getData();
            // on lie l'adresse à l'utilisateur connecté
            $address->setUser($this->getUser()); 
            // on fige les données             
            $entityManager->persist($address);
            // on envoie les données en base de données
            $entityManager->flush();
            // on redirige vers la page des adresses du compte
            return $this->redirectToRoute('app_adress');
        }
        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);        

    }


    // function pour modifier une adresse du compte 
    #[Route('/compte/modifier-une-adresse/{id}', name: 'edit_adress')]
    public function editAdresse(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
       
        $address = $entityManager->getRepository(Address::class)->find($id);
        // on vérifie que l'adresse existe et qu'elle appartient à l'utilisateur connecté
        if (!$address || $address->getUser() != $this->getUser()) {
           $this->redirectToRoute('app_adress');            
        }
        // on crée le formulaire
        $form = $this->createForm(AddressType::class, $address);
        // on récupère les données du formulaire        
        $form->handleRequest($request);
        // si le formulaire est soumis et valide 
        if ($form->isSubmitted() && $form->isValid()) {            
            // on envoie les données en base de données
            $entityManager->flush();
            // on ajoute un message flash
            $this->addFlash('success', 'Votre adresse a bien été modifiée');
            // on redirige vers la page des adresses du compte
            return $this->redirectToRoute('app_adress');
        }
        
        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    // function pour supprimer une adresse du compte
    #[route('/compte/supprimer-une-adresse/{id}', name: 'delete_adress')]
    public function deleteAdresse(EntityManagerInterface $entityManager, $id): Response
    {
        // on supprime l'adresse
        $address = $entityManager->getRepository(Address::class)->find($id);
        // on vérifie que l'adresse existe et qu'elle appartient à l'utilisateur connecté
        if (!$address || $address->getUser() != $this->getUser()) {
            $this->redirectToRoute('app_adress');            
        }
        // on supprime l'adresse
        $entityManager->remove($address);        
        // on envoie les données en base de données
        $entityManager->flush();
        // on ajoute un message flash
        $this->addFlash('success', 'Votre adresse a bien été supprimée');
        // on redirige vers la page des adresses du compte
        return $this->redirectToRoute('app_adress');
    }
}
