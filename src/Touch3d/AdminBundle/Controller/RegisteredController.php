<?php

namespace Touch3d\AdminBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Proxies\__CG__\Touch3d\AdminBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Touch3d\AdminBundle\Entity\Categorie;
use Touch3d\UserBundle\Entity\User;
use Touch3d\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class RegisteredController extends Controller
{


    /**
     * @Route("/admin/dashboard/registered",name="registered")
     * @Template()
     */

    public function registeredAction(Request $request)
    {
        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        $request->setLocale($langue);

        $registered = new User();
        $form = $this->createForm(new UserType(), $registered);
        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        return array('form' => $form->createView(), 'user' => $user, 'transLib' => $this->t('Registered'));
    }

    function userdata()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            return null;
        }
        return $user;
    }

    function t($s){
        return $this->get('translator')->trans($s);
    }
    function tc($s,$i,$name){
        return $this->get('translator')->transChoice($s,$i,array($name => $i));
    }


    /**
     * @Route("/admin/dashboard/registered/ajax/_deleteRegistered",name="_deleteRegistered")
     */
    public function _deleteRegisteredAction()
    {
        $id = $this->getRequest()->get('id');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dUserBundle:User');
        $registered = $repository->findOneById($id);

        if ($registered != null) {
            $em->remove($registered);
            $em->flush();

            $response = new Response('<div class="alert alert-success">' . $this->tc('The %lb% of this id :',$this->t('Registered'),'%lb%').$id.' '.$this->t('is removed!') . '</div>');
        } else {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Registered'),'%lb%') . $id . '</div>');
        }
        return $response;
    }

    /**
     * @Route("/admin/dashboard/registered/ajax/_covertirRegistered",name="_covertirRegistered")
     */
    public function _covertirRegisteredAction()
    {
        $id = $this->getRequest()->get('id');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dUserBundle:User');
        $registered = $repository->findOneById($id);


        if ($registered != null) {
            $cm=$registered->getCommercial();
            $cm=!$cm;
            $registered->setCommercial($cm);
            $em->persist($registered);
            $em->flush();

            $response = new Response('<div class="alert alert-success">' . $this->tc('The %lb% of this id :',$this->t('Registered'),'%lb%').$id.' </div>');
        } else {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Registered'),'%lb%') . $id . '</div>');
        }
        return $response;
    }

    /**
     * @Route("/admin/dashboard/registered/ajax/_selectXRegistered",name="_selectXRegistered")
     */
    public function _selectXRegisteredAction()
    {
        $id = $this->getRequest()->get('id');

        $registered = new User();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dUserBundle:User');
        $registered = $repository->findOneById($id);

        if ($registered == null) {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Registered'),'%lb%') . $id . '</div>');
        } else {


            $html = "";
            $html .= "<b class='lab0'>Id </b><hr/> <p>" . htmlspecialchars($registered->getId(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>Email </b><hr/> <p>" . htmlspecialchars($registered->getEmail(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>Username </b><hr/> <p>" . htmlspecialchars($registered->getUsername(), ENT_QUOTES) . "</p><br/>";
            if($registered->getCommercial())$html .= "<br/><b class='lab0'>Commercial </b><hr/> <p>Valider</p><br/>";
            else $html .= "<br/><b class='lab0'>Commercial </b><hr/> <p>Non Valider</p><br/>";


        }

$c =new Client();

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Client');
        $c = $repository->findOneByUser($id);
        if ($c && $c->getActif()=="1") {
        $html .= "<br/><b class='lab0'>Active </b><hr/> <p>active</p><br/>";
            $html .= '<table class="table table-striped table-hover">';
            $html .= "<tr><th>first_name        </th><td> " . htmlspecialchars($c->getFirstName(), ENT_QUOTES) . "</td></tr>";
            $html .= "<tr><th>last_name         </th><td> " . htmlspecialchars($c->getLastName(), ENT_QUOTES) . "</td></tr>";
            $html .= "<tr><th>phone             </th><td> " . htmlspecialchars($c->getPhone(), ENT_QUOTES) . "</td></tr>";
            $html .= "<tr><th>fax             </th><td> " . htmlspecialchars($c->getFax(), ENT_QUOTES) . "</td></tr>";
            $html .= "<tr><th>skype             </th><td> " . htmlspecialchars($c->getSkype(), ENT_QUOTES) . "</td></tr>";
            $html .= "<tr><th>address             </th><td> " . htmlspecialchars($c->getAddress(), ENT_QUOTES) . "</td></tr>";
            $html .= "<tr><th>city             </th><td> " . htmlspecialchars($c->getCity(), ENT_QUOTES) . "</td></tr>";
            $html .= "<tr><th>postal_code             </th><td> " . htmlspecialchars($c->getPostalCode(), ENT_QUOTES) . "</td></tr>";
            $html .= "<tr><th>country           </th><td> " . htmlspecialchars($c->getCountry(), ENT_QUOTES) . "</td></tr>";
            if ($c->getOrganization()==1) {
            $html .= "<tr><th>business_name </th><td> " . htmlspecialchars($c->getBusinessName(), ENT_QUOTES) . "</td></tr>";
                $html .= "<tr><th>organization_name </th><td> " . htmlspecialchars($c->getOrganizationName(), ENT_QUOTES) . "</td></tr>";
                $html .= "<tr><th>organization_abbreviation </th><td> " . htmlspecialchars($c->getOrganizationAbbreviation(), ENT_QUOTES) . "</td></tr>";
                $html .= "<tr><th>registration_number </th><td> " . htmlspecialchars($c->getRegistrationNumber(), ENT_QUOTES) . "</td></tr>";
                $html .= "<tr><th>tax_identification_number </th><td> " . htmlspecialchars($c->getTaxIdentificationNumber(), ENT_QUOTES) . "</td></tr>";
                $html .= "<tr><th>website </th><td> " . htmlspecialchars($c->getWebsite(), ENT_QUOTES) . "</td></tr>";
            }
            $html .= "</table>";

        }
        else  $html .= "<br/><b class='lab0'>Active </b><hr/> <p>Not active</p><br/>";

            return new Response($html);

    }


    /**
     * @Route("/admin/dashboard/registered/ajax/_viewAllRegistered",name="_viewAllRegistered")
     */
    public function _viewAllRegisteredAction()
    {

        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dUserBundle:User');
        $registers = $repository->findAll();
        $form = $this->createForm(new UserType(), new User());
        $response = '';
        $i = 0;
        foreach ($registers as $register) {
            $response .= $this->fluxView($register);
            $i++;
        }
        return new Response($response);
    }




    function fluxView($register)
    {
        $data = "<tr>";
        $data .= '<form action="#" method="POST" target="_self" >' .
            '<td>' . ($register->getId()) . '</td>' .
            '<td>' . ($register->getEmail()) . '</td>' .
            '<td>' . ($register->getUsername()) . '</td>';
        if($register->getCommercial()){
        $data .='<td> <i class=" icon-ok"></i> Commercial </td>';
        }else{
            $data .='<td> <i class=" icon-remove"></i> User </td>';
        }
        $data .= '<td><div class="btn-group">' .
            '<a class="btn" href="javascript:viewRegistered(' . $register->getId() . ');">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('View Details').'" class="icon-eye-open"></i> </a>' .
            '<a class="btn" id="522" href="javascript:bootbox.confirm(&quot;'.$this->t('Are you sure?').'&quot;, function(result){if(result)deleteRegistered(' . $register->getId() . ');});">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('Delete Data').'" class="icon-trash"></i></a>';

        if(!$register->getCommercial()){
         $data .= '<a class="btn" id="522" href="javascript:bootbox.confirm(&quot;'.$this->t('Are you sure?').'&quot;, function(result){if(result)covertirRegistered(' . $register->getId() . ');});">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="convertir en commercial" class="icon-thumbs-up"></i></a>';
        }else{
         $data .= '<a class="btn" id="522" href="javascript:bootbox.confirm(&quot;'.$this->t('Are you sure?').'&quot;, function(result){if(result)covertirRegistered(' . $register->getId() . ');});">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="convertir en utilisateur" class="icon-thumbs-down"></i></a>';
        }
        $data .= '</div></td></form>';
        $data .= "</tr>";
        return $data;
    }




    /**
     * @Route("/admin/dashboard/registered/ajax/_findRegistered",name="_findRegistered")
     */
    public function _findRegisteredAction()
    {

        $req = $this->getRequest()->get('req');


        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT u
            FROM Touch3dUserBundle:User u
            WHERE (u.id like :req)OR(u.commercial like :req)
            ORDER BY u.id DESC'
        )->setParameter('req', '%' . $req . '%');

        $registers = $query->getResult();
        $response = '';
        $founded = 0;
        foreach ($registers as $register) {
            $response .= $this->fluxView($register);
            $founded++;
        }

        if ($registers == null) $response = "<tr><td colspan='5'><div class='alert alert-warning'>null registered</div></td></tr>";

        return new Response($response);
    }

}