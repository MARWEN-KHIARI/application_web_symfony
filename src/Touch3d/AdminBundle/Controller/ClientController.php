<?php

namespace Touch3d\AdminBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Touch3d\AdminBundle\Entity\Client;
use Touch3d\AdminBundle\Form\ClientType;
use Touch3d\UserBundle\Entity\User;

class ClientController extends Controller
{

    /**
     * @Route("/user/profile",name="userProfile")
     * @Template()
     */
    public function userProfileAction()
    {

        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        $request->setLocale($langue);
        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId  = $user->getId();
        if (!is_object($user) || !$user instanceof UserInterface) {
            return $this->redirect($this->generateUrl('index'));
        }
        $client = new Client();
        //$client->setDueDate(new \DateTime('tomorrow'));
        $form = $this->createForm(new ClientType(), $client);


        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Client');
        $c = $repository->findOneBy(array('user' => $userId));
        //actif tester
        if ($c && $c->getActif()=="1") {
            $form->get('actif')->setData("true");
            $form->get('first_name')->setData($c->getFirstName());
            $form->get('last_name')->setData($c->getLastName());
            $form->get('phone')->setData($c->getPhone());
            $form->get('fax')->setData($c->getFax());
            $form->get('skype')->setData($c->getSkype());
            $form->get('address')->setData($c->getAddress());
            $form->get('city')->setData($c->getCity());
            $form->get('postal_code')->setData($c->getPostalCode());
            $form->get('country')->setData($c->getCountry());
            $form->get('organization')->setData($c->getOrganization());
            if ($c->getOrganization()==1) {
            $form->get('business_name')->setData($c->getBusinessName());
            $form->get('organization_name')->setData($c->getOrganizationName());
            $form->get('organization_abbreviation')->setData($c->getOrganizationAbbreviation());
            $form->get('registration_number')->setData($c->getRegistrationNumber());
            $form->get('tax_identification_number')->setData($c->getTaxIdentificationNumber());
            $form->get('website')->setData($c->getWebsite());
            }
        }else {
            $form->get('actif')->setData("false");
        }

        /*
        if ('POST' == $request->getMethod()) {
            //$form->bindRequest($request);
            $form->handleRequest($request);
            if ($form->isValid()) {
                //$form->get('user')->setData($user);
                //$data = $form->getData();
                //return $this->redirect($this->generateUrl("udashboard"));
            }*/

        return array(
            'form' => $form->createView(), 'user' => $user,'transLib' => "Profil"
        );

    }

    /**
     * @Route("/user/profile/save",name="userProfileSave")
     */
    public function userProfileSaveAction(Request $request)
    {

        $user=$this->container->get('security.context')->getToken()->getUser();
        $userId  = $user->getId();
        /*
            $userId  = $this->container->get('security.context')->getToken()->getUser()->getId();
            $em = $this->getDoctrine()->getManager();
            $user01 = $em->getRepository('Touch3dUserBundle:User')->findOneById($userId);*/
        $client = new Client();

        $form = $this->createForm(new ClientType(), $client);
        $form->bind($request);

        if(!$form->isValid())return new Response('<div class="alert alert-danger">Erreur data</div>');
        $doPersist=true;
        if($form->get('actif')->getData()=="true"){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Client');
        $client = $repository->findOneBy(array('user' => $userId));
            $doPersist=false;
        }
        if ('POST' == $request->getMethod()) {
            $client->setFirstName($form->get('first_name')->getData());
            $client->setLastName($form->get('last_name')->getData());
            $client->setPhone($form->get('phone')->getData());
            $client->setFax($form->get('fax')->getData());
            $client->setSkype($form->get('skype')->getData());
            $client->setAddress($form->get('address')->getData());
            $client->setCity($form->get('city')->getData());
            $client->setPostalCode($form->get('postal_code')->getData());
            $client->setCountry($form->get('country')->getData());

            $client->setUser($user);
            $organization = $form->get('organization')->getData();
            $client->setOrganization($organization);
            if ($organization=="1") {
                $client->setBusinessName($form->get('business_name')->getData());
                $client->setOrganizationName($form->get('organization_name')->getData());
                $client->setOrganizationAbbreviation($form->get('organization_abbreviation')->getData());
                $client->setRegistrationNumber($form->get('registration_number')->getData());
                $client->setTaxIdentificationNumber($form->get('tax_identification_number')->getData());
                $client->setWebsite($form->get('website')->getData());
            }

            $client->setActif("1");
            $em = $this->getDoctrine()->getManager();
            if($doPersist)$em->persist($client);
            $em->flush();


            $em = $this->getDoctrine()->getManager();
            $repUser = $em->getRepository('Touch3dUserBundle:User');
            $repUser = $repository->findOneById($userId);
            if ($repUser != null) {
                $repUser->setClient($client);
                $em->flush();
            }


            //return $this->redirect($this->generateUrl("udashboard"));
            return new Response('<div class="alert alert-success">Identification terminer'.'</div>');
        }
        return new Response('<div class="alert alert-warning">Erreur identification</div>');

    }


}