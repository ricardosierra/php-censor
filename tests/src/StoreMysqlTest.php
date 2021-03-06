<?php

namespace Tests\PHPCensor;

use PHPCensor\Config;
use PHPCensor\Database;
use PHPCensor\Exception\InvalidArgumentException;
use PHPCensor\Store\Factory;
use PHPCensor\Model\Project;
use PHPCensor\Model\ProjectGroup;
use PHPCensor\Store;

class WrongStore extends Store
{
    protected $tableName  = 'project_group';

    protected $modelName  = '\PHPCensor\Model\ProjectGroup';

    public function getByPrimaryKey($key, $useConnection = 'read')
    {
        return null;
    }
}

class StoreMysqlTest extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * @var \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection|null
     */
    protected $connection = null;

    /**
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        if (extension_loaded('mysqli')) {
            if (null === $this->connection) {
                try {
                    $pdo = new \PDO(
                        'mysql:host=localhost;dbname=' . MYSQL_DBNAME,
                        MYSQL_USER,
                        MYSQL_PASSWORD
                    );

                    $this->connection = $this->createDefaultDBConnection($pdo, MYSQL_DBNAME);

                    $this->connection->getConnection()->query('
                        CREATE TABLE IF NOT EXISTS `project_group` (
                            `id`          int(11) NOT NULL AUTO_INCREMENT,
                            `title`       varchar(100) NOT NULL,
                            `create_date` datetime,
                            `user_id`     int(11) NOT NULL DEFAULT 0,
                            PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8
                    ');
                } catch (\PDOException $ex) {
                    $this->connection = null;
                }
            }
        } else {
            $this->connection = null;
        }
    }

    /**
     * @return \PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        if (null === $this->connection) {
            $this->markTestSkipped('Test skipped because MySQL database/user/extension doesn`t exist.');
        }

        return $this->connection;
    }

    /**
     * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return $this->createArrayDataSet([
            'project_group' => [[
                'id'          => 1,
                'title'       => 'group 1',
                'create_date' => null,
                'user_id'     => 0,
            ], [
                'id'          => 2,
                'title'       => 'group 2',
                'create_date' => null,
                'user_id'     => 0,
            ], [
                'id'          => 3,
                'title'       => 'group 3',
                'create_date' => null,
                'user_id'     => 1,
            ], [
                'id'          => 4,
                'title'       => 'group 4',
                'create_date' => null,
                'user_id'     => 1,
            ], [
                'id'          => 5,
                'title'       => 'group 5',
                'create_date' => '2018-01-01 01:01:00',
                'user_id'     => 2,
            ], [
                'id'          => 6,
                'title'       => 'group 6',
                'create_date' => '2018-02-01 01:01:00',
                'user_id'     => 3,
            ], [
                'id'          => 7,
                'title'       => 'group 7',
                'create_date' => '2018-03-01 01:01:00',
                'user_id'     => 4,
            ]],
        ]);
    }

    protected function setUp()
    {
        parent::setUp();

        new Config([
            'php-censor' => [
                'database' => [
                    'servers' => [
                        'read'  => [
                            ['host' => 'localhost'],
                        ],
                        'write' => [
                            ['host' => 'localhost'],
                        ],
                    ],
                    'type'     => Database::MYSQL_TYPE,
                    'name'     => MYSQL_DBNAME,
                    'username' => MYSQL_USER,
                    'password' => MYSQL_PASSWORD,
                ],
            ],
        ]);
        Database::reset();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testConstruct()
    {
        $store = new WrongStore();
    }

    public function testGetWhere()
    {
        $testStore = Factory::getStore('ProjectGroup');

        $data = $testStore->getWhere([], 3, 1, ['id' => 'DESC']);
        self::assertEquals(7, $data['count']);
        self::assertEquals(3, count($data['items']));

        self::assertEquals(6, $data['items'][0]->getId());
        self::assertEquals(5, $data['items'][1]->getId());
        self::assertEquals(4, $data['items'][2]->getId());

        $data = $testStore->getWhere(['project_group.user_id' => 0], 100, 0, ['id' => 'ASC']);
        self::assertEquals(2, $data['count']);
        self::assertEquals(2, count($data['items']));

        self::assertEquals(1, $data['items'][0]->getId());
        self::assertEquals(2, $data['items'][1]->getId());

        try {
            $data = $testStore->getWhere(['' => 0], 100, 0, ['id' => 'ASC']);
        } catch (InvalidArgumentException $e) {
            self::assertEquals('You cannot have an empty field name.', $e->getMessage());
        }

        try {
            $data = $testStore->getWhere([], '; SELECT', 0, ['id' => 'ASC']);
        } catch (\PDOException $e) {
            self::assertInstanceOf('\PDOException', $e);
        }
    }

    public function testSaveByInsert()
    {
        $testStore = Factory::getStore('ProjectGroup');
        $model     = new ProjectGroup();

        $model->setTitle('group 8');

        $testStore->save($model);

        $newModel = $testStore->getByPrimaryKey(8);

        self::assertEquals(8, $newModel->getId());
        self::assertEquals('group 8', $newModel->getTitle());
    }

    public function testSaveByUpdate()
    {
        $testStore = Factory::getStore('ProjectGroup');
        $model     = $testStore->getByPrimaryKey(7);

        $model->setTitle('group 100');

        $testStore->save($model);

        $newModel = $testStore->getByPrimaryKey(7);

        self::assertEquals(7, $newModel->getId());
        self::assertEquals('group 100', $newModel->getTitle());

        // Without changes
        $model = $testStore->getByPrimaryKey(6);

        $testStore->save($model);

        $newModel = $testStore->getByPrimaryKey(6);

        self::assertEquals(6, $newModel->getId());
        self::assertEquals('group 6', $newModel->getTitle());

        // Wrong Model
        try {
            $model = new Project();
            $model->setId(1);

            $testStore->save($model);
        } catch (InvalidArgumentException $e) {
            self::assertEquals(
                'PHPCensor\Model\Project is an invalid model type for this store.',
                $e->getMessage()
            );
        }
    }

    public function testDelete()
    {
        $testStore = Factory::getStore('ProjectGroup');
        $model     = $testStore->getByPrimaryKey(5);

        $testStore->delete($model);

        $newModel = $testStore->getByPrimaryKey(5);

        self::assertEquals(null, $newModel);

        // Wrong Model
        try {
            $model = new Project();
            $model->setId(1);

            $testStore->delete($model);
        } catch (InvalidArgumentException $e) {
            self::assertEquals(
                'PHPCensor\Model\Project is an invalid model type for this store.',
                $e->getMessage()
            );
        }
    }
}
