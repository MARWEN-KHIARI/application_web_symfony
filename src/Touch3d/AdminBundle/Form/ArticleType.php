<?php

namespace Touch3d\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array("draft"=>"draft","revision"=>"revision","publish"=>"publish");
        $builder

            ->add('nom', 'text', array('label' => 'Titre de l\'évènement: ',
                'attr' => array(
                    'placeholder' => 'Quel est le nom de l article ?',
                    'pattern'     => '.{2,}' //minlength
                )
            ))
            ->add('img', 'text', array('label' => 'Image: ',
                'attr' => array(
                    'placeholder' => 'URL de l\'Image',
                ),'required'  => false,
            ))

            ->add('date','date', array('label' => 'Date de l\'évènement: ','label_attr' => array('class' => '')))

            ->add('lieu','text', array('label' => 'Lieu de l\'évènement: ','label_attr' => array('class' => ''),
                'attr' => array(
                    'placeholder' => 'Le lieu de votre évènement ici...',
                    'pattern'     => '.{3,}'
                )
            ))
            ->add('resumer', 'textarea', array('label' => 'Resumer de l\'évènement: ','label_attr' => array('class' => ''),
                'attr' => array(
                    'cols' => 20,
                    'rows' => 5,
                    'placeholder' => 'Le resumer de votre contenu ici...',
                    'pattern'     => '.{3,}' //minlength
                )
            ))
            ->add('contenu', 'textarea', array('label' => 'Contenu de l\'évènement: ','label_attr' => array('class' => ''),
                'attr' => array(
                    'cols' => 20,
                    'rows' => 5,
                    'placeholder' => 'Votre texte ici...'
                )
            ))
            ->add('status', 'choice', array('label' => 'Status de l\'article: ','choices' => $choices,
                'attr' => array(
                    'placeholder' => 'le status de votre article? {post, draft, revision}',
                    'pattern'     => '.{4,}',

                )
            ))
            ->add('envoyer','button', array('attr' => array('class' => 'btn btn-primary')));
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
            'data_class' => 'Touch3d\AdminBundle\Entity\Article',
            'constraints' => $collectionConstraint
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'touch3d_adminbundle_article';
    }
}
