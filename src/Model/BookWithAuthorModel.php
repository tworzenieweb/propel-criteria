<?php


namespace Tworzenieweb\Model;


use Tworzenieweb\Model\Bookstore\AuthorPeer;
use Tworzenieweb\Model\Bookstore\BookPeer;

class BookWithAuthorModel extends DataModel
{
    /**
     * @param $firstName
     * @param $lastName
     * @param $title
     */
    public function __construct($firstName, $lastName, $title)
    {
        $this[AuthorPeer::FIRST_NAME] = $firstName;
        $this[AuthorPeer::LAST_NAME] = $lastName;
        $this[BookPeer::TITLE] = $title;

        $this->filters = [AuthorFilter::NAME, BookFilter::NAME];
    }
}