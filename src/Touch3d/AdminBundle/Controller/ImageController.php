<?php

namespace Touch3d\AdminBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Touch3d\AdminBundle\Entity\Image;
use Touch3d\AdminBundle\Form\ImageType;

class ImageController extends Controller
{

    /**
     * @Route("/admin/dashboard/gallery", name="gallery")
     * @Template()
     */
    public function galleryAction()
    {
        $langue=$this->get('session')->get('_locale', 'en');
        $request = $this->getRequest();
        //$locale = $request->getLocale();
        $request->setLocale($langue);

        $user = $this->userdata();
        if ($user == null) {
            return $this->redirect($this->generateUrl('index'));
        }

        $image = new Image();
        $form = $this->createForm(new ImageType(), $image);
        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($image);
                $em->flush();
                $this->redirect($this->generateUrl("gallery"));
            }
        }
        $images = $this->selectAllImages();
        return array('form' => $form->createView(), "image" => $image, "images" => $images, 'user' => $user);
    }


    function selectAllImages()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('Touch3dAdminBundle:Image');
        return $repository->findAll();
    }

    function t($s){
        return $this->get('translator')->trans($s);
    }
    function userdata()
    { //'user' => $user
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            return null;
        }
        return $user;
    }

    /**
     * @Route("/admin/dashboard/gallery/ajax/_selectImages", name="_selectImages")
     */
    public function _selectImagesAction()
    {
        $images = $this->selectAllImages();
        $response = $this->fluxCatalogue($images);
        $response = new Response($response);
        return $response;
    }

    function fluxCatalogue($images)
    {

        //$url=$this->get('kernel')->getRootDir() . '/../web';
        //$url=realpath($url).'/';
        //$url = "http://localhost/application_web/web/";
        $catalogue =
            '<center>' .
            '<div id="catalogueImg">';
        $i = 0;
        foreach ($images as $image) {
            //$urlPic = $url . $image->getWebPath();
            // $image->getId().".".$image->getUrl()
           //$urlPic="/".$image->getId().".".$image->getUrl();
            $urlPic=$image->getWebPath();
            $imgId='im_'.$image->getId();
            $catalogue .= '<span class="blockImg img-polaroid">' .
                '<a onclick="getSelectedImage(\'' . $urlPic . '\', '. $image->getId() .' );">' .
                '<img id="'.$imgId.'" src="" alt="' . $image->getAlt() . '" width="100px" height="100px"/>' .

                '</a>' .
                '<script>$("#'.$imgId.'").attr("src",UrlRoot+"' . $urlPic . '");</script>'.
                '</span>';
            $i++;
            if (($i % 3) == 0) $catalogue .= '<br/>';
        }
        $catalogue .= '</div>' .
            '</center>';
        return $catalogue;
    }

    /**
     * @Route("/admin/dashboard/gallery/ajax/_uploadImage", name="_uploadImage")
     */
    public function _uploadImageAction()
    {


        $img = new Image();
        $form = $this->createForm(new ImageType(), $img);
        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                try
                {
                    $em->persist($img);
                    $em->flush();
                }
                catch (PropelException $e)
                {
                    new Response('<div class="alert alert-error">Image non ajouter '.$e.'</div>');
                }

            }
        }
        $response = new Response('<div class="alert alert-success">'.$this->t('Picture is added').'</div>');
        return $response;
    }

    /**
     * @Route("/admin/dashboard/gallery/ajax/_deleteImages",name="_deleteImages")
     */
    public function _deleteImagesAction()
    {
        $Id2delete = $this->getRequest()->get('Id2delete');

        $em = $this->getDoctrine()->getManager();


        foreach ($Id2delete as $id) {
            $repository = $em->getRepository('Touch3dAdminBundle:Image');
            $product = $repository->findOneById($id);
            if ($product != null) {
                $em->remove($product);
                $em->flush();
            }
        }

        $response = new Response('<div class="alert alert-warning">'.$this->t('Pictures are deleted').'</div>');

        return $response;
    }


}
