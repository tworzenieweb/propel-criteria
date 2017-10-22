<?php

namespace Tworzenieweb\Model;

use Criteria;
use Tworzenieweb\Model\Bookstore\AuthorPeer;
use Tworzenieweb\Model\Bookstore\BookPeer;

class BookFilter extends Filter
{
    const NAME = 'Book';

    /**
     * @inheritdoc
     */
    public function getCriterion(Criteria $criteria, DataModel $dataModel)
    {
        $criteria->addAsColumn('book_pid', BookPeer::ID);
        $criteria->addAsColumn('book_title', BookPeer::TITLE);
        $criterion = $criteria->getNewCriterion(BookPeer::TITLE, $dataModel[BookPeer::TITLE]);

        if ($dataModel->hasFilter(AuthorFilter::NAME)) {
            $criteria->addJoin(
                BookPeer::AUTHOR_ID,
                AuthorPeer::ID,
                Criteria::INNER_JOIN
            );
        }

        return $criterion;
    }
}