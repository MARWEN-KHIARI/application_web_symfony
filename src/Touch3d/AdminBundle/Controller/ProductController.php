<?php

namespace Touch3d\AdminBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Touch3d\AdminBundle\Entity\Produit;
use Touch3d\AdminBundle\Entity\Categorie;
use Touch3d\AdminBundle\Entity\Fournisseur;
use Touch3d\AdminBundle\Form\ProduitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ProductController extends Controller
{


    /**
     * @Route("/admin/dashboard/product",name="product")
     * @Template()
     */
    public function productAction(Request $request)
    {
        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        $prd = new Produit();
        $form = $this->createForm(new ProduitType(), $prd);
        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        return array('form' => $form->createView(), 'user' => $user, 'transLib' => $this->t('Product'));
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
     * @Route("/admin/dashboard/product/ajax/_deleteProduct",name="_deleteProduct")
     */
    public function _deleteProductAction()
    { //espace-client
        $id = $this->getRequest()->get('id');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Produit');
        $product = $repository->findOneById($id);

        if ($product != null) {
            $em->remove($product);
            $em->flush();

            $response = new Response('<div class="alert alert-success">' . $this->tc('The %lb% of this id :',$this->t('Product'),'%lb%').$id.' '.$this->t('is removed!') . '</div>');
        } else {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Product'),'%lb%') . $id . '</div>');
        }
        return new Response($response);
    }

    /**
     * @Route("/admin/dashboard/product/ajax/_selectXProduct",name="_selectXProduct")
     */
    public function _selectXProductAction()
    {
        $id = $this->getRequest()->get('id');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Produit');
        $product = $repository->findOneById($id); //->findOneBy(array('number' => $number))->findAll();->findOneById($id);

        $prd = new Produit();
        if ($product == null) {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Product'),'%lb%') . $id . '</div>');
        } else {
            $html = "";
            $html .= "<b class='lab0'>Nom </b><hr/> <p>" . htmlspecialchars($product->getNom(), ENT_QUOTES) . "</p><br/>";
            if($product->getImg()!=null){
            $html .= "<br/><b class='lab0'>Image </b><hr/> <p><img src='" . htmlspecialchars($product->getImg(), ENT_QUOTES) . "' class='img-polaroid'/></p><br/>";
            }
            $html .= "<br/><b class='lab0'>Prix </b><hr/> <p>" . html_entity_decode(htmlspecialchars($product->getPrix(), ENT_QUOTES)) . "</p><br/>";
            $html .= "<br/><b class='lab0'>Quantité en stock </b><hr/> <p>" . html_entity_decode(htmlspecialchars($product->getQteStock(), ENT_QUOTES)) . "</p><br/>";
            $fournisseur=$product->getFournisseur();
            if($fournisseur!=null){
            $html .= "<br/><b class='lab0'>Nom Fournisseur </b><hr/> <p>" . html_entity_decode(htmlspecialchars($fournisseur->getNom(), ENT_QUOTES)) . "</p><br/>";
                $html .= "<br/><b class='lab0'>Adresse Fournisseur </b><hr/> <p>" . html_entity_decode(htmlspecialchars($fournisseur->getAdresse(), ENT_QUOTES)) . "</p><br/>";
            }
            $category=$product->getCategorie();
            if($category!=null){
                $html .= "<br/><b class='lab0'>Categorie </b><hr/> <p>" . html_entity_decode(htmlspecialchars($category->getNom(), ENT_QUOTES)) . "</p><br/>";
            }
            $html .= "<br/><b class='lab0'>Status du produit </b><hr/> <p>" . htmlspecialchars($product->getStatus(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>Resumer </b><hr/> <p>" . htmlspecialchars($product->getResumer(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>Contenu </b><hr/> <p>" . html_entity_decode(htmlspecialchars($product->getContenu(), ENT_QUOTES)) . "</p><br/>";

            $response = new Response($html);
        }
        return $response;
    }

    /**
     * @Route("/admin/dashboard/product/ajax/_selectAllProduct",name="_selectAllProduct")
     */
    public function _selectAllProductAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Produit');
        $products = $repository->findAll();
        //->getArticles(3, $page);
        $prd = new Produit();
        $form = $this->createForm(new ProduitType(), $prd);
        $response = '';
        $i = 0;
        foreach ($products as $product) {
            $response .= $this->fluxView($product);
            $i++;
        }
        $request = $this->container->get('request');
        $session = $request->getSession();
        $session->set('productNb', '' . $i);

        return new Response($response);
    }

    /**
     * @Route("/admin/dashboard/product/ajax/_viewPaginatedProduct",name="_viewPaginatedProduct")
     */
    public function _viewPaginatedProductAction()
    {
        $nombreParPage = $this->get('session')->get('pagination', 3);
        $pageActive = $this->getRequest()->get('pageActive');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:Produit p
            ORDER BY p.id DESC'
        )
            ->setFirstResult(($pageActive - 1) * $nombreParPage)
            ->setMaxResults($nombreParPage);
        $products = $query->getResult();

        $form = $this->createForm(new ProduitType(), new Produit());

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
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Produit');
        $products = $repository->findAll();
        $SearchDataList = '';
        foreach ($products as $product) {
            $SearchDataList .= '<option value=\"' . ($product->getNom()) . '\">Nom</option>';
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
            '<td><img src="' . ($product->getImg()) . '" width="70px"/></td>' .
            '<td>' . ($product->getStatus()) . '</td>' .
            '<td><div class="btn-group">' .
            '<a class="btn" href="javascript:viewProduct(' . $product->getId() . ');">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('View Details').'" class="icon-eye-open"></i> </a>' .
            '<a class="btn" href="' . 'product/edit/' . $product->getId() . '" target="_blank">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('Edit Informations').'" class="icon-pencil"></i> </a>' .
            '<a class="btn" href="javascript:bootbox.confirm(&quot;'.$this->t('Are you sure?').'&quot;, function(result){if(result)deleteProduct(' . $product->getId() . ');});">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('Delete Data').'" class="icon-trash"></i></a>'
            . '</div></td></form>';
        $data .= "</tr>";
        return $data;
    }


    /**
     * @Route("/admin/dashboard/product/ajax/_statisticProduct",name="_statisticProduct")
     */
    public function _statisticProductAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT COUNT(p.id)
              FROM Touch3dAdminBundle:Produit p'
        );
        $query = $query->getSingleResult();
        $productNb = $query[1];
        //->setParameter('req1', '%' . $req1 . '%');
        $req1 = "draft";
        $req2 = "revision";
        $req3 = "publish";

        $query = $em->createQuery(
            'SELECT COUNT(p.id)
            FROM Touch3dAdminBundle:Produit p
            WHERE p.status like :req1'
        )->setParameter('req1', $req1);
        $query = $query->getSingleResult();
        $productND = $query[1];

        $query = $em->createQuery(
            'SELECT COUNT(p.id)
            FROM Touch3dAdminBundle:Produit p
            WHERE p.status like :req2'
        )->setParameter('req2', $req2);
        $query = $query->getSingleResult();
        $productNR = $query[1];

        $query = $em->createQuery(
            'SELECT COUNT(p.id)
            FROM Touch3dAdminBundle:Produit p
            WHERE p.status like :req3'
        )->setParameter('req3', $req3);
        $query = $query->getSingleResult();
        $productNP = $query[1];

        $request = $this->container->get('request');
        $session = $request->getSession();
        $session->set('productNb', $productNb);

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
        return '<p>'.$this->tc('Number of all %lb%',$this->t('Product'),'%lb%').' <span class=\"badge\">' . $i . '</span></p>';
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
     * @Route("/admin/dashboard/product/ajax/_paginateProduct",name="_paginateProduct")
     */
    public function _paginateProductAction()
    {
        $nombreParPage = $this->get('session')->get('pagination', 3);
        $pageActive = $this->getRequest()->get('pageActive');

        $request = $this->container->get('request');
        $session = $request->getSession();
        $productNb = $session->get('productNb');


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
     * @Route("/admin/dashboard/product/create",name="createProduct")
     * @Template()
     */
    public function createAction()
    {
        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        $prd = new Produit();
        $form = $this->createForm(new ProduitType(), $prd);


        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        $images = $this->selectAllImages();
        return array('form' => $form->createView(), "images" => $images, 'user' => $user, 'transLib' => $this->t('Product'));

    }


    /**
     * @Route("/admin/dashboard/product/edit/{id}",name="editProduct")
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
            $repository = $em->getRepository('Touch3dAdminBundle:Produit');
            $product = $repository->findOneById($id);
        } else $product = null;
        $prd = new Produit();

        $form = $this->createForm(new ProduitType(), $prd);

        $form->get('nom')->setData($product->getNom());
        $form->get('img')->setData($product->getImg());
        $form->get('resumer')->setData($product->getResumer());
        $form->get('contenu')->setData($product->getContenu());
        $category=$product->getCategorie();
        if($category!=null)$category=$category->getNom();
        else $category="null";

        $form->get('prix')->setData($product->getPrix());
        $form->get('qteStock')->setData($product->getQteStock());
        $fournisseur=$product->getFournisseur();
        if($fournisseur!=null)$fournisseur=$fournisseur->getNom();
        else $fournisseur="null";



        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        $images = $this->selectAllImages();
        return array('Produit' => $product, "images" => $images, 'form' => $form->createView(), 'user' => $user,'category' => $category, 'transLib' => $this->t('Product'));
    }

    function selectAllImages()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Image');
        return $repository->findAll();
    }

    /**
     * @Route("/admin/dashboard/product/ajax/_editProduct",name="_editProduct")
     */
    public function _editProductAction()
    {

        $id = $this->getRequest()->get('id');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Produit');
        $product = $repository->findOneById($id);
        if (!$product) {
            return new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Product'),'%lb%') . $id . '</div>');
        }
        $nom = $this->getRequest()->get('nom');
        $product->setNom($nom);
        $img = $this->getRequest()->get('img');
        $product->setImg($img);
        $resumer = $this->getRequest()->get('resumer');
        $product->setResumer($resumer);
        $contenu = $this->getRequest()->get('contenu');
        $product->setContenu($contenu);
        $status = $this->getRequest()->get('status');
        $product->setStatus($status);
        $category=$this->getRequest()->get('categorie');
        $categorie = $em->getRepository('Touch3dAdminBundle:Categorie')->findOneById($category);
        $product->setCategorie($categorie);


        $prix = $this->getRequest()->get('prix');
        $product->setPrix($prix);
        $qteStock = $this->getRequest()->get('qteStock');
        $product->setQteStock($qteStock);

        $fournisseur=$this->getRequest()->get('fournisseur');
        $em = $this->getDoctrine()->getManager();
        $fournisseur = $em->getRepository('Touch3dAdminBundle:Fournisseur')->findOneById($fournisseur);
        $product->setFournisseur($fournisseur);

        //$em->persist($product);
        $em->flush();

        return new Response('<div class="alert alert-success">'.$this->tc('Id of the modified %lb% is: ',$this->t('Product'),'%lb%') . $id. '</div>');
    }


    /**
     * @Route("/admin/dashboard/product/ajax/_newProduct",name="_newProduct")
     */
    public function _newProductAction()
    {
        $product = new Produit();
        $nom = $this->getRequest()->get('nom');
        $product->setNom($nom);
        $img = $this->getRequest()->get('img');
        $product->setImg($img);
        $resumer = $this->getRequest()->get('resumer');
        $product->setResumer($resumer);
        $contenu = $this->getRequest()->get('contenu');
        $product->setContenu($contenu);
        $status = $this->getRequest()->get('status');
        $product->setStatus($status);
        $category=$this->getRequest()->get('categorie');
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('Touch3dAdminBundle:Categorie')->findOneById($category);
        $product->setCategorie($categorie);


        $prix = $this->getRequest()->get('prix');
        $product->setPrix($prix);
        $qteStock = $this->getRequest()->get('qteStock');
        $product->setQteStock($qteStock);

        $fournisseur=$this->getRequest()->get('fournisseur');
        $em = $this->getDoctrine()->getManager();
        $fournisseur = $em->getRepository('Touch3dAdminBundle:Fournisseur')->findOneById($fournisseur);
        $product->setFournisseur($fournisseur);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response('<div class="alert alert-success">'.$this->tc('Id of the new %lb% is: ',$this->t('Product'),'%lb%') . $product->getId(). '</div>');
    }

    /**
     * @Route("/admin/dashboard/product/ajax/_findProduct",name="_findProduct")
     */
    public function _findProductAction()
    {

        $req = $this->getRequest()->get('req');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:Produit p
             WHERE (p.id like :req)OR(p.nom like :req)OR(p.img like :req)OR(p.resumer like :req)OR(p.status like :req)
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

        if ($products == null) $response = "<tr><td colspan='5'><div class='alert alert-warning'>". $this->tc('No %lb% found for this request : ',$this->t('Product'),'%lb%') . $req . "</div></td></tr>";

        $request = $this->container->get('request');
        $session = $request->getSession();
        $productNb=$session->get('productNb', 0);

        $data_info = '' .
            '<p>'.$this->tc('Number %lb% founded',$this->t('Product'),'%lb%').' <span class=\"badge badge-important\">' . $founded . '</span></p>'.
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

    /**
     * @Route("/admin/dashboard/category/new",name="_newCat")
     */
    public function _newCatAction()
    {

        $cat = new Categorie();
        $nom = $this->getRequest()->get('nom');
        $cat->setNom($nom);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cat);
        $em->flush();

        return new Response('<script>reloadTimed();</script><div class="alert alert-success">the new Category is created</div>');
    }

    /**
     * @Route("/admin/dashboard/category/delete",name="_deleteCat")
     */
    public function _deleteCatAction()
    {
        $id = $this->getRequest()->get('id');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Categorie');
        $cat = $repository->findOneById($id);

        if ($cat != null) {
            $em->remove($cat);
            $em->flush();

            $response = new Response('<script>reloadTimed();</script><div class="alert alert-success">' . $this->tc('The %lb% of this id :',$this->t('Category'),'%lb%').$id.' '.$this->t('is removed!') . '</div>');
        } else {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Category'),'%lb%') . $id . '</div>');
        }
        return new Response($response);

    }

    /**
     * @Route("/admin/dashboard/fournisseur/new",name="_newFournisseur")
     */
    public function _newFournisseurAction()
    {

        $fourniss = new Fournisseur();
        $nom = $this->getRequest()->get('nom');
        $fourniss->setNom($nom);

        $adresse = $this->getRequest()->get('adresse');
        $fourniss->setAdresse($adresse);

        $em = $this->getDoctrine()->getManager();
        $em->persist($fourniss);
        $em->flush();

        return new Response('<script>reloadTimed();</script><div class="alert alert-success">fournisseur crée</div>');
    }

    /**
     * @Route("/admin/dashboard/fournisseur/delete",name="_deleteFournisseur")
     */
    public function _deleteFournisseurAction()
    {
        $id = $this->getRequest()->get('id');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Fournisseur');
        $fourniss = $repository->findOneById($id);

        if ($fourniss != null) {
            $em->remove($fourniss);
            $em->flush();

            $response = new Response('<script>reloadTimed();</script><div class="alert alert-success">fournisseur supprimé</div>');
        } else {
            $response = new Response('<div class="alert alert-warning">fournisseur ne peut pas etre supprimé</div>');
        }
        return new Response($response);

    }
} 