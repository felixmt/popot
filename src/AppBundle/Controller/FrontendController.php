<?php
/**
*   Frontend Controller
*
*   PHP version 5.5.12
*
*   @category  PHP
*   @package   Cmsundle
*   @author    Felix MOTOT <felix@motot.fr>
*   @copyright 2015 Félix Motot
*   @license   Sopra http://choosealicense.com/licenses/bsd-2-clause/
*   @link      http://burgundywineschool.com
*/

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;

/**
* Frontend controller
*
* @category  PHP
* @package   Trunkadmin
* @author    Felix MOTOT <felix@motot.fr>
* @copyright 2015 Félix Motot
* @license   Sopra http://choosealicense.com/licenses/bsd-2-clause/
* @link      http://soprasteria.com  
*/
class FrontendController extends Controller
{
    /**
    * Display galleries
    *
    * @param string $templateName name of template to be displayed
    * @param string $route        page asked
    *
    * @return void
    */
    public function indexAction($templateName, $route = null)
    {    
        $repository = $this->getDoctrine()->getManager()->getRepository(
            'AppBundle:Gallery'
        );
        $galleries = $repository->FindAll();
        
        if (isset($route)) {
            $gallery = $repository->findOneByRoute($route);
            if ($gallery == null) {
                $gallery = $repository->findOneByIsHomeGallery(1);
            }
        } else {
            $gallery = $repository->findOneByIsHomeGallery(1);
        }
        $finder = new Finder();
        $finder->files()->in('uploads/' . $gallery->getFolder());
        $content = array();
        foreach ($finder as $file) {
            $content[] = $file->getRelativePathname();
        }            
        
        return $this->render(
            'AppBundle:Frontend:' . $templateName . '.html.twig', array(
                'galleries' => $galleries, 'content' => $content, 'gallery' => $gallery,
            )
        );
    }
    
    /**
    * Display contact page
    *
    * @param string $templateName name of template to be displayed
    *
    * @return void
    */
    public function contactAction($templateName) {
        $repository = $this->getDoctrine()->getManager()->getRepository(
            'AppBundle:Gallery'
        );
        $galleries = $repository->FindAll();
        
        return $this->render(
            'AppBundle:Frontend:' . $templateName . '.html.twig', array(
                'galleries' => $galleries, /*'content' => $content,*/
            )
        );
    }
}
