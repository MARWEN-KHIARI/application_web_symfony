<?php

namespace Touch3d\AdminBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Touch3d\AdminBundle\Entity\Partenaire;
use Touch3d\AdminBundle\Form\PartenaireType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class PartnerController extends Controller
{


    /**
     * @Route("/admin/dashboard/partner",name="partner")
     * @Template()
     */
    public function partnerAction(Request $request)
    {
        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        $prd = new Partenaire();
        $form = $this->createForm(new PartenaireType(), $prd);
        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        return array('form' => $form->createView(), 'user' => $user, 'transLib' => $this->t('Partner'));
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
     * @Route("/admin/dashboard/partner/ajax/_deletePartner",name="_deletePartner")
     */
    public function _deletePartnerAction()
    { //espace-client
        $id = $this->getRequest()->get('id');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Partenaire');
        $product = $repository->findOneById($id);

        if ($product != null) {
            $em->remove($product);
            $em->flush();

            $response = new Response('<div class="alert alert-success">' . $this->tc('The %lb% of this id :',$this->t('Partner'),'%lb%').$id.' '.$this->t('is removed!') . '</div>');
        } else {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Partner'),'%lb%') . $id . '</div>');
        }
        return $response;
    }

    /**
     * @Route("/admin/dashboard/partner/ajax/_selectXPartner",name="_selectXPartner")
     */
    public function _selectXPartnerAction()
    {
        $id = $this->getRequest()->get('id');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Partenaire');
        $product = $repository->findOneById($id); //->findOneBy(array('number' => $number))->findAll();->findOneById($id);

        $prd = new Partenaire();
        if ($product == null) {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Partner'),'%lb%') . $id . '</div>');
        } else {

            $html = "";
            $html .= "<b class='lab0'>Nom </b><hr/> <p>" . htmlspecialchars($product->getNom(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>Logo </b><hr/> <p><img src='" . htmlspecialchars($product->getLogo(), ENT_QUOTES) . "' class='img-polaroid'/></p><br/>";
            $html .= "<br/><b class='lab0'>lien </b><hr/> <p>" . htmlspecialchars($product->getLien(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>matricule </b><hr/> <p>" . htmlspecialchars($product->getMatricule(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>responsable </b><hr/> <p>" . htmlspecialchars($product->getResponsable(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>adresse </b><hr/> <p>" . htmlspecialchars($product->getAdresse(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>ville </b><hr/> <p>" . htmlspecialchars($product->getVille(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>poste </b><hr/> <p>" . htmlspecialchars($product->getPoste(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>pays </b><hr/> <p>" . htmlspecialchars($product->getPays(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>tel </b><hr/> <p>" . htmlspecialchars($product->getTel(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>fax </b><hr/> <p>" . htmlspecialchars($product->getFax(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>email </b><hr/> <p>" . htmlspecialchars($product->getEmail(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>Status du produit </b><hr/> <p>" . htmlspecialchars($product->getStatus(), ENT_QUOTES) . "</p><br/>";
            $response = new Response($html);
        }
        return $response;
    }

    /**
     * @Route("/admin/dashboard/partner/ajax/_selectAllPartner",name="_selectAllPartner")
     */
    public function _selectAllPartnerAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Partenaire');
        $products = $repository->findAll();
        //->getArticles(3, $page);
        $prd = new Partenaire();
        $form = $this->createForm(new PartenaireType(), $prd);
        $response = '';
        $i = 0;
        foreach ($products as $product) {
            $response .= $this->fluxView($product);
            $i++;
        }
        $request = $this->container->get('request');
        $session = $request->getSession();
        $session->set('partnerNb', '' . $i);

        return new Response($response);
    }

    /**
     * @Route("/admin/dashboard/partner/ajax/_viewPaginatedPartner",name="_viewPaginatedPartner")
     */
    public function _viewPaginatedPartnerAction()
    {
        $nombreParPage = $this->get('session')->get('pagination', 3);
        $pageActive = $this->getRequest()->get('pageActive');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:Partenaire p
            ORDER BY p.id DESC'
        )
            ->setFirstResult(($pageActive - 1) * $nombreParPage)
            ->setMaxResults($nombreParPage);
        $products = $query->getResult();

        $form = $this->createForm(new PartenaireType(), new Partenaire());

        $response = '';
        $i = 0;
        foreach ($products as $product) {
            $response .= $this->fluxView($product);
            $i++;
        }

        $response .= $this->fluxSearchDataList();
        return new Response($response);
    }

    function fluxSearchDataList()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Partenaire');
        $products = $repository->findAll();
        $SearchDataList = '';
        foreach ($products as $product) {
            $SearchDataList .= '<option value=\"' . ($product->getNom()) . '\">';
        }
        $SearchDataList .= '<option value=\"----------------------------------------------------\"></option>';
        $SearchDataList .= '<option value=\"publish\">'.$this->t('publish').'</option>';
        $SearchDataList .= '<option value=\"revision\">'.$this->t('revision').'</option>';
        $SearchDataList .= '<option value=\"draft\">'.$this->t('draft').'</option>';
        $SearchDataList = '<script>$("#bib").html("' . $SearchDataList . '");</script>';
        return $SearchDataList;
    }


    function fluxView($product)
    {
        $data = "<tr>";
        $data .= '<form action="#" method="POST" target="_self" >' .
            '<td>' . ($product->getId()) . '</td>' .
            '<td>' . ($product->getNom()) . '</td>' .
            '<td><img src="' . ($product->getLogo()) . '" width="70px"/></td>' .
            '<td>' . ($product->getStatus()) . '</td>' .
            '<td><div class="btn-group">' .
            '<a class="btn" href="javascript:viewProduct(' . $product->getId() . ');">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('View Details').'" class="icon-eye-open"></i> </a>' .
            '<a class="btn" href="' . 'partner/edit/' . $product->getId() . '" target="_blank">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('Edit Informations').'" class="icon-pencil"></i> </a>' .
            '<a class="btn" id="522" href="javascript:bootbox.confirm(&quot;'.$this->t('Are you sure?').'&quot;, function(result){if(result)deleteProduct(' . $product->getId() . ');});">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('Delete Data').'" class="icon-trash"></i></a>'
            . '</div></td></form>';
        $data .= "</tr>";
        return $data;
    }


    /**
     * @Route("/admin/dashboard/partner/ajax/_statisticPartner",name="_statisticPartner")
     */
    public function _statisticPartnerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT COUNT(p.id)
    FROM Touch3dAdminBundle:Partenaire p
    '
        );
        $query = $query->getSingleResult();
        $productNb = $query[1];
        //->setParameter('req1', '%' . $req1 . '%');
        $req1 = "draft";
        $req2 = "revision";
        $req3 = "publish";

        $query = $em->createQuery(
            'SELECT COUNT(p.id)
            FROM Touch3dAdminBundle:Partenaire p
            WHERE p.status like :req1'
        )->setParameter('req1', $req1);
        $query = $query->getSingleResult();
        $productND = $query[1];

        $query = $em->createQuery(
            'SELECT COUNT(p.id)
            FROM Touch3dAdminBundle:Partenaire p
            WHERE p.status like :req2'
        )->setParameter('req2', $req2);
        $query = $query->getSingleResult();
        $productNR = $query[1];

        $query = $em->createQuery(
            'SELECT COUNT(p.id)
            FROM Touch3dAdminBundle:Partenaire p
            WHERE p.status like :req3'
        )->setParameter('req3', $req3);
        $query = $query->getSingleResult();
        $productNP = $query[1];

        $request = $this->container->get('request');
        $session = $request->getSession();
        $session->set('partnerNb', $productNb);

        $data_info = '' .
            $this->fluxStat_Publish($productNP) .
            $this->fluxStat_Revision($productNR) .
            $this->fluxStat_Draft($productND) .
            $this->fluxStat_Normal($productNb);

        $data = '<script>' .
            'var pieData = [' .
            '{ value : ' . $productNP . ', color : "#3a87ad" },' .
            '{ value : ' . $productNR . ', color : "#f89406" },' .
            '{ value : ' . $productND . ', color : "#b94a48" }' .
            '];' .
            'var Pie_Product = new Chart(document.getElementById("canvas_product").getContext("2d")).Pie(pieData);' .
            '$("#info_canvas_product").html("' . $data_info . '");' .
            '</script>';
        return new Response($data);
        //return new Response(var_dump($resp));

    }

    function fluxStat_Normal($i)
    {
        return '<p>'.$this->tc('Number of all %lb%',$this->t('Partner'),'%lb%').' <span class=\"badge\">' . $i . '</span></p>';
    }

    function fluxStat_Publish($i)
    {
        return '<p>'.$this->t('publish').' <span class=\"badge badge-info\">' . $i . '</span></p>';
    }

    function fluxStat_Revision($i)
    {
        return '<p>'.$this->t('revision').' <span class=\"badge badge-warning\">' . $i . '</span></p>';
    }

    function fluxStat_Draft($i)
    {
        return '<p>'.$this->t('draft').' <span class=\"badge badge-important\">' . $i . '</span></p>';
    }


    /**
     * @Route("/admin/dashboard/partner/ajax/_paginatePartner",name="_paginatePartner")
     */
    public function _paginatePartnerAction()
    {
        $nombreParPage = $this->get('session')->get('pagination', 3);
        $pageActive = $this->getRequest()->get('pageActive');

        $request = $this->container->get('request');
        $session = $request->getSession();
        $productNb = $session->get('partnerNb');


        $pageFirst = ($pageActive - 1) * $nombreParPage;
        $pageLast = ceil($productNb / $nombreParPage);

        $prev = $pageActive - 1;
        if ($prev < 1) $prev = 1;
        $next = $pageActive + 1;
        if ($next > $pageLast) $next = $pageActive;
        return new Response($this->fluxPagination($pageActive, $next, $prev, $pageFirst, $pageLast));

    }

    function fluxPagination($pageActive, $next, $prev, $pageFirst, $pageLast)
    {
        $data = '<ul>';
        $data .= '<li>';
        if ($pageActive > 1) $data .= $this->ClassNormal("&laquo;", $pageFirst); else $data .= $this->Classdisabled("&laquo;");
        $data .= '</li>';
        $data .= '<li>';
        if ($pageActive > 1) $data .= $this->ClassNormal("&lt;", $prev); else $data .= $this->Classdisabled("&lt;");
        $data .= '</li>';
        for ($pg = 1; $pg <= $pageLast; $pg++) {
            $data .= '<li>';
            if ($pg == $pageActive) $data .= $this->ClassActive($pg);
            else $data .= $this->ClassNormal($pg, $pg);
            $data .= '</li>';
        }
        $data .= '<li>';
        if ($pageActive < $pageLast) $data .= $this->ClassNormal("&gt;", $next); else $data .= $this->Classdisabled("&gt;");
        $data .= '</li>';
        $data .= '<li>';
        if ($pageActive < $pageLast) $data .= $this->ClassNormal("&raquo;", $pageLast); else $data .= $this->Classdisabled("&raquo;");
        $data .= '</li></ul>';
        return $data;
    }

    function ClassActive($s)
    {
        return '<span class="active">' . $s . '</span>';
    }

    function Classdisabled($s)
    {
        return '<span class="disabled">' . $s . '</span>';
    }

    function ClassNormal($s, $p)
    {
        return '<a href="javascript:viewAllProduct(' . $p . ');">' . $s . '</a>';
    }

    /**
     * @Route("/admin/dashboard/partner/create",name="createPartner")
     * @Template()
     */
    public function createAction()
    {
        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        $prd = new Partenaire();
        $form = $this->createForm(new PartenaireType(), $prd);


        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        $images = $this->selectAllImages();
        return array('form' => $form->createView(), "images" => $images, 'user' => $user, 'transLib' => $this->t('Partner'));

    }


    /**
     * @Route("/admin/dashboard/partner/edit/{id}",name="editPartner")
     * @Template()
     */
    public function editAction($id)
    {
        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        if ($id != null) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('Touch3dAdminBundle:Partenaire');
            $product = $repository->findOneById($id);
        } else $product = null;
        $prd = new Partenaire();
        $form = $this->createForm(new PartenaireType(), $prd);

        $form->get('nom')->setData($product->getNom());
        $form->get('logo')->setData($product->getLogo());
        $form->get('lien')->setData($product->getLien());
        $form->get('matricule')->setData($product->getMatricule());
        $form->get('responsable')->setData($product->getResponsable());
        $form->get('adresse')->setData($product->getAdresse());
        $form->get('ville')->setData($product->getVille());
        $form->get('poste')->setData($product->getPoste());
        $form->get('pays')->setData($product->getPays());
        $form->get('tel')->setData($product->getTel());
        $form->get('fax')->setData($product->getFax());
        $form->get('email')->setData($product->getEmail());
        $form->get('status')->setData($product->getStatus());

        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        $images = $this->selectAllImages();
        return array('Produit' => $product, "images" => $images, 'form' => $form->createView(), 'user' => $user, 'transLib' => $this->t('Partner'));
    }

    function selectAllImages()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Image');
        return $repository->findAll();
    }

    /**
     * @Route("/admin/dashboard/partner/ajax/_editPartner",name="_editPartner")
     */
    public function _editPartnerAction()
    {

        $id = $this->getRequest()->get('id');

                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('Touch3dAdminBundle:Partenaire');
                $product = $repository->findOneById($id);
                if (!$product) {
                    return new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Partner'),'%lb%') . $id . '</div>');
                }

        $nom = $this->getRequest()->get('nom');
        $product->setNom($nom);
        $logo = $this->getRequest()->get('logo');
        $product->setLogo($logo);
         $lien = $this->getRequest()->get('lien');
        $product->setLien($lien);
         $matricule = $this->getRequest()->get('matricule');
        $product->setMatricule($matricule);
         $responsable = $this->getRequest()->get('responsable');
        $product->setResponsable($responsable);
         $adresse = $this->getRequest()->get('adresse');
        $product->setAdresse($adresse);
         $ville = $this->getRequest()->get('ville');
        $product->setVille($ville);
         $poste = $this->getRequest()->get('poste');
        $product->setPoste($poste);
         $pays = $this->getRequest()->get('pays');
        $product->setPays($pays);
         $tel = $this->getRequest()->get('tel');
        $product->setTel($tel);
         $fax = $this->getRequest()->get('fax');
        $product->setFax($fax);
         $email = $this->getRequest()->get('email');
        $product->setEmail($email);
        $status = $this->getRequest()->get('status');
        $product->setStatus($status);

        //$em->persist($product);
        $em->flush();
        return new Response('<div class="alert alert-success">'.$this->tc('Id of the modified %lb% is: ',$this->t('Partner'),'%lb%') . $id. '</div>');
    }


    /**
     * @Route("/admin/dashboard/partner/ajax/_newPartner",name="_newPartner")
     */
    public function _newPartnerAction()
    {
        $product = new Partenaire();

        $nom = $this->getRequest()->get('nom');
        $product->setNom($nom);
        $logo = $this->getRequest()->get('logo');
        $product->setLogo($logo);
        $lien = $this->getRequest()->get('lien');
        $product->setLien($lien);
        $matricule = $this->getRequest()->get('matricule');
        $product->setMatricule($matricule);
        $responsable = $this->getRequest()->get('responsable');
        $product->setResponsable($responsable);
        $adresse = $this->getRequest()->get('adresse');
        $product->setAdresse($adresse);
        $ville = $this->getRequest()->get('ville');
        $product->setVille($ville);
        $poste = $this->getRequest()->get('poste');
        $product->setPoste($poste);
        $pays = $this->getRequest()->get('pays');
        $product->setPays($pays);
        $tel = $this->getRequest()->get('tel');
        $product->setTel($tel);
        $fax = $this->getRequest()->get('fax');
        $product->setFax($fax);
        $email = $this->getRequest()->get('email');
        $product->setEmail($email);
        $status = $this->getRequest()->get('status');
        $product->setStatus($status);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response('<div class="alert alert-success">'.$this->tc('Id of the new %lb% is: ',$this->t('Partner'),'%lb%') . $product->getId(). '</div>');
    }

    /**
     * @Route("/admin/dashboard/partner/ajax/_findPartner",name="_findPartner")
     */
    public function _findPartnerAction()
    {

        $req = $this->getRequest()->get('req');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:Partenaire p
             WHERE (p.id like :req)OR(p.nom like :req)OR(p.responsable like :req)OR(p.ville like :req)OR(p.poste like :req)OR(p.pays like :req)OR(p.status like :req)
            ORDER BY p.id DESC'
        )->setParameter('req', '%' . $req . '%');
        //OR(p.contenu like :req)
        $products = $query->getResult();

        $response = '';
        $founded = 0;
        foreach ($products as $product) {
            $response .= $this->fluxView($product);
            $founded++;
        }

        if ($products == null) $response = "<tr><td colspan='5'><div class='alert alert-warning'>". $this->tc('No %lb% found for this request : ',$this->t('Partner'),'%lb%') . $req . "</div></td></tr>";

        $request = $this->container->get('request');
        $session = $request->getSession();
        $productNb=$session->get('partnerNb', 0);

        $data_info = '' .
            '<p>'.$this->tc('Number %lb% founded',$this->t('Partner'),'%lb%').' <span class=\"badge badge-important\">' . $founded . '</span></p>'.
            $this->fluxStat_Normal($productNb);

        $data = '<script>' .
            'var pieData = [' .
            '{ value : ' . $founded . ', color : "#b94a48" },' .
            '{ value : ' . ($productNb-$founded) . ', color : "#3a87ad" }' .
            '];' .
            'var Pie_Product = new Chart(document.getElementById("canvas_product").getContext("2d")).Pie(pieData);' .
            '$("#info_canvas_product").html("' . $data_info . '");' .
            '</script>';
        return new Response($response.$data);
    }


} 