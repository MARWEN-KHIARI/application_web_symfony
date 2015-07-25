<?php

namespace Touch3d\AdminBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Touch3d\AdminBundle\Entity\Article;
use Touch3d\AdminBundle\Form\ArticleType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ArticleController extends Controller
{


    /**
     * @Route("/admin/dashboard/article",name="article")
     * @Template()
     */
    public function articleAction(Request $request)
    {
        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        $prd = new Article();
        $form = $this->createForm(new ArticleType(), $prd);
        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        return array('form' => $form->createView(), 'user' => $user, 'transLib' => $this->t('Article'));
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
     * @Route("/admin/dashboard/article/ajax/_deleteArticle",name="_deleteArticle")
     */
    public function _deleteArticleAction()
    { //espace-client
        $id = $this->getRequest()->get('id');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Article');
        $articles = $repository->findOneById($id);

        if ($articles != null) {
            $em->remove($articles);
            $em->flush();

            $response = new Response('<div class="alert alert-success">' . $this->tc('The %lb% of this id :',$this->t('Article'),'%lb%').$id.' '.$this->t('is removed!') . '</div>');
        } else {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Article'),'%lb%') . $id . '</div>');
        }
        return $response;
    }

    /**
     * @Route("/admin/dashboard/article/ajax/_selectXArticle",name="_selectXArticle")
     */
    public function _selectXArticleAction()
    {
        $id = $this->getRequest()->get('id');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Touch3dAdminBundle:Article');
        $articles = $repository->findOneById($id); //->findOneBy(array('number' => $number))->findAll();->findOneById($id);

        $prd = new Article();
        if ($articles == null) {
            $response = new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Article'),'%lb%') . $id . '</div>');
        } else {
            $html = "";
            $html .= "<b class='lab0'>Titre </b><hr/> <p>" . htmlspecialchars($articles->getNom(), ENT_QUOTES) . "</p><br/>";
            $html .= "<b class='lab0'>Date </b><hr/> <p>" . htmlspecialchars($articles->getDate()->format('d-m-Y'), ENT_QUOTES) . "</p><br/>";
            $html .= "<b class='lab0'>Lieu </b><hr/> <p>" . htmlspecialchars($articles->getLieu(), ENT_QUOTES) . "</p><br/>";
            if($articles->getImg() !=null)$html .= "<br/><b class='lab0'>Image </b><hr/> <p><img src='" . htmlspecialchars($articles->getImg(), ENT_QUOTES) . "' class='img-polaroid'/></p><br/>";
            $html .= "<br/><b class='lab0'>Resumer </b><hr/> <p>" . htmlspecialchars($articles->getResumer(), ENT_QUOTES) . "</p><br/>";
            $html .= "<br/><b class='lab0'>Contenu </b><hr/> <p>" . html_entity_decode(htmlspecialchars($articles->getContenu(), ENT_QUOTES)) . "</p><br/>";
            $html .= "<br/><b class='lab0'>Status du Article </b><hr/> <p>" . htmlspecialchars($articles->getStatus(), ENT_QUOTES) . "</p><br/>";
            $response = new Response($html);
        }
        return $response;
    }

    /**
     * @Route("/admin/dashboard/article/ajax/_selectAllArticle",name="_selectAllArticle")
     */
    public function _selectAllArticleAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Article');
        $articles = $repository->findAll();
        //->getArticle(3, $page);
        $prd = new Article();
        $form = $this->createForm(new ArticleType(), $prd);
        $response = '';
        $i = 0;
        foreach ($articles as $article) {
            $response .= $this->fluxView($article);
            $i++;
        }
        $request = $this->container->get('request');
        $session = $request->getSession();
        $session->set('articleNb', '' . $i);

        return new Response($response);
    }

    /**
     * @Route("/admin/dashboard/article/ajax/_viewPaginatedArticle",name="_viewPaginatedArticle")
     */
    public function _viewPaginatedArticleAction()
    {
        $nombreParPage = $this->get('session')->get('pagination', 3);
        $pageActive = $this->getRequest()->get('pageActive');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:Article p
            ORDER BY p.id DESC'
        )
            ->setFirstResult(($pageActive - 1) * $nombreParPage)
            ->setMaxResults($nombreParPage);
        $articles = $query->getResult();

        $form = $this->createForm(new ArticleType(), new Article());

        $response = '';
        $i = 0;
        foreach ($articles as $article) {
            $response .= $this->fluxView($article);
            $i++;
        }

        $response .= $this->fluxSearchDataList();
        return new Response($response);
    }

    function fluxSearchDataList()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Article');
        $articles = $repository->findAll();
        $SearchDataList = '';
        foreach ($articles as $article) {
            $SearchDataList .= '<option value=\"' . ($article->getNom()) . '\">';
        }
        $SearchDataList .= '<option value=\"----------------------------------------------------\"></option>';
        $SearchDataList .= '<option value=\"publish\">'.$this->t('publish').'</option>';
        $SearchDataList .= '<option value=\"revision\">'.$this->t('revision').'</option>';
        $SearchDataList .= '<option value=\"draft\">'.$this->t('draft').'</option>';
        $SearchDataList = '<script>$("#bib").html("' . $SearchDataList . '");</script>';
        return $SearchDataList;
    }


    function fluxView($article)
    {
        $data = "<tr>";
        $data .= '<form action="#" method="POST" target="_self" >' .
            '<td>' . ($article->getId()) . '</td>' .
            '<td>' . ($article->getNom()) . '</td>' .
            '<td>' . ($article->getDate()->format('d-m-Y')) . '</td>' .
            '<td>' . ($article->getStatus()) . '</td>' .
            '<td><div class="btn-group">' .
            '<a class="btn" href="javascript:viewArticle(' . $article->getId() . ');">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('View Details').'" class="icon-eye-open"></i> </a>' .
            '<a class="btn" href="' . 'article/edit/' . $article->getId() . '" target="_blank">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('Edit Informations').'" class="icon-pencil"></i> </a>' .
            '<a class="btn" id="522" href="javascript:bootbox.confirm(&quot;'.$this->t('Are you sure?').'&quot;, function(result){if(result)deleteArticle(' . $article->getId() . ');});">
<i rel="tooltip" onmouseover="$(this).tooltip(&#39;show&#39;);" data-original-title="'.$this->t('Delete Data').'" class="icon-trash"></i></a>'
            . '</div></td></form>';
        $data .= "</tr>";
        return $data;
    }


    /**
     * @Route("/admin/dashboard/article/ajax/_statisticArticle",name="_statisticArticle")
     */
    public function _statisticArticleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT COUNT(p.id)
    FROM Touch3dAdminBundle:Article p
    '
        );
        $query = $query->getSingleResult();
        $articleNb = $query[1];
        //->setParameter('req1', '%' . $req1 . '%');
        $req1 = "draft";
        $req2 = "revision";
        $req3 = "publish";

        $query = $em->createQuery(
            'SELECT COUNT(p.id)
            FROM Touch3dAdminBundle:Article p
            WHERE p.status like :req1'
        )->setParameter('req1', $req1);
        $query = $query->getSingleResult();
        $articleND = $query[1];

        $query = $em->createQuery(
            'SELECT COUNT(p.id)
            FROM Touch3dAdminBundle:Article p
            WHERE p.status like :req2'
        )->setParameter('req2', $req2);
        $query = $query->getSingleResult();
        $articleNR = $query[1];

        $query = $em->createQuery(
            'SELECT COUNT(p.id)
            FROM Touch3dAdminBundle:Article p
            WHERE p.status like :req3'
        )->setParameter('req3', $req3);
        $query = $query->getSingleResult();
        $articleNP = $query[1];

        $request = $this->container->get('request');
        $session = $request->getSession();
        $session->set('articleNb', $articleNb);

        $data_info = '' .
            $this->fluxStat_Publish($articleNP) .
            $this->fluxStat_Revision($articleNR) .
            $this->fluxStat_Draft($articleND) .
            $this->fluxStat_Normal($articleNb);

        $data = '<script>' .
            'var pieData = [' .
            '{ value : ' . $articleNP . ', color : "#3a87ad" },' .
            '{ value : ' . $articleNR . ', color : "#f89406" },' .
            '{ value : ' . $articleND . ', color : "#b94a48" }' .
            '];' .
            'var Pie_article = new Chart(document.getElementById("canvas_article").getContext("2d")).Pie(pieData);' .
            '$("#info_canvas_article").html("' . $data_info . '");' .
            '</script>';
        return new Response($data);
        //return new Response(var_dump($resp));

    }

    function fluxStat_Normal($i)
    {
        return '<p>'.$this->tc('Number of all %lb%',$this->t('Article'),'%lb%').' <span class=\"badge\">' . $i . '</span></p>';
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
     * @Route("/admin/dashboard/article/ajax/_paginateArticle",name="_paginateArticle")
     */
    public function _paginateArticleAction()
    {
        $nombreParPage = $this->get('session')->get('pagination', 3);
        $pageActive = $this->getRequest()->get('pageActive');

        $request = $this->container->get('request');
        $session = $request->getSession();
        $articleNb = $session->get('articleNb');


        $pageFirst = ($pageActive - 1) * $nombreParPage;
        $pageLast = ceil($articleNb / $nombreParPage);

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
        return '<a href="javascript:viewAllArticle(' . $p . ');">' . $s . '</a>';
    }

    /**
     * @Route("/admin/dashboard/article/create",name="createArticle")
     * @Template()
     */
    public function createAction()
    {
        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        $prd = new Article();
        $form = $this->createForm(new ArticleType(), $prd);


        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        $images = $this->selectAllImages();
        return array('form' => $form->createView(), "images" => $images, 'user' => $user, 'transLib' => $this->t('Article'));

    }


    /**
     * @Route("/admin/dashboard/article/edit/{id}",name="editArticle")
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
            $repository = $em->getRepository('Touch3dAdminBundle:Article');
            $article = $repository->findOneById($id);
        } else $article = null;
        $prd = new Article();
        $form = $this->createForm(new ArticleType(), $prd);

        $form->get('nom')->setData($article->getNom());
        $form->get('date')->setData($article->getDate());
        $form->get('lieu')->setData($article->getLieu());
        $form->get('img')->setData($article->getImg());
        $form->get('resumer')->setData($article->getResumer());
        $form->get('contenu')->setData($article->getContenu());

        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }
        $images = $this->selectAllImages();
        return array('Article' => $article, "images" => $images, 'form' => $form->createView(), 'user' => $user, 'transLib' => $this->t('Article'));
    }

    function selectAllImages()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Image');
        return $repository->findAll();
    }

    /**
     * @Route("/admin/dashboard/article/ajax/_editArticle",name="_editArticle")
     */
    public function _editArticleAction()
    {
        $id = $this->getRequest()->get('id');


                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('Touch3dAdminBundle:Article');
                $article = $repository->findOneById($id);
                if (!$article) {
                    return new Response('<div class="alert alert-warning">' . $this->tc('No %lb% found for this id : ',$this->t('Article'),'%lb%') . $id . '</div>');
                }

                $nom = $this->getRequest()->get('nom');
                $article->setNom($nom);
                $date = $this->getRequest()->get('date');
                //$article->setDate($date->format('Y-m-d'));format not work
                $article->setDate(new \DateTime($date));//DateTime to set the date
                $lieu = $this->getRequest()->get('lieu');
                $article->setLieu($lieu);
                $img = $this->getRequest()->get('img');
                $article->setImg($img);
                $resumer = $this->getRequest()->get('resumer');
                $article->setResumer($resumer);
                $contenu = $this->getRequest()->get('contenu');
                $article->setContenu($contenu);
                $status = $this->getRequest()->get('status');
                $article->setStatus($status);

                $em->persist($article);
                $em->flush();

        return new Response('<div class="alert alert-success">'.$this->tc('Id of the modified %lb% is: ',$this->t('Article'),'%lb%'). $id. '</div>');
    }


    /**
     * @Route("/admin/dashboard/article/ajax/_newArticle",name="_newArticle")
     */
    public function _newArticleAction()
    {

        $article = new Article();
        $nom = $this->getRequest()->get('nom');
        $article->setNom($nom);
        $date = $this->getRequest()->get('date');
        $article->setDate(new \DateTime($date));//DateTime to set the date
        $lieu = $this->getRequest()->get('lieu');
        $article->setLieu($lieu);
        $img = $this->getRequest()->get('img');
        $article->setImg($img);
        $resumer = $this->getRequest()->get('resumer');
        $article->setResumer($resumer);
        $contenu = $this->getRequest()->get('contenu');
        $article->setContenu($contenu);
        $status = $this->getRequest()->get('status');
        $article->setStatus($status);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
        return new Response('<div class="alert alert-success">'.$this->tc('Id of the new %lb% is: ',$this->t('Article'),'%lb%') . $article->getId(). '</div>');
    }

    /**
     * @Route("/admin/dashboard/article/ajax/_findArticle",name="_findArticle")
     */
    public function _findArticleAction()
    {

        $req = $this->getRequest()->get('req');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM Touch3dAdminBundle:Article p
             WHERE (p.id like :req)OR(p.nom like :req)OR(p.date like :req)OR(p.lieu like :req)OR(p.resumer like :req)OR(p.status like :req)
            ORDER BY p.id DESC'
        )->setParameter('req', '%' . $req . '%');
        //OR(p.contenu like :req)
        $articles = $query->getResult();

        $response = '';
        $founded = 0;
        foreach ($articles as $article) {
            $response .= $this->fluxView($article);
            $founded++;
        }

        if ($articles == null) $response = "<tr><td colspan='5'><div class='alert alert-warning'>". $this->tc('No %lb% found for this request : ',$this->t('Article'),'%lb%') . $req . "</div></td></tr>";

        $request = $this->container->get('request');
        $session = $request->getSession();
        $articleNb=$session->get('articleNb', 0);

        $data_info = '' .
            '<p>'.$this->tc('Number %lb% founded',$this->t('Article'),'%lb%').' <span class=\"badge badge-important\">' . $founded . '</span></p>'.
            $this->fluxStat_Normal($articleNb);

        $data = '<script>' .
            'var pieData = [' .
            '{ value : ' . $founded . ', color : "#b94a48" },' .
            '{ value : ' . ($articleNb-$founded) . ', color : "#3a87ad" }' .
            '];' .
            'var Pie_article = new Chart(document.getElementById("canvas_article").getContext("2d")).Pie(pieData);' .
            '$("#info_canvas_article").html("' . $data_info . '");' .
            '</script>';
        return new Response($response.$data);
    }

} 