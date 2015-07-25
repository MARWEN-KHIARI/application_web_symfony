<?php

namespace Touch3d\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ProduitType extends AbstractType
{
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array("draft"=>"draft","revision"=>"revision","publish"=>"publish");
        $builder
            ->add('nom', 'text', array('label' => 'Nom: ',
                'attr' => array(
                    'placeholder' => 'Quel est le nom du produit?',
                    'pattern'     => '.{2,}' //minlength
                )
            ))
            ->add('prix', 'number', array('label' => 'Prix: ',
                'attr' => array(
                    'placeholder' => 'Le prix du produit ici',
                    'pattern'     => '.{1,}',
                    'step'     => '0.05'
                )
            ))

            ->add('qteStock', 'number', array('label' => 'Quantité en stock: ',
                'attr' => array(
                    'placeholder' => 'Quantité en stock du produit ici',
                    'pattern'     => '.{1,}',
                    'step'     => '10'
                )
            ))
            ->add('img', 'text', array('label' => 'Image: ',
                'attr' => array(
                    'placeholder' => 'Pour vous répondre.'
                )
            ))
            ->add('resumer', 'textarea', array('label' => 'Resumer: ','label_attr' => array('class' => ''),
                'attr' => array(
                    'cols' => 20,
                    'rows' => 5,
                    'placeholder' => 'Le resumer de votre contenu ici...',
                    'pattern'     => '.{3,}' //minlength
                )
            ))
            ->add('contenu', 'textarea', array('label' => 'Contenu: ','label_attr' => array('class' => ''),
                'attr' => array(
                    'cols' => 20,
                    'rows' => 5,
                    'placeholder' => 'Votre texte ici...'
                )
            ))
            ->add('status', 'choice', array('label' => 'Status du produit: ','choices' => $choices,
                'attr' => array(
                    'placeholder' => 'le status de votre produit? {post, draft, revision}',
                    'pattern'     => '.{4,}',

                )
            ))
            ->add('categorie',  'entity',        array(
                'class'    => 'Touch3dAdminBundle:Categorie',
                'property' => 'nom',
                'multiple' => false
            ))
            ->add('fournisseur',  'entity',        array(
                'class'    => 'Touch3dAdminBundle:Fournisseur',
                'property' => 'nom',
                'multiple' => false
            ))
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
                new Length(array('min' => 2))
            ),
            'resumer' => array(
                new NotBlank(array('message' => 'resumer ne doit pas être vide.')),
                new Length(array('min' => 3))
            ),
            'contenu' => array(
                new NotBlank(array('message' => 'contenu ne doit pas être vide.')),
                new Length(array('min' => 5))
            ),
             'status' => array(
                new NotBlank(array('message' => 'status ne doit pas être vide.')),
                new Length(array('min' => 4))
             )
        ));
        $resolver->setDefaults(array(
            'data_class' => 'Touch3d\AdminBundle\Entity\Produit',
            'constraints' => $collectionConstraint
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'touch3d_adminbundle_produit';
    }
}

