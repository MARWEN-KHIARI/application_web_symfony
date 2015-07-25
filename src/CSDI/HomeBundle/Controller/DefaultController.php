<?php

namespace CSDI\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\Extension\Validator\Constraints\Form;
use Symfony\Component\Form\FormConfigBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Touch3d\AdminBundle\Entity\Commande;
use Touch3d\AdminBundle\Entity\Visiteur;
use Touch3d\AdminBundle\Form\ContactType;
use Ps\PdfBundle\Annotation\Pdf;
use Touch3d\AdminBundle\Entity\Article;
use Touch3d\AdminBundle\Entity\Produit;
use Touch3d\AdminBundle\Entity\Newsletter;
use Touch3d\UserBundle\Entity\User;

class DefaultController extends Controller
{

    public function getProductsWithCategory($category)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT p, c FROM Touch3dAdminBundle:Produit p JOIN p.categorie c WHERE c.id = :category AND p.status = \'publish\' ORDER BY p.id');
        $query->setParameter('category', $category);
        return $query->getResult();
        /* return $this->getManager()
                    ->createQuery('SELECT p FROM myBundle:Verbs p WHERE p.verbid='.$verbid)
                    ->getSingleResult();*/
    }


    function _getOneEntity($table, $nameEntity, $valueEntity)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Touch3dAdminBundle:' . $table);
        return $repository->findOneBy(array($nameEntity => $valueEntity));
    }

    function _getUser($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Touch3dUserBundle:User');
        return $repository->findOneBy(array('id' => $id));
    }

    function _getOneEntityPublished($table, $nameEntity, $valueEntity)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Touch3dAdminBundle:' . $table);
        return $repository->findOneBy(array($nameEntity => $valueEntity, 'status' => 'publish'));
    }

    function _getEntity($table)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Touch3dAdminBundle:' . $table);
        return $repository->findAll();
    }


    function getEntityPublished($table)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:' . $table . ' p
            WHERE p.status like :req'
        )->setParameter('req', 'publish');
        return $query->execute();
    }

    function getEntityPublishedLimited($table, $limit)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:' . $table . ' p
            WHERE p.status like :req
            ORDER BY p.id DESC'
        )->setParameter('req', 'publish');
        $query->setMaxResults($limit);
        return $query->execute();
    }

    public function findOneByIdJoinedToCategory($id)
    {
        $query = $this->getManager()
            ->createQuery('
              SELECT p, c FROM Touch3dAdminBundle:Produit p
              JOIN p.Categorie c
              WHERE p.id = :id
            ')->setParameter('id', $id);
        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    function testUserCnx()
    {
        /*
            {% if is_granted('ROLE_USER') %}
            {% endif %}
            $user = $this->container->get('security.context')->getToken()->getUser();
           if (!is_object($user) || !$user instanceof UserInterface) $user = "n-";
            if ( false === $this->container->get('security.context')->isGranted('ROLE_USER')) {$user = "n-";}
        */
    }

    public function statusCommercial()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $com = $user->getCommercial();
        if ($com == 1) return true;
        else return false;
    }

    public function statusUser()
    {
        if (true === $this->container->get('security.context')->isGranted('ROLE_USER')) return true;
        else return false;
    }


    /**
     * @Route("/accueil",name="ACCUEIL")
     * @Template()
     */
    public function accueilAction()
    {
        $this->addIp();

        $Produits = $this->getEntityPublishedLimited('Produit', 3);

        $Evenements = $this->getEntityPublishedLimited('Article',3);
        $Partenaires = $this->getEntityPublishedLimited('Partenaire',6);

        return array('Produits' => $Produits, 'Evenements' => $Evenements, 'Partenaires' => $Partenaires);
    }


    /**
     * @Route ("/Catalogue-ebook", name="CatalogueBook")
     */
    public function catalogueBookAction($name = 'Pedro')
    {
        $Produits = $this->getEntityPublished('Produit');
        $facade = $this->get('ps_pdf.facade');
        $response = new Response();
        $this->render('CSDIHomeBundle:Default:catalogueBookAction.pdf.twig', array('Produits' => $Produits), $response);

        $xml = $response->getContent();

        $content = $facade->render($xml);

        return new Response($content, 200, array('content-type' => 'application/pdf'));
    }

    /**
     * @Route("/reference",name="REFERENCE")
     * @Template()
     */
    public function referenceAction()
    {
        $this->addIp();
        return array();
    }


    /**
     * @Route("/catalogue",name="CATALOGUE")
     * @Template()
     */
    public function catalogueAction()
    {
        $this->addIp();
        $Produits = $this->getEntityPublished('Produit');
        $userResp = "b";
        if (($this->statusUser()) && ($this->statusCommercial())) {
            $userResp = "n-";
        }

        return array('Produits' => $Produits, 'user_' => $userResp);
    }


    /**
     * @Route("/user/commander",name="commander")
     */
    public function commander()
    {

        if (false === $this->statusUser()) {
        return new Response("erreur");
    }
        $id = $this->getRequest()->get('id');
        $data = '
        <label for="qte_' . $id . '">Quantité: </label><br/><input id="qte_' . $id . '" type="number" value="10" style="float: right; max-width:80px;"/><br/><br/>
        <img id="img_ajouter_panier" style="width: 100px;float: right;cursor: pointer;" onclick="commanderExec(' . $id . ');return false;"  src=""/>';
        return new Response($data);
    }

    /**
     * @Route("/user/commanderExec",name="commanderExec")
     */
    public function commanderExec()
    {
        $prodID = $this->getRequest()->get('id');
        $qte = $this->getRequest()->get('qte');

        $user = new User();
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (false === $this->statusUser()) {
        return new Response("erreur");
    }

        $prd = new Produit();

        $prd = $this->_getOneEntity('Produit', 'id', $prodID);
        //$userID = $user->getId();
        //$user=$this->_getUser($userID);

        $cmd = new Commande();
        $cmd->setProduit($prd);
        $cmd->setQuantite($qte);
        $cmd->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cmd);
        $em->flush();

        return new Response("good");
    }

    /**
     * @Route("/evenement",name="EVENEMENT")
     * @Template()
     */
    public function evenementAction()
    {
        $this->addIp();
        $Evenements = $this->getEntityPublishedLimited('Article',5);
        //$Evenements = $this->getEntityPublished('Article');
        return array('Evenements' => $Evenements);
    }

    /**
     * @Route("/SETNlttr",name="SETNEWST")
     */
    public function SETNlttr()
    {
        $Nwsltter = new Newsletter();
        $email = $this->getRequest()->get('email');
        $Nwsltter->setEmail($email);

        $em = $this->getDoctrine()->getManager();
        $em->persist($Nwsltter);
        $em->flush();

        return new Response("good");
    }


    /**
     * @Route("/contact",name="CONTACT")
     * @Template()
     */
    public function contactAction(Request $request)
    {
        $this->addIp();
        $form = $this->createForm(new ContactType());
        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {

                $message = \Swift_Message::newInstance()
                    ->setSubject($form->get('subject')->getData())
                    ->setFrom('contact@csdi.com')
                    ->setTo('contact@csdi.com')
                    ->setBody('<html>' .
                        ' <head><title>' . $form->get('subject')->getData() . '</title></head>' .
                        ' <body>' .
                        'ip:' . $request->getClientIp() . '<br/>' .
                        'name:' . $form->get('name')->getData() . '<br/>' .
                        'email:' . $form->get('email')->getData() . '<hr/>' .
                        'message:' . $form->get('message')->getData() . '<br/>' .
                        ' </body>' .
                        '</html>',
                        'text/html');
                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('success', 'Votre email a été envoyé! Merci!');
                return $this->redirect($this->generateUrl('CONTACT'));
            }
        }

        return array('form' => $form->createView());

    }

    function addIp()
    {
        //$ip = $this->container->get('request')->getClientIp();
        $ip = $this->getRequest()->getClientIp();
        $ip_ = $this->get('session')->get('ipclient', 0);
        if ($ip_ != 0) {
            if ($ip_ == $ip) return null;
        }

        $request = $this->container->get('request');
        $session = $request->getSession();
        $session->set('ipclient', $ip);


        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Visiteur');
        $date = new \Datetime;
        //$date=$date->format('Y-m-d');

        //$vis = $repository->findOneById(1);
        $vis = $repository->findOneByDate($date);
        if ($vis == null) {
            $vis = new Visiteur();
            $vis->setNombre(1);
            $vis->setDate($date);
            $em->persist($vis);
            $em->flush();
        } else {
            $vis->setNombre(($vis->getNombre()) + 1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($vis);
            $em->flush();
        }
        return null;
    }


}
