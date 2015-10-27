<?php
/**
 * Created by julien.
 * User: julien
 * Date: 19/02/15
 * Time: 11:21
 */

namespace BOR\GamificationBundle\Manager;
use Doctrine\ORM\EntityManager;
use BOR\GamificationBundle\Manager\BaseManager as BaseManager;
use BOR\GamificationBundle\Entity\Level;

/**
 * Classe permettant de gérer les actions sur les levels un peu compliquées
 *
 * Class LevelManager
 *
 * @package BORGamificationBundleManager_Manager
 *
 */
class LevelManager extends BaseManager
{

    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Sauvegarde le niveau et répercute le changement d'expérience max sur les niveaux suivants
     *
     * @param Level $level
     */
    public function saveLevel(Level $level)
    {
        $adjacentLevels = $this->getPreviousAndNextLevel($level);
        $nextLevel = $adjacentLevels["next"];

        $previousLevel = $adjacentLevels["prev"];
        //Si le niveau est le premier, alors on se base sur 0 et non pas sur le niveau précédent
        if (!isset($previousLevel)) {
            $level->setExperienceMax($level->getExperienceRequired());
        } else {
            $level->setExperienceMin($previousLevel->getExperienceMax() + 1);
            $level->setExperienceMax($level->getExperienceRequired() + $previousLevel->getExperienceMax() + 1);
        }

        //Persist de l'entité actuelle
        $this->persistAndFlush($level);

        //If de récursivité pour traiter tous les niveaux suivants
        if (isset($nextLevel)) {
            //On appelle la fonction sur le niveau suivant
            $this->saveLevel($nextLevel);
        }
    }

    /**
     * Renvoi l'entité level précédente
     *
     * @param Level $level
     *
     * @return Level
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     */
    public function getPreviousLevel($level)
    {

        return $this->getRepository()
            ->getPreviousOrNextLevel($level->getLevel(), "prev")
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * Renvoi l'entité level suivant
     *
     * @param Level $level
     *
     * @return Level
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     */
    public function getNextLevel($level)
    {

        return $this->getRepository()
            ->getPreviousOrNextLevel($level->getLevel(), "next")
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * Renvoi dans une array les 2 niveaux autour de celui passé
     *
     * @param Level $level
     *
     * @return array
     *
     */
    public function getPreviousAndNextLevel($level)
    {

        return array(
            'prev' => $this->getPreviousLevel($level),
            'desk' => $level,
            'next' => $this->getNextLevel($level)
        );
    }

    /**
     * Renvoi le prochain niveau à créer en int.
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     */
    public function getNextLevelNumber()
    {
        $result =  $this->getRepository()
            ->getLevelMax()
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();

        $nextLevel = $result['max_level'];

        if (isset($nextLevel)) {

            return $nextLevel + 1 ;
        } else {

            return 0 + 1;
        }
    }

    /**
     * @return \BOR\GamificationBundle\Entity\LevelRepository
     *
     */
    public function getRepository()
    {

        return $this->em->getRepository('BORGamificationBundle:Level');
    }

}
