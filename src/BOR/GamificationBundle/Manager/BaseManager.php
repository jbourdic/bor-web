<?php
/**
 * Created by julien.
 * User: julien
 * Date: 19/02/15
 * Time: 11:17
 */

namespace BOR\GamificationBundle\Manager;

/**
 * Abstraite comprenant la sauvegarde pour soulage les controllers au niveau du nombre de lignes
 *
 * Class BaseManager
 *
 * @package BOR\GamificationBundle\Manager
 *
 */
abstract class BaseManager
{
    protected function persistAndFlush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
