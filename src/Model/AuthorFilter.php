<?php

namespace Tworzenieweb\Model;

use Criteria;
use Tworzenieweb\Model\Bookstore\AuthorPeer;

class AuthorFilter extends Filter
{
    const NAME = 'Author';

    /**
     * @inheritdoc
     */
    public function getCriterion(Criteria $criteria, DataModel $model)
    {
        $criterion = null;

        if (isset($model[AuthorPeer::FIRST_NAME])) {
            $criteria->addAsColumn('author_pid', AuthorPeer::ID);
            $criteria->addAsColumn('author_first_name', AuthorPeer::FIRST_NAME);
            $criteria->addAsColumn('author_last_name', AuthorPeer::LAST_NAME);

            $criterion = $criteria->getNewCriterion( AuthorPeer::FIRST_NAME, $model[AuthorPeer::FIRST_NAME]);
            $criterion->addAnd($criteria->getNewCriterion( AuthorPeer::LAST_NAME, $model[AuthorPeer::LAST_NAME]));
        }

        return $criterion;
    }
}