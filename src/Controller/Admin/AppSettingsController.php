<?php

namespace App\Controller\Admin;

use App\Settings\AppSettings;
use Jbtronics\SettingsBundle\Form\SettingsFormFactoryInterface;
use Jbtronics\SettingsBundle\Manager\SettingsManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Translation\TranslatableMessage;

class AppSettingsController extends AbstractController
{
    public function __construct(
        private readonly SettingsManagerInterface $settingsManager,
        private readonly SettingsFormFactoryInterface $settingsFormFactory,
    ) {
    }

    #[Route('/admin/settings', name: 'settings')]
    public function settingsForm(Request $request): Response
    {
        // Create a temporary copy of the settings object, which we can modify in the form without breaking anything with invalid data
        $settings = $this->settingsManager->createTemporaryCopy(AppSettings::class);

        // Create a builder for the settings form
        $builder = $this->settingsFormFactory->createSettingsFormBuilder($settings);

        // Add a submit button, so we can save the form
        $builder->add('submit', SubmitType::class);

        // Create the form
        $form = $builder->getForm();

        // Handle the form submission
        $form->handleRequest($request);

        // If the form was submitted and the data is valid, then it
        if ($form->isSubmitted() && $form->isValid()) {
            // Merge the valid data back into the managed instance
            $this->settingsManager->mergeTemporaryCopy($settings);

            // Save the settings to storage
            $this->settingsManager->save();

            $this->addFlash('success', new TranslatableMessage('Settings saved'));
        }

        // Render the form
        return $this->render('admin/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
