<?php

namespace BOR\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BOR\CoreBundle\Entity\Slider;
use BOR\CoreBundle\Entity\SliderRepository;
use Symfony\Component\HttpFoundation\Request;
use BOR\AdvertBundle\Form\FilterType;
/**
 * Class CoreController
 *
 * @package BOR\CoreBundle\Controller
 */
class CoreController extends Controller
{
    /**
     * @return array
     *
     * @Route("/", name="bor_core_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BORCoreBundle:Slider')->findAllBySlideOrderAndActive();
        $form = $this->createForm(new FilterType());

        return array(
            'entities' => $entities,
            'form_filter' => $form->createView()
        );
    }

    /**
     * @return array
     *
     * @Route("/", name="bor_core_header")
     * @Template()
     */
    public function headerAction()
    {
        $mooc = $this->getMoocInfo();

        return array('mooc' => $mooc);
    }
    /**
     * @return array
     *
     * @Route("/", name="bor_core_sidebar")
     * @Template()
     */
    public function sidebarAction()
    {
       $mooc = $this->getMoocInfo();

        return array('mooc' => $mooc);
    }

    /**
     * @Route("/contact")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array
     */
    public function contactAction()
    {
        return array();
    }

    /**
     * @Route("/concept", name="bor_core_concept")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array
     */
    public function conceptAction()
    {
        return array();
    }

    /**
     * @Route("/cgu", name="bor_core_cgu")
     * @Method({"GET", "POST"})
     * @Template()
     * @return array
     */
    public function cguAction()
    {
        return array();
    }

    /**
     * @Route("/mentions-legales", name="bor_core_mentionsLegales")
     * @Template()
     * @return array
     */
    public function mentionsLegalesAction()
    {
        return array();
    }

    /**
     * @Route("/plan-du-site", name="bor_core_sitemap")
     * @Template()
     * @return array
     */
    public function sitemapAction()
    {
        return array();
    }

    /**
     * @Route("/dashboard"), name="bor_core_dashboard")
     * @template()
     *
     * @return array
     */
    public function dashboardAction()
    {

        //On vérifie que l'utilisateur est authentifié avant tout traitement
        if ($this->get('security.context')->isGranted('ROLE_USER')) {

            //Init du service gamification
            $gamification = $this->get("bor_core.gamification");
            $mooc = $this->get("bor_core.mooc_provider");
            $user = $this->getUser();
            $moocAccount = $user->getHasMoocAccount();
            if (isset($moocAccount) && $moocAccount != false) {

                //Calcul des cours vus
                $courses = $mooc->getUserCourses([
                    "userId" => $user->getId(),
                    "sharedKey" => $mooc->getSharedKey()
                ]);

                $challenges = $mooc->getUserChallenges([
                    "userId" => $user->getId(),
                    "sharedKey" => $mooc->getSharedKey()
                ]);

                $data = $courses->toArray();

                $gamification->updateQcmPoints($user, $data['courses']);
                $gamification->updateCourses($user, $data['courses']);
            }
            //Calcul du nombre de jours d'abonnement restant
            $now = new \DateTime('now');
            $subscribeEnd = $user->getSubscribeEnd();

            if ($subscribeEnd > $now) {
                $daysSubscription = $user->getSubscribeEnd()->diff($now)->days;
            } else {
                $daysSubscription = 0;
            }

            //Récupération de ses stats
            $dashboard = $gamification->getUserStatistics($user);
            //Calcul du ranking de l'utilisateur
            $rank = $gamification->getUserRank($user);

            //Récupération des information pour la barre de progression
            $levelInfo = $gamification->getCurrentLevelInfo($dashboard);

            return array(
                "dashboard" => $dashboard,
                "rank" => $rank,
                "subscribeDaysLeft" => $daysSubscription,
                "levelInfo" => $levelInfo
            );

        } else {
            return array();
        }
    }

    /**
     * Retourne les informations du mooc
     *
     * @return array
     */
    private function getMoocInfo()
    {
        $tokenMooc = '';
        $user = $this->getUser();
        if ($user) {
            $tokenMooc = $user->getTokenMooc();
        }
        $mup = $this->container->get('bor_core.mooc_provider');

        return array('mooc_enter_url' => $mup->getURL(), 'mooc_login_url' => $mup->getLoginURL(), 'token_mooc' => $tokenMooc);
    }
}
