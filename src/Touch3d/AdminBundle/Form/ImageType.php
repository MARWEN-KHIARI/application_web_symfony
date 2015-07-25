<?php
namespace Touch3d\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alt', 'text', array('label' => false,
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Texte alternatif de l\'image ..',
                    'pattern'     => '.{2,}' //minlength
                )
            ))
            ->add('legend', 'text', array('label' => false,
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Légende de l\'image ..',
                    'pattern'     => '.{2,}' //minlength
                )
            ))

            ->add('file', 'file', array('label' => false,
                'attr' => array(
                    'class' => 'fileinput'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Touch3d\AdminBundle\Entity\Image'
        ));
    }

  public function getName()
  {
    return 'touch3d_adminbundle_imagetype';
  }
}