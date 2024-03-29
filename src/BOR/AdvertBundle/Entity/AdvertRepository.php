<?php

namespace BOR\AdvertBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends EntityRepository
{
    /**
     * Fonction appelé à l'exécution du formulaire de filtrage
     * @param QueryBuilder $qb
     * @param array        $parameters
     *
     * @return array
     */
    public function filterFind(QueryBuilder $qb, array $parameters)
    {
        if (isset($parameters["priceType"]) && $parameters["priceType"] == "priceTTC") {
            // si prix min renseigné et prix max non renseigné
            if ($parameters["min"] != "" && $parameters["max"] == "") {
                $qb = $this->wherePriceMin($qb, $parameters["min"]);
            }
            // si prix min non renseigné et prix max renseigné
            if ($parameters["min"] == "" && $parameters["max"] != "") {
                $qb = $this->wherePriceMax($qb, $parameters["max"]);
            }
            // si prix min et prix max renseigné
            if ($parameters["min"] != "" && $parameters["max"] != "") {
                $qb = $this->wherePriceMinAndMax($qb, $parameters["min"], $parameters["max"]);
            }
            // si tri du - cher au + cher (prix TTC)
            if ($parameters["order"] == "ascPrice") {
                $qb = $this->orderPriceTtcAsc($qb);
            }
            // si tri du + cher au - cher (prix TTC)
            if ($parameters["order"] == "descPrice") {
                $qb = $this->orderPriceTtcDesc($qb);
            }
        }

        if (isset($parameters["priceType"]) && $parameters["priceType"] == "charges") {
            // si charges min renseigné et charges max non renseigné
            if ($parameters["min"] != "" && $parameters["max"] == "") {
                $qb = $this->whereChargesMin($qb, $parameters["min"]);
            }
            // si charges min non renseigné et charges max renseigné
            if ($parameters["min"] == "" && $parameters["max"] != "") {
                $qb = $this->whereChargesMax($qb, $parameters["max"]);
            }
            // si charges min et charges max renseigné
            if ($parameters["min"] != "" && $parameters["max"] != "") {
                $qb = $this->whereChargesMinAndMax($qb, $parameters["min"], $parameters["max"]);
            }
            // si tri du - cher au + cher (Charges)
            if ($parameters["order"] == "ascPrice") {
                $qb = $this->orderChargesAsc($qb);
            }
            // si tri du + cher au - cher (Charges)
            if ($parameters["order"] == "descPrice") {
                $qb = $this->orderChargesDesc($qb);
            }
        }

        if (isset($parameters["priceType"]) && $parameters["priceType"] == "tax") {
            // si tax min renseigné et tax max non renseigné
            if ($parameters["min"] != "" && $parameters["max"] == "") {
                $qb = $this->whereTaxMin($qb, $parameters["min"]);
            }
            // si tax min non renseigné et tax max renseigné
            if ($parameters["min"] == "" && $parameters["max"] != "") {
                $qb = $this->whereTaxMax($qb, $parameters["max"]);
            }
            // si tax min et tax max renseigné
            if ($parameters["min"] != "" && $parameters["max"] != "") {
                $qb = $this->whereTaxMinAndMax($qb, $parameters["min"], $parameters["max"]);
            }
            // // si tri du - cher au + cher (Tax)
            if ($parameters["order"] == "ascPrice") {
                $qb = $this->orderTaxAsc($qb);
            }
            // si tri du + cher au - cher (Tax)
            if ($parameters["order"] == "descPrice") {
                $qb = $this->orderTaxDesc($qb);
            }
        }

        // si code postal renseigné
        if (isset($parameters["zipCode"]) && $parameters["zipCode"] != "") {
            $qb = $this->whereLocalization($qb, $parameters["zipCode"]);
        }
        // si type de transaction renseigné
        if (isset($parameters["transactType"]) && $parameters["transactType"] != "all") {
            $qb = $this->whereTransactType($qb, $parameters["transactType"]);
        }
        // date de mise en ligne du plus récent au moins récent
        if (isset($parameters["order"]) && $parameters["order"] == "ascDate") {
            $qb = $this->orderUpdatedAsc($qb);
        }
        if (isset($parameters["order"]) && $parameters["order"] == "descDate") {
            $qb = $this->orderUpdatedDesc($qb);
        }
        $results = $qb->getQuery()
            ->getResult();

        return $results;
    }

    /**
     * @return QueryBuilder
     */
    public function filterInit()
    {
        $qb = $this->createQueryBuilder('a');

        return $qb;
    }

    /**
     * @param string $keywords
     *
     * @return array
     */
    public function searchFind($keywords)
    {
        $qb = $this->createQueryBuilder('a');
        $qb = $this->searchByKeywords($qb, $keywords);

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * @param QueryBuilder $qb
     * @param strin        $transactType
     *
     * @return QueryBuilder
     */
    public function whereTransactType(QueryBuilder $qb, $transactType)
    {
        $qb->andWhere('a.transactType = :transactType')
            ->setParameter('transactType', $transactType);

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param float        $priceMin
     *
     * @return QueryBuilder
     */
    public function wherePriceMin(QueryBuilder $qb, $priceMin)
    {
        $qb->andWhere('a.price >= :priceMin')
            ->setParameters(array('priceMin' => $priceMin));

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param float        $priceMax
     *
     * @return QueryBuilder
     */
    public function wherePriceMax(QueryBuilder $qb, $priceMax)
    {
        $qb->andWhere('a.price <= :priceMax')
            ->setParameters(array('priceMax' => $priceMax));

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param float        $priceMin
     * @param float        $priceMax
     *
     * @return QueryBuilder
     */
    public function wherePriceMinAndMax(QueryBuilder $qb, $priceMin, $priceMax)
    {
        $qb->andWhere('a.price BETWEEN :priceMin AND :priceMax')
            ->setParameters(array('priceMin' => $priceMin, 'priceMax' => $priceMax));

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param float        $chargesMin
     *
     * @return QueryBuilder
     */
    public function whereChargesMin(QueryBuilder $qb, $chargesMin)
    {
        $qb->andWhere('a.charges >= :chargesMin')
            ->setParameters(array('chargesMin' => $chargesMin));

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param int          $chargesMax
     *
     * @return QueryBuilder
     */
    public function whereChargesMax(QueryBuilder $qb, $chargesMax)
    {
        $qb->andWhere('a.charges <= :chargesMax')
            ->setParameters(array('chargesMax' => $chargesMax));

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param float        $chargesMin
     * @param float        $chargesMax
     *
     * @return QueryBuilder
     */
    public function whereChargesMinAndMax(QueryBuilder $qb, $chargesMin, $chargesMax)
    {
        $qb->andWhere('a.charges BETWEEN :chargesMin AND :chargesMax')
            ->setParameters(array('chargesMin' => $chargesMin, 'chargesMax' => $chargesMax));

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param float        $taxMin
     *
     * @return QueryBuilder
     */
    public function whereTaxMin(QueryBuilder $qb, $taxMin)
    {
        $qb->andWhere('a.tax >= :taxMin')
            ->setParameters(array('taxMin' => $taxMin));

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param float        $taxMax
     *
     * @return QueryBuilder
     */
    public function whereTaxMax(QueryBuilder $qb, $taxMax)
    {
        $qb->andWhere('a.tax <= :taxMax')
            ->setParameters(array('taxMax' => $taxMax));

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param float        $taxMin
     * @param float        $taxMax
     *
     * @return QueryBuilder
     */
    public function whereTaxMinAndMax(QueryBuilder $qb, $taxMin, $taxMax)
    {
        $qb->andWhere('a.tax BETWEEN :taxMin AND :taxMax')
            ->setParameters(array('taxMin' => $taxMin, 'taxMax' => $taxMax));

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $localization
     *
     * @return QueryBuilder
     */
    public function whereLocalization(QueryBuilder $qb, $localization)
    {
        $qb->andWhere('a.zipCode = :localization')
            ->setParameter('localization', $localization);

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function orderUpdatedAsc(QueryBuilder $qb)
    {
        $qb->orderBy('a.updatedOn', 'ASC');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function orderUpdatedDesc(QueryBuilder $qb)
    {
        $qb->orderBy('a.updatedOn', 'DESC');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function orderChargesAsc(QueryBuilder $qb)
    {
        $qb->orderBy('a.charges', 'ASC');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function orderChargesDesc(QueryBuilder $qb)
    {
        $qb->orderBy('a.charges', 'DESC');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function orderPriceTtcAsc(QueryBuilder $qb)
    {
        $qb->orderBy('a.price', 'ASC');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function orderPriceTtcDesc(QueryBuilder $qb)
    {
        $qb->orderBy('a.price', 'DESC');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function orderTaxAsc(QueryBuilder $qb)
    {
        $qb->orderBy('a.tax', 'ASC');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function orderTaxDesc(QueryBuilder $qb)
    {
        $qb->orderBy('a.tax', 'DESC');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $keywords
     *
     * @return QueryBuilder
     */
    public function searchByKeywords(QueryBuilder $qb, $keywords)
    {
        $qb->where('a.title LIKE :keywords')
            ->orWhere('a.description LIKE :keywords')
            ->orWhere('a.street LIKE :keywords')
            ->orWhere('a.zipCode LIKE :keywords')
            ->orWhere('a.city LIKE :keywords')
            ->orWhere('a.country LIKE :keywords')
            ->setParameter('keywords', '%' . $keywords . '%');

        return $qb;
    }
}
