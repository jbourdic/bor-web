<?php

namespace BOR\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class MoocController
 *
 * @package BOR\ApiBundle\Controller
 */
class MoocController extends FOSRestController
{
    /**
     * @param Request $request
     *
     * @return \BOR\UserBundle\Entity\User
     *
     * @Post("/user")
     * @View()
     *
     * @RequestParam(name="sharedKey", requirements=".+", description="")
     *
     * @ApiDoc(
     *  section="Mooc",
     *  description="Permet d'obtenir les données de l'utilisateur",
     *  statusCodes={
     *      200="Returned when successful",
     *      403="Returned when bad shared key",
     *      404="Returned when user not found"
     *  }
     * )
     */
    public function getUserAction(Request $request)
    {
        $mup = $this->container->get('bor_core.mooc_provider');
        $tokenMooc = $request->get('tokenMooc');
        if ($mup->getSharedKey() != $request->get('sharedKey')) {
            throw new AccessDeniedException('Bad Shared Key');
        }
        $em = $this->getDoctrine();
        $user = $em->getRepository('UserBundle:User')->findOneByTokenMooc($tokenMooc);
        if (!is_object($user)) {
            throw $this->createNotFoundException();
        }

        return $user;
    }
}
