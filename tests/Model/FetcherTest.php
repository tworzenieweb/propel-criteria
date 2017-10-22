<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Tworzenieweb\Model\AuthorFilter;
use Tworzenieweb\Model\BookFilter;
use Tworzenieweb\Model\BookWithAuthorModel;
use Tworzenieweb\Model\DataModel;
use Tworzenieweb\Model\PropelFetcher;

class FetcherTest extends TestCase
{
    const BOOK_TITLE = 'Php7 For Experts';
    const FIRST_NAME = 'John';
    const LAST_NAME = 'Doe';

    /** @var PropelFetcher */
    private $fetcher;

    /** @var bool */
    private $withDebug = false;

    /**
     * @test
     * @dataProvider modelProvider
     * @param DataModel $dataModel
     * @param $expectedResults
     * @param bool $throwsError
     */
    public function it_should_fetch_data_based_on_data_model(DataModel $dataModel, $expectedResults, $throwsError = false)
    {
        $this->fetcher = new PropelFetcher(new Criteria());
        $this->fetcher->addFilter(new AuthorFilter());
        $this->fetcher->addFilter(new BookFilter());

        if ($throwsError) {
            $this->expectException(RuntimeException::class);
        }

        static::assertEquals($expectedResults, $this->fetcher->countResults($dataModel));
    }

    public function modelProvider()
    {
        /** @var ArrayAccess|DataModel $dataModel */
        $dataModel = new BookWithAuthorModel(self::FIRST_NAME, self::LAST_NAME, self::BOOK_TITLE);
        $dataModel2 = new BookWithAuthorModel('Non Existing author name', self::LAST_NAME, self::BOOK_TITLE);
        $dataModel3 = $this->prophesize(DataModel::class);
        return [
            [$dataModel, 1],
            [$dataModel2, 0],
            [$dataModel3->reveal(), 0, true],
        ];
    }

    protected function setUp()
    {
        $this->initPropel();
        $this->loadFixtures();
    }

    protected function initPropel()
    {
        Propel::setConfiguration(
            array(
                'datasources' => array(
                    'default' => 'bookstore',
                    'bookstore' => [
                        'adapter' => 'sqlite',
                        'connection' => [
                            'classname' => $this->withDebug ? DebugPDO::class : PropelPDO::class,
                            'dsn' => 'sqlite::memory:',
                        ],
                    ],
                ),
            )
        );

        if ($this->withDebug) {
            $this->enableLoggerForPropel();
        }
    }


    protected function enableLoggerForPropel()
    {
        $logger = new Logger('logger');
        $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
        Propel::setLogger($logger);
    }

    protected function loadFixtures()
    {
        $createTablesSql = file_get_contents(__DIR__.'/schema.sql');
        Propel::getConnection('bookstore')->exec($createTablesSql);

        $author = new \Tworzenieweb\Model\Bookstore\Author();
        $author->setFirstName(self::FIRST_NAME);
        $author->setLastName(self::LAST_NAME);
        $author->save();

        $publisher = new \Tworzenieweb\Model\Bookstore\Publisher();
        $publisher->setName('Packt publishing');
        $publisher->save();

        $book = new \Tworzenieweb\Model\Bookstore\Book();
        $book->setTitle(self::BOOK_TITLE);
        $book->setAuthor($author);
        $book->setISBN('TEST12345');
        $book->setPublisher($publisher);
        $book->save();
    }

    protected function tearDown()
    {
        Propel::close();
    }
}
