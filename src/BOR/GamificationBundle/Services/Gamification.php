<?php

namespace BOR\GamificationBundle\Services;

use BOR\GamificationBundle\BORGamificationBundle;
use BOR\UserBundle\UserBundle;
use Doctrine\ORM\EntityManager;
use BOR\GamificationBundle\Entity\Statistics;
use BOR\GamificationBundle\Entity\Action;
use Misd\GuzzleBundle\MisdGuzzleBundle;
use Misd\GuzzleBundle\Request\ParamConverter\GuzzleParamConverter3x;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Service pour les actions de gamification
 *
 * Class Gamification
 *
 * @package BOR\GamificationBundle\Services
 *
 */
class Gamification
{

    /**
     * @var EntityManager
     *
     */
    protected $em;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Fonction appelée pour mettre à jour les statistiques de l'utilisateur en fonction de l'action. Retourne la liste des gains de l'utilisateur
     *
     * @param \BOR\UserBundle\Entity\User   $user
     * @param string                        $actionName
     *
     * @return array liste des gains
     *
     */
    public function gammificationAction($user, $actionName)
    {
        if (!isset($user)) {
            throw new NotFoundResourceException('Unable to find User entity.');
        }

        $action = $this->em->getRepository("BORGamificationBundle:Action")->findOneBy(array("name" => $actionName));
        //Vérification de l'existence de l'action
        if (!isset($action)) {
            throw new NotFoundResourceException('Unable to find Action entity.');
        }

        //Récupération de la ligne de stat à modifier
        $statistics = $this->em->getRepository("BORGamificationBundle:Statistics")->findOneBy(array("user" => $user));

        //Vérification de son existence
        if (!isset($statistics)) {
            throw new NotFoundResourceException('Unable to find Statistics entity.');
        }

        //Détermination de l'expérience qui va être gagnée
        $experienceEarned = $action->getExperience() + rand($action->getExperienceMin(), $action->getExperienceMax());

        //Détermination des crédits qui vont être gagnés
        $creditEarned = $action->getCredit() + rand($action->getCreditMin(), $action->getCreditMax());

        //Si level up avec la nouvelle expérience alors on monte le niveau
        $hasLevelUp = $this->hasLevelUp($statistics, $experienceEarned);
        if ($hasLevelUp) {
            $level = $this->getCurrentLevelInfo($statistics);
            $statistics->setCredit($statistics->getCredit() + $level->getCreditReward());
            $statistics->setLevel($statistics->getLevel() + 1);
        }

        //Mise à jour des nouvelles stats
        $statistics->setExperience($statistics->getExperience() + $experienceEarned);
        $statistics->setCredit($statistics->getCredit() + $creditEarned);

        $this->em->persist($statistics);
        $this->em->flush();

        return array(
            "experience" => $experienceEarned,
            "credit" => $creditEarned,
            "levelup" => $hasLevelUp
        );
    }

    /**
     * Retourne les informations sur level en cours
     *
     * @param Statistics $statistics
     *
     * @return \BOR\GamificationBundle\Entity\Level $level
     *
     */
    public function getCurrentLevelInfo($statistics)
    {

        $currentLevel = $statistics->getLevel();
        $level = $this->em->getRepository("BORGamificationBundle:Level")->findOneBy(array("level" => $currentLevel));

        return $level;
    }

    /**
     * Retourne si l'utilisateur a monté de niveau avec l'expérience gagnée.
     *
     * @param Statistics    $statistics
     * @param integer       $experienceEarned
     *
     * @return bool
     *
     */
    private function hasLevelUp($statistics, $experienceEarned)
    {
        $currentLevel = $statistics->getLevel();
        $currentExperience = $statistics->getExperience() + $experienceEarned;

        $level = $this->getCurrentLevelInfo($statistics);

        //Vérification de la présence d'un niveau suivant.
        $nextLevel = $this->em->getRepository("BORGamificationBundle:Level")->findBy(array("level" => $currentLevel+1));

        if (isset($nextLevel) && count($nextLevel) > 0 && isset($level) && $currentExperience > $level->getExperienceMax()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $user
     * @param $courses
     */
    public function updateCourses($user, $courses)
    {
        $statistics = $this->em->getRepository('BORGamificationBundle:Statistics')->findOneBy(array('user' => $user));
        $statistics->setCourses(count($courses));
        $this->em->persist($statistics);
        $this->em->flush();
    }

    /**
     * @param $user
     * @param $courses
     */
    public function updateQcmPoints($user, $courses)
    {
        //Get repositories
        $statistics = $this->em->getRepository('BORGamificationBundle:Statistics')->findOneBy(array('user' => $user));
        $qcmPoints = $this->em->getRepository('BORGamificationBundle:QcmPoint')->findOneBy(array('qcmPoints' => 1));

        //Set reference values
        $expPerPoint = $qcmPoints->getExperience();
        $coursesDone = $statistics->getCourses();
        $points = 0;
        $coursesCount = count($courses);

        //Add points with new points got
        if ($coursesDone < $coursesCount) {
                for($i = $coursesCount - 1; $coursesDone <= $i; $i-- ) {
                    $mark = $courses[$i]['course_mark'];
                    $points += $mark * $expPerPoint;
                }

            $hasLevelUp = $this->hasLevelUp($statistics, $points);
            if ($hasLevelUp) {
                $statistics->setLevel($statistics->getLevel() + 1);
            }

            $statistics->setExperience($statistics->getExperience() + $points);
            $this->em->persist($statistics);
            $this->em->flush();
        }
    }

    /**
     * @param \BOR\UserBundle\Entity\User $user
     */
    public function minusCreditMonth($user)
    {
        $statistics = $this->em->getRepository('BORGamificationBundle:Statistics')->findOneBy(array('user' => $user));
        $newCredit = $statistics->getCredit() - 800;
        $statistics->setCredit($newCredit);
        $this->em->persist($statistics);
        $this->em->flush();
    }

    /**
     * @param \BOR\UserBundle\Entity\User $user
     */
    public function minusCreditYear($user)
    {
        $statistics = $this->em->getRepository('BORGamificationBundle:Statistics')->findOneBy(array('user' => $user));
        $newCredit = $statistics->getCredit() - 8000;
        $statistics->setCredit($newCredit);
        $this->em->persist($statistics);
        $this->em->flush();
    }

    /**
     * Retourne les statistiques de l'utilisateur
     *
     * @param \BOR\UserBundle\Entity\User $user
     *
     * @return Statistics
     *
     */
    public function getUserStatistics($user)
    {
        $statistics = $this->em->getRepository('BORGamificationBundle:Statistics')->findOneBy(array('user' => $user));

        return $statistics;
    }

    /**
     * retourne le rang de l'utilisateur avec son classement et le total des entrées
     *
     * @param \BOR\UserBundle\Entity\User $user
     *
     * @return array
     *
     */
    public function getUserRank($user)
    {
        $totalEntries = $this->em->getRepository("BORGamificationBundle:Statistics")->getTotalEntities();
        $rank = $this->em->getRepository("BORGamificationBundle:Statistics")->getUserRank($user);

        return array(
            "rank" =>  $rank,
            "totalEntries" => $totalEntries
        );
    }

    /**
     * retourne le rang de l'utilisateur avec son classement et le total des entrées
     *
     * @param \BOR\UserBundle\Entity\User $user
     *
     * @return array
     *
     */
    public function getUserCourses($user, $getList)
    {
        $rank = $this->em->getRepository("BORGamificationBundle:Statistics")->getUserRank($user);


        return array(
            "rank" =>  $rank,
            "totalEntries" => $totalEntries
        );
    }
}
