<?php

namespace Tworzenieweb\Model;

use BasePeer;
use Criteria;

class PropelFetcher
{
    /** @var Filter[] */
    private $filters;

    /**
     * @var Criteria
     */
    private $criteria;

    public function __construct(Criteria $criteria)
    {
        $this->criteria = $criteria;
        $this->filters = [];
    }

    public function addFilter(Filter $filter)
    {
        array_push($this->filters, $filter);
    }

    /**
     * @param DataModel $dataModel
     * @return Criteria
     */
    public function getCriteria(DataModel $dataModel)
    {
        $this->criteria->clear();
        $atLeastOneFilter = false;

        foreach ($this->filters as $filter) {
            if ($filter->shouldApply($dataModel)) {
                $atLeastOneFilter = true;
                $this->criteria->add($filter->getCriterion($this->criteria, $dataModel));
            }
        }

        if (!$atLeastOneFilter) {
            throw new \RuntimeException('Provided data model has no filters defined');
        }

        return $this->criteria;
    }

    /**
     * @param DataModel $dataModel
     * @return \Traversable
     */
    public function getResultsIterator(DataModel $dataModel)
    {
        $statement = BasePeer::doSelect($this->getCriteria($dataModel));
        $statement->setFetchMode(\PDO::FETCH_ASSOC);

        return $statement;
    }

    /**
     * @param DataModel $dataModel
     * @return mixed
     */
    public function countResults(DataModel $dataModel)
    {
        $statement = BasePeer::doCount($this->getCriteria($dataModel));

        return $statement->fetchColumn();
    }
}