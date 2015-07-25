<?php

namespace Touch3d\AdminBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Touch3d\AdminBundle\Entity\Facture;
use Touch3d\AdminBundle\Entity\Produit;
use Touch3d\AdminBundle\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Ps\PdfBundle\Annotation\Pdf;

class FacturerController extends Controller
{

    /**
     * @Route("/user/dashboard/commande",name="userFacturer")
     * @Template()
     */
    public function facturerAction()
    {
        $langue = $this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        $userID = $user->getId();
        $cmds = $this->getEntityCmd($userID);
        if ($this->statusIsNotCommercial()) {
            return $this->redirect($this->generateUrl('udashboard'));
        }
        return array('user' => $user, 'cmds' => $cmds);
    }


    /**
     * @Route("/user/dashboard/facture",name="userFacture")
     * @Template()
     */
    public function factureAction()
    {
        $langue = $this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);
        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        $userID = $user->getId();
        $fcs = $this->getEntityFcs($userID);
        //$fcs=var_dump($fcs);
        if ($this->statusIsNotCommercial()) {
            return $this->redirect($this->generateUrl('udashboard'));
        }
        return array('user' => $user, 'factures' => $fcs);
    }

    /**
     * @Route("/user/dashboard/facture/delete",name="deleteFacture")
     */
    public function deleteFactureAction()
    {
        if ($this->statusIsNotCommercial()) {
            return $this->redirect($this->generateUrl('udashboard'));
        }
        $id = $this->getRequest()->get('id');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Facture');
        $fc = $repository->findOneById($id);

        if ($fc != null) {
            $em->remove($fc);
            $em->flush();

            $response = '<div class="alert alert-success">La facture est supprimer</div>';
        } else {
            $response = '<div class="alert alert-warning">La facture n\'est pas supprimer</div>';
        }
        return new Response($response);

    }


    public function statusIsNotCommercial()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $com = $user->getCommercial();
        if ($com == 1) return false;
        else return true;
    }


    function getEntityFcs($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:Facture p JOIN p.user u
            WHERE u.id like :req
            ORDER BY p.id'
        )->setParameter('req', $id);
        return $query->execute();
    }

    function getEntityCmd($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:Commande p JOIN p.user u
            WHERE u.id like :req and p.facture IS NULL
            ORDER BY p.id'
        )->setParameter('req', $id);
        return $query->execute();
    }

    function getEntityCmdId($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p.id
            FROM Touch3dAdminBundle:Commande p JOIN p.user u
            WHERE u.id like :req and p.facture IS NULL
            ORDER BY p.id'
        )->setParameter('req', $id);
        return $query->execute();
    }

    function getEntityCmdNormal($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:Commande p,Touch3dUserBundle:User u
            WHERE u.id like :req and u.id=p.user
            ORDER BY p.id'
        )->setParameter('req', $id);
        return $query->execute();
    }


    function userdata()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            return null;
        }
        return $user;
    }

    /**
     * @Route("/user/dashboard/commande/delete",name="deleteCommande")
     */
    public function deleteCommandeAction()
    {
        //->getArticles(3, $page);

        $id = $this->getRequest()->get('id');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Commande');
        $cat = $repository->findOneById($id);

        if ($cat != null) {
            $em->remove($cat);
            $em->flush();

            $response = '<div class="alert alert-success">La commande est supprimer</div>';
        } else {
            $response = '<div class="alert alert-warning">La commande n\'est pas supprimer</div>';
        }
        return new Response($response);

    }

    /**
     * @Route("/user/dashboard/commande/_selectXProduct",name="_UserSelectXProduct")
     */
    public function _selectXProductAction()
    {
        $id = $this->getRequest()->get('id');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Produit');
        $product = $repository->findOneById($id);

        $prd = new Produit();
        if ($product == null) {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ', $this->t('Product'), '%lb%') . $id . '</div>');
        } else {
            $html = "";
            $html .= "<h1 class='lab0'>" . htmlspecialchars($product->getNom(), ENT_QUOTES) . "</h1><br/>";
            $html .= "<p><b class='lab0'>Prix: </b>" . htmlspecialchars($product->getPrix(), ENT_QUOTES) . "</p><br/>";
            $html .= "<hr/> <p><img src='" . htmlspecialchars($product->getImg(), ENT_QUOTES) . "' class='img-polaroid' style='float:left;margin:20px;'/></p><br/>";
            $html .= "<br/><b class='lab0'>Resumer </b><hr/> <p>" . htmlspecialchars($product->getResumer(), ENT_QUOTES) . "</p><br/>";

            $response = new Response($html);
        }
        return $response;
    }


    /**
     * @Route("/user/dashboard/facture/create",name="_createFacture")
     */
    public function _CreateFactureAction()
    {
        $facture = new Facture();
        $facture->setDateCreation();
        $user = $this->userdata();
        if ($user == null) {
            return new Response('<div class="alert alert-warning">erreur</div>');
        }
        //$userID = $user->getId();
        $facture->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();
        //$IdFacture = $facture->getId();
        //var_dump($idcmd)

        $idcmds = $this->getRequest()->get('idcmds');
        if (false == $this->SecurityCmdFacture($idcmds)) return new Response('error');
        foreach ($idcmds as $idcmd) {
            $this->setEntityCmdFacture($idcmd, $facture);
        }
        return new Response('ok');
    }

    function SecurityCmdFacture($Ids)
    {
        $user = $this->userdata();
        if ($user == null) {
            return false;
        }
        return true;
        $userID = $user->getId();
        $cmds = $this->getEntityCmdId($userID);
        $i = 0;
        foreach ($cmds as $cmd) {
            if ($cmd != $Ids[$i]) {
                return false;
            }
            $i++;
        }
        return true;
    }

    function setEntityCmdFacture($id, $facture)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Commande');
        $cmd = $repository->findOneById($id);
        $cmd->setFacture($facture);
        $em->persist($facture);
        $em->flush();
        return true;
    }




    function getEntityFcSecurity($fcID,$userID)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT f.id
            FROM Touch3dAdminBundle:Facture f JOIN f.user u
            WHERE u.id like :req and f.id like :req2
            '
        )->setParameter('req', $userID)
            ->setParameter('req2', $fcID);
        $result=$query->getOneOrNullResult();
        if($result==null)return null;
        return true;
    }
    function getFactureInfo($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("
        SELECT f.id as id_facture, f.status as status_facture, f.dateCreation as date_creation, f.datePaiement as date_paiement, u.id as id_client, CONCAT(c.first_name,' ',c.last_name) as nom_client
        FROM Touch3dAdminBundle:Facture f, Touch3dAdminBundle:Client c, Touch3dUserBundle:User u
        WHERE f.id like :req and u.id=c.user and u.id=f.user
        ")->setParameter('req', $id);
        $results = $query->getOneOrNullResult();
        //$results = $query->getSingleResult();
        if($results==null)return null;
        return array('id_facture' => $results['id_facture'],'id_client' => $results['id_client'],'nom_client' => $results['nom_client'],'date_creation' => $results['date_creation'],'date_paiement' => $results['date_paiement'],'status_facture' => $results['status_facture']);
    }


    function getCommandesInfo($id)
    {

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('
        SELECT p.id as id, p.nom as nom, c.quantite as quantite, p.prix as prix, (prix * quantite) as MU
        FROM Touch3dAdminBundle:Facture f, Touch3dAdminBundle:Commande c, Touch3dAdminBundle:Produit p
        WHERE f.id like :req and f.id=c.facture and p.id=c.produit
        ')->setParameter('req', $id);
        //$results = $query->execute();
        $results = $query->getResult();
        //$results = $query->getOneOrNullResult();
        //$results = $query->getSingleResult();
        //$results = $query->getArrayResult();
        //$scalar = $query->getScalarResult();
        //$singleScalar = $query->getSingleScalarResult();

        /*$res=array();
        foreach($results as $result){
            array_push($res,array('id' => $result['id'],'nom' => $result['nom'],'quantite' => $result['quantite'],'prix' => $result['prix'],'MU' => $result['MU'],'MT' => $result['MT']));
        }*/
        return $results;
    }

    function getMT($results)
    {
        //SELECT SUM(p.prix * c.quantite) as MT
        $sum=0;
        foreach($results as $result){
            $quantite=floatval($result['quantite']);
            $prix=floatval($result['prix']);
            $sum+=$quantite*$prix;
        }
        return $sum;
    }

    function getMTP($MT)
    {
        $MT=floatval($MT);
        $TVA=$MT*18/100;
        return $MT + $TVA;
    }

    /**
     * @Route("/admin/dashboard/factures",name="adminFactures")
     * @Template()
     */
    public function adminFactureAction()
    {
        $langue = $this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);
        $user = $this->userdata();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT f
            FROM Touch3dAdminBundle:Facture f
            ORDER BY f.id'
        );
        $fcs = $query->execute();
        return array('user' => $user, 'factures' => $fcs);
    }

    /**
     * @Route("/admin/dashboard/factures/payer",name="payerFactures")
     */
    public function payerFacturesAction()
    {
        $id = $this->getRequest()->get('id');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Facture');
        $fc = $repository->findOneById($id);
        //$fc=new Facture();
        if ($fc != null) {

            $fc->setDatePaiement();
            $em->persist($fc);
            $em->flush();
            $dt=$this->calculerQuantiteStock($id);
            if($dt==""){
                $response = '<div class="alert alert-success">La facture est payée</div>';
            }else{
                $response = '<script>reloadPage1=false; bootbox.alert("'.$dt.'", "Products Probleme");</script><div class="alert alert-warning">La facture est payée, Probleme quantité en stock insuffisante '.$dt.'</div>';
            }
        } else {
            $response = '<div class="alert alert-warning">La facture n\'est pas payée</div>';
        }
        return new Response($response);

    }

    function calculerQuantiteStock($id)
    {
        $data="";
        $results = $this->getCommandesInfo($id);
        foreach($results as $result){
            $id1=$result['id'];
            $quantite=floatval($result['quantite']);

            $em = $this->getDoctrine()->getManager();
            $prd = $em->getRepository('Touch3dAdminBundle:Produit')->findOneById($id1);
            $quantiteStock=floatval($prd->getQteStock());
            $prd->setQteStock($quantiteStock-$quantite);
            $em->flush();
            if($quantiteStock<0){
                $data.="<br/>produit n°".$id1;
            }
        }

        return $data;
    }
    /**
     * @Route("/admin/dashboard/factures/delete",name="deleteFactures")
     */
    public function deleteFacturesAction()
    {
        $id = $this->getRequest()->get('id');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Facture');
        $fc = $repository->findOneById($id);

        if ($fc != null) {
            $em->remove($fc);
            $em->flush();

            $response = '<div class="alert alert-success">La facture est supprimer</div>';
        } else {
            $response = '<div class="alert alert-warning">La facture n\'est pas supprimer</div>';
        }
        return new Response($response);

    }


    /**
     * @Route("/admin/dashboard/facture/ajax/_findFacture",name="_findFacture")
     */
    public function _findFactureAction()
    {
        $req = $this->getRequest()->get('req');

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
           'SELECT f
            FROM Touch3dAdminBundle:Facture f
             WHERE (f.id like :req)OR(f.status like :req)
            ORDER BY f.id DESC'
        )->setParameter('req', '%' . $req . '%');
        $factures = $query->getResult();

        $response = '';
        $founded = 0;
        foreach ($factures as $facture) {
            $response .= $this->fluxView($facture);
            $founded++;
        }

        if ($factures == null) $response = "<tr><td colspan='5'><div class='alert alert-warning'>il n y a pas de facture</div></td></tr>";
        return new Response($response);
    }


    function fluxView($fc)
    {
        $data =     '<tr>'.
                    '<td>'.$fc->getId().'</td>'.
                    '<td width="150">'.
                        '<a class="btn" href="/admin/dashboard/factures/'.$fc->getId().'" target="_blank">'.
                            '<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="View Details" class="icon-eye-open"></i> </a>'.

                        '<a class="btn" href="javascript:bootbox.confirm(&quot;vous êtes sûr?&quot;, function(result){if(result)deleteFacture('.$fc->getId().');});">'.
                            '<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="Delete Data" class="icon-trash"></i></a>';
        if($fc->getStatus()=="facture non payée")
        $data.=
                        '<a class="btn" href="javascript:bootbox.confirm(&quot;votre facture est payé?&quot;, function(result){if(result)payerFacture('.$fc->getId().');});">'.
                            '<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="Delete Data" class="icon-shopping-cart"></i></a>';
        $data.=     '</td>'.
                    '<td>'.$fc->getDateCreation()->format('d-m-Y').'</td>';
        if($fc->getDatePaiement()!=null)
        $data.=     '<td>'.$fc->getDatePaiement()->format('d-m-Y');
        $data.=     '</td>'.
                    '<td>'.$fc->getStatus().'</td>'.
                    '</tr>';
        return $data;
    }
    /**
     * @Route ("/user/dashboard/facture/{id}", name="pdfFacture")
     */
    public function pdfFactureAction($id)
    {

        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        $userID = $user->getId();
        $id = $this->getRequest()->get('id');
        if ($id == null) return $this->redirect($this->generateUrl('index'));

        $FactureSecurity = $this->getEntityFcSecurity($id,$userID);
        if($FactureSecurity==null) return $this->redirect($this->generateUrl('index'));

        $FactureInfo = $this->getFactureInfo($id);
        if($FactureInfo==null) return $this->redirect($this->generateUrl('index'));
        $CommandesInfo = $this->getCommandesInfo($id);
        $MT = $this->getMT($CommandesInfo);
        $MTP = $this->getMTP($MT);
        $facade = $this->get('ps_pdf.facade');
        $response = new Response();
        $this->render('Touch3dAdminBundle:Facturer:pdfFacture.pdf.twig', array('facture' => $FactureInfo,'commandes' => $CommandesInfo,'MT' => $MT,'MTPaye' => $MTP), $response);
        $xml = $response->getContent();
        $content = $facade->render($xml);

        return new Response($content, 200, array('content-type' => 'application/pdf'));
    }

    /**
     * @Route ("/admin/dashboard/factures/{id}", name="pdfFactures")
     */
    public function adminPdfFactureAction($id)
    {

        $id = $this->getRequest()->get('id');
        $FactureInfo = $this->getFactureInfo($id);
        if($FactureInfo==null) return $this->redirect($this->generateUrl('index'));
        $CommandesInfo = $this->getCommandesInfo($id);
        $MT = $this->getMT($CommandesInfo);
        $MTP = $this->getMTP($MT);
        $facade = $this->get('ps_pdf.facade');
        $response = new Response();
        $this->render('Touch3dAdminBundle:Facturer:pdfFacture.pdf.twig', array('facture' => $FactureInfo,'commandes' => $CommandesInfo,'MT' => $MT,'MTPaye' => $MTP), $response);
        $xml = $response->getContent();
        $content = $facade->render($xml);

        return new Response($content, 200, array('content-type' => 'application/pdf'));
    }
}
