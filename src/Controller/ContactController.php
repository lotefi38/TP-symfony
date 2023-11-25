<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

class ContactController extends AbstractController
{
	public function __construct(private ContactRepository $contactRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
	{
	}

	#[Route('/contact', name: 'contact.form')]
	public function index(): Response
	{
		return $this->render('/contact/form.html.twig', [
			'products' => $this->contactRepository->findAll(),
		]);
	}

	#[Route('/contact', name: 'contact.form')]
	public function form(int $id = null): Response
	{
		// création d'un formulaire
		$entity = $id ? $this->contactRepository->find($id) : new Contact();
		$type = ContactType::class;


		$form = $this->createForm($type, $entity);

		// récupérer la saisie précédente dans la requête http
		$form->handleRequest($this->requestStack->getMainRequest());

		// si le formulaire est valide et soumis
		if ($form->isSubmitted() && $form->isValid()) {
			// ByteString permet de générer une chaîne de caractères aléatoire
			$filename = ByteString::fromRandom(32)->lower();
			// dd($filename, $entity);

			// dd($entity);
			// insérer dans la base
			$this->entityManager->persist($entity);
			$this->entityManager->flush();

			// message de confirmation
			$message = $id ? 'Product updated' : 'Message successful';

			// message flash : message stocké en session, supprimé suite à son affichage
			$this->addFlash('notice', $message);

			// redirection vers la page d'accueil de l'admin
			return $this->redirectToRoute('contact.form');
		}

		return $this->render('/contact/form.html.twig', [
			'form' => $form->createView(),
		]);
	}
}