<?php
/**
*   Backend controller
*
*   PHP version 5.5.12
*
*   @category  PHP
*   @package   AppBundle
*   @author    Felix MOTOT <felix@motot.fr>
*   @copyright 2015 Félix Motot
*   @license   Sopra http://choosealicense.com/licenses/bsd-2-clause/
*   @link      http://louisperrichet.com
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;
use AppBundle\Entity\Gallery;
use AppBundle\Form\Type\GalleryType;

/**
* Backend controller (create, index)
*
* @category  PHP
* @package   Trunkadmin
* @author    Felix MOTOT <felix@motot.fr>
* @copyright 2015 Félix Motot
* @license   Sopra http://choosealicense.com/licenses/bsd-2-clause/
* @link      http://soprasteria.com  
*/
class BackendController extends Controller
{
    /**
    * admin homepage
    *
    * @param string $templateName name of template to be displayed
    *
    * @return void
    */
    public function indexAction($templateName)
    {
        return $this->render('AppBundle:Backend:' . $templateName . '.html.twig');
    }
    
    /**
    * Display galleries list
    *
    * @param string $templateName name of template to be displayed
    *
    * @return void
    */
    public function galleryListAction($templateName)
    {    
        $repository = $this->getDoctrine()->getManager()->getRepository(
            'AppBundle:Gallery'
        );
        $galleries = $repository->FindAll();
        
        return $this->render(
            'AppBundle:Backend:' . $templateName . '.html.twig', array(
                'galleries' => $galleries,
            )
        );
    }
    
    /**
    * Create / Update a menuItem
    *
    * @param Request $request      form status
    * @param string  $templateName name of template to be displayed
    * @param int     $id           id of menuItem to be updated
    *
    * @return void
    */
    public function galleryNewAction(Request $request, $templateName, $id = null)
    {
        // chercher les dossiers du répertoire uploads pour faire une liste de choix dans le choix du dossier
        // $finder = new Finder();
        // $finder->folder()->in('uploads/');
        // print_r($finder);
        // die();
        $em = $this->getDoctrine()->getManager();
        if (!isset($id)) {
            $gallery = new Gallery();
        } else {
            $repository = $em->getRepository('AppBundle:Gallery');
            $gallery = $repository->findOneById($id);
        }
        // $form = $this->createForm('gallery', $gallery);
        $form = $this->createForm(new GalleryType(), $gallery);
        
        if ($form->handleRequest($request)->isValid()
        ) { // check if form has been submitted) {
            if ($gallery->getIsHomeGallery()) {
                $repository = $em->getRepository('AppBundle:Gallery');
                $galleries = $repository->FindAll();
                foreach ($galleries as $tmp) {
                    if ($tmp->getId() != $gallery->getId()) {
                        $tmp->setIsHomeGallery(0);
                        $em->persist($tmp);
                    }
                }
            }
            
            $em->persist($gallery);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add(
                'notice', 'Galerie enregistrée avec succès.'
            );
            if ($form->get('Enregistrer')->isClicked()) {
                
                return $this->redirect($this->generateUrl('backend_gallery_update', array('id' => $gallery->getId())));
            } else {

                return $this->redirect($this->generateUrl('backend_gallery_list'));
            }
        }
        
        return $this->render(
            'AppBundle:Backend:' . $templateName . '.html.twig', array(
                'form' => $form->createView(), 'gallery' => $gallery
            )
        );
    }
    
    /**
    * Delete a gallery
    *
    * @param int     $id           id of gallery to be updated
    * @param Request $request      request
    * @param string  $templateName name of template to be displayed
    *
    * @return void
    */
    public function galleryDeleteAction($id, Request $request, $templateName)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Gallery');
        $gallery = $repository->findOneById($id);
        if ($gallery != null) {
            $em->remove($gallery);
            $em->flush();
                
            $request->getSession()->getFlashBag()->add(
                'notice', 'Galerie supprimée avec succès.'
            );
        }
        
        return $this->redirect($this->generateUrl('backend_gallery_list'));
    }
    
    /**
    * Ajax deletion of menuItem
    *
    * @return void
    */
    public function galleryDeleteAjaxAction()
    {

        $request = $this->container->get('request');

        if ($request->isXmlHttpRequest()) {
            $id = '';
            $id = $request->request->get('id');
            
            if ($id != '') {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Gallery');
                $gallery = $repository->findOneById($id);
                if ($gallery != null) {
                    $em->remove($gallery);
                    $em->flush();
                    
                    return new JsonResponse(
                        array(
                            'isSuccess' => "1"
                        )
                    );
                }
            } else {
                
                return new JsonResponse(
                    array(
                        'isSuccess' => "0"
                        , "notice" => "Cette galerie n'existe pas
                            , veuillez actualiser la page et réessayer."
                    )
                );
            }
        } else {
            
            return new JsonResponse(
                array(
                    'isSuccess' => "0"
                    , "notice" => "Une erreur s'est produite, veuillez réessayer."
                )
            );
        }
    }
    
    /**
    * Upload pictures
    *
    * @param string $templateName name of template to be displayed
    *
    * @return void
    */
    public function uploadAction($templateName)
    {
        $form = $this->createFormBuilder()
            ->add('elfinder','elfinder', array('instance'=>'form', 'enable' => true))
            ->getForm();
    
        return $this->render(
            'AppBundle:Backend:' . $templateName . '.html.twig', array(
                'form' => $form->createView(),
            )
        );
    }
}
