<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\Users;
use App\Repository\RolesRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersType extends AbstractType
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $encoder = $this->encoder;
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('mail', EmailType::class)
            ->add('password',PasswordType::class)
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use($encoder){
                /** @var Users */
                $user = $event->getData();
                $form = $event->getForm();
                if ($user) {
                    $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                    /** @var Roles */
                    foreach ($user->getUserRoles()->toArray() as $role) {
                        $role->addUser($user);
                    }
                }})
            ->add('userRoles', EntityType::class, ['class' => Roles::class , 'multiple' => true, 'expanded' => true, 'choice_label' => 'name', 'choice_value' => 'id', ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
