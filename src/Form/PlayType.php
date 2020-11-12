<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Win;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['players'] as $player) {
            $playerChoices[$player->getName()] = $player;
        }

        if (!empty($playerChoices)) {
            $builder
                ->add('player', EntityType::class, [
                    'class' => Player::class,
                    'choices' => $playerChoices,
                ])->add('submit', SubmitType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver); // TODO: Change the autogenerated stub
        $resolver->setDefaults([
            'data_class' => Win::class,
        ]);
        $resolver->setRequired('players');
    }
}
