<?php

namespace Touch3d\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Intl\Intl;

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //\Locale::setDefault('en');
        $countries = Intl::getRegionBundle()->getCountryNames();
        //$country = Intl::getRegionBundle()->getCountryName('GB');
        $builder

            ->add('first_name', 'text', array('label' => 'first name: *',
                'attr' => array(
                    'placeholder' => 'John',
                    'pattern' => '.{4,}'
                )
            ))

            ->add('last_name', 'text', array('label' => 'last name: *',
                'attr' => array(
                    'placeholder' => 'Smith',
                    'pattern' => '.{4,}'
                )
            ))
            ->add('business_name', 'text', array('label' => 'business name: ','required' => false,
                'attr' => array(
                    'placeholder' => 'John Smith'
                )
            ))

            ->add('phone', 'text', array('label' => 'phone: *',
                'attr' => array(
                    'placeholder' => '22 33 44 55',
                    'pattern' => '.{4,}'
                )
            ))

            ->add('fax', 'text', array('label' => 'fax: ','required' => false,
                'attr' => array(
                    'placeholder' => '22 33 44 55'
                )
            ))

            ->add('address', 'text', array('label' => 'address: *',
                'attr' => array(
                    'placeholder' => '5 road new way go here',
                    'pattern' => '.{8,}'
                )
            ))

            ->add('city', 'text', array('label' => 'city: *',
                'attr' => array(
                    'placeholder' => 'New York',
                    'pattern' => '.{2,}'
                )
            ))

            ->add('postal_code', 'text', array('label' => 'postal code: *',
                'attr' => array(
                    'placeholder' => '1111',
                    'pattern' => '.{2,}'
                )
            ))

            ->add('country', 'choice', array('label' => 'country: *', 'choices' => $countries,
                'attr' => array(
                    'pattern' => '.{4,}',

                )
            ))

            ->add('organization', 'checkbox', array('label' => 'organization: ','required' => false,
                'attr' => array(//'checked' => 'checked'
                )))

            ->add('organization_name', 'text', array('label' => 'organization name: ','required' => false,
                'attr' => array(
                    'placeholder' => 'Touch3d'
                )
            ))
            ->add('organization_abbreviation', 'text', array('label' => 'organization abbreviation: ','required' => false,
                'attr' => array(
                    'placeholder' => 'T3d'
                )
            ))
            ->add('registration_number', 'text', array('label' => 'registration number: ','required' => false,
                'attr' => array(
                    'placeholder' => 'B186502000'
                )
            ))
            ->add('tax_identification_number', 'text', array('label' => 'tax identification number: ','required' => false,
                'attr' => array(
                    'placeholder' => '0031458D'
                )
            ))
            ->add('website', 'text', array('label' => 'main website: ','required' => false,
                'attr' => array(
                    'placeholder' => 'www.Touch3d.tn'
                )
            ))
            ->add('skype', 'text', array('label' => 'email/pseudo skype: ','required' => false,
                'attr' => array(
                    'placeholder' => 'contact@touch3d.tn'
                )
            ))
            ->add('actif', 'hidden')
            ->add('save', 'submit', array('attr' => array('class' => 'btn btn-primary')))
            ->add('reset', 'reset', array('attr' => array('class' => 'btn btn-danger')));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'touch3d_adminbundle_client';
    }
}

