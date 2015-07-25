<?php

namespace Touch3d\AdminBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Touch3d\AdminBundle\Entity\Visiteur;


class DefaultController extends Controller
{

    /**
     * @Route("/",name="index")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('ACCUEIL'));
    }

    /**
     * @Route("/admin/",name="admin")
     * @Template()
     */
    public function adminAction()
    {
        return $this->redirect($this->generateUrl('dashboard'));
    }

    /**
     * @Route("/admin/dashboard",name="dashboard")
     * @Template()
     */
    public function dashboardAction()
    {

        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            return $this->redirect($this->generateUrl('index'));
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Newsletter');
        $newsletters = $repository->findAll();
        return array('user' => $user,'newsletters' => $newsletters);
    }

    /**
     * @Route("/user/",name="user")
     * @Template()
     */
    public function userAction()
    {
        return $this->redirect($this->generateUrl('udashboard'));
    }

    /**
     * @Route("/checkDashboard",name="checkDashboard")
     * @Template()
     */
    public function checkDashboardAction()
    {
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            if( $securityContext->isGranted('ROLE_ADMIN') ){
                return $this->redirect($this->generateUrl('dashboard'));
            }else if( $securityContext->isGranted('ROLE_USER') ){
                return $this->redirect($this->generateUrl('udashboard'));
            }
        }
        else
            return $this->redirect($this->generateUrl('index'));

    }


    /**
     * @Route("/user/dashboard",name="udashboard")
     * @Template()
     */
    public function udashboardAction()
    {

        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            return $this->redirect($this->generateUrl('index'));
        }

        return array('user' => $user);
    }
    /**
     * @Route("/ajax/_language",name="_language")
     */
    public function _languageAction(Request $request)
    {
        $langue = $request->get('language');
        $this->get('session')->set('_locale', $langue);
        return new Response('<script>location.reload();</script>');
    }
    /**
     * @Route("/admin/ajax/_pagination",name="_pagination")
     */
    public function _paginationAction(Request $request)
    {
        $pagination = $request->get('pagination');
        $this->get('session')->set('pagination', $pagination);
        return new Response('<script>ReloadDocument();</script>');
    }

    /**
     * @Route("/admin/dashboard/ajax/_statisticVisiteur",name="_statisticVisiteur")
     */
    public function _statisticVisiteurAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Visiteur');
        $vistrs = $repository->findAll();
        $total=0;
        $tabDate = '';
        $tabNbr = '';
        $moisAncien='';
        $date='';
        foreach ($vistrs as $visr) {
            //formater la date pour les statistique
            /*
            if($moisAncien==''){
                $moisAncien=$visr->getDate()->format('m');
                $date=$visr->getDate()->format('d/m/Y');
            }
            else*/
            if($moisAncien!=$visr->getDate()->format('m')){
                $moisAncien=$visr->getDate()->format('m');
                $date=$visr->getDate()->format('d/m');
            }
            else {
                $date=$visr->getDate()->format('d');
            }
            $tabDate .= "'".$date."'".',';
            $tabNbr  .= $visr->getNombre().',';
            $total+=$visr->getNombre();
        }
        $tabDate=trim($tabDate, ",");
        $tabNbr=trim($tabNbr, ",");
        $data = '
        <script>
		var lineChartData = {
			labels : ['.$tabDate.'],
			datasets : [
				{
					fillColor : "rgba(220,220,220,0.5)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					data : ['.$tabNbr.']
				}
			]

		}

	var myLine = new Chart(document.getElementById("canvas_visiteur").getContext("2d")).Line(lineChartData);
	$("#nbrx_visiteur").html("<h6>Nombre total: '.$total.'<br/></h6>");
	</script>';
        return new Response($data);
        //return new Response(var_dump($resp));

    }


}
