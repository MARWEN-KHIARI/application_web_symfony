<?php

namespace Touch3d\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Nom: ',
                'attr' => array(
                    'placeholder' => 'Quel est votre nom?',
                    'pattern'     => '.{2,}' //minlength
                )
            ))
            ->add('email', 'email', array('label' => 'Email: ',
                'attr' => array(
                    'placeholder' => 'Pour vous répondre.'
                )
            ))
            ->add('subject', 'text', array('label' => 'Sujet: ',
                'attr' => array(
                    'placeholder' => 'Le sujet de votre message.',
                    'pattern'     => '.{3,}' //minlength
                )
            ))
            ->add('message', 'textarea', array('label' => 'Message: ','label_attr' => array('class' => 'last'),
                'attr' => array(
                    'cols' => 20,
                    'rows' => 5,
                    'placeholder' => 'Votre message à ici...'
                )
            ));
            //->add('envoyer','submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'name' => array(
                new NotBlank(array('message' => 'Nom ne doit pas être vide.')),
                new Length(array('min' => 2))
            ),
            'email' => array(
                new NotBlank(array('message' => 'Email ne doit pas être vide.')),
                new Email(array('message' => 'Invalid email address.'))
            ),
            'subject' => array(
                new NotBlank(array('message' => 'Sujet ne doit pas être vide.')),
                new Length(array('min' => 3))
            ),
            'message' => array(
                new NotBlank(array('message' => 'Message ne doit pas être vide.')),
                new Length(array('min' => 5))
            )
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }

    public function getName()
    {
        return 'contact';
    }
}
