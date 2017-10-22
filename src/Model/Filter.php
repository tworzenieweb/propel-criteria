<?php

namespace Tworzenieweb\Model;

use Criteria;
use Criterion;

abstract class Filter
{
    const NAME = 'ABSTRACT_FILTER';

    /**
     * @param Criteria $criteria
     * @param DataModel $model
     * @return Criterion|null
     */
    public abstract function getCriterion(Criteria $criteria, DataModel $model);

    /**
     * @param DataModel $model
     * @return bool
     */
    public function shouldApply(DataModel $model)
    {
        return $model->hasFilter(static::NAME);
    }
}