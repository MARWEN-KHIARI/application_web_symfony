<?php

namespace Touch3d\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class PartenaireType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array("draft"=>"draft","revision"=>"revision","publish"=>"publish");
        $builder
            ->add('nom')
            ->add('logo')
            ->add('lien', 'url', array(
                'attr' => array(                    
                    'placeholder' => 'http://www.site.com/'                    
                )
            ))
            ->add('matricule')
            ->add('responsable')
            ->add('adresse')
            ->add('ville')
            ->add('poste')
            ->add('pays')
            ->add('tel')
            ->add('fax')
            ->add('email', 'email')
        ->add('status', 'choice', array('choices' => $choices,
            'attr' => array(
                'placeholder' => 'le status de votre produit? {post, draft, revision}',
                'pattern'     => '.{4,}',

            )))
            ->add('envoyer','submit', array('attr' => array('class' => 'btn btn-primary')));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'nom' => array(
                new NotBlank(array('message' => 'Nom ne doit pas être vide.')),
                new Length(array('min' => 3))
            ),
            'logo' => array(
                new NotBlank(array('message' => 'logo ne doit pas être vide.')),
                new Length(array('min' => 3))
            ),
            'email' => array(
                new NotBlank(array('message' => 'logo ne doit pas être vide.')),
                new Length(array('min' => 3)),
                new Email(array('message' => 'Invalid email address.'))
            ),
            'matricule' => array(
                new NotBlank(array('message' => 'logo ne doit pas être vide.')),
                new Length(array('min' => 3))
            ),
            'pays' => array(
                new NotBlank(array('message' => 'logo ne doit pas être vide.')),
                new Length(array('min' => 3))
            )

        ));
        $resolver->setDefaults(array(
            'data_class' => 'Touch3d\AdminBundle\Entity\Partenaire',
            'constraints' => $collectionConstraint
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'touch3d_adminbundle_partenaire';
    }
}
