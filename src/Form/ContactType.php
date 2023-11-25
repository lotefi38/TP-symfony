<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		// dd($options['data']->getId());

		$builder
			->add('subject', TextType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Subject is required',
					]),
				],
			])
			->add('email', EmailType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Email is required',
					]),
					new Email([
						'message' => 'Email should be ex (yourname@domaine.com)'
					]),
				],
			])
			->add('message', TextareaType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Message is required',
					]),
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Contact::class,
		]);
	}
}
