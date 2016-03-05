<?php

namespace Map;

use \Topic;
use \TopicQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'defender_topic' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class TopicTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.TopicTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'defender_topic';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Topic';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Topic';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 4;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 4;

    /**
     * the column name for the is_root field
     */
    const COL_IS_ROOT = 'defender_topic.is_root';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'defender_topic.name';

    /**
     * the column name for the tag_count field
     */
    const COL_TAG_COUNT = 'defender_topic.tag_count';

    /**
     * the column name for the id field
     */
    const COL_ID = 'defender_topic.id';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('IsRoot', 'Name', 'TagCount', 'Id', ),
        self::TYPE_CAMELNAME     => array('isRoot', 'name', 'tagCount', 'id', ),
        self::TYPE_COLNAME       => array(TopicTableMap::COL_IS_ROOT, TopicTableMap::COL_NAME, TopicTableMap::COL_TAG_COUNT, TopicTableMap::COL_ID, ),
        self::TYPE_FIELDNAME     => array('is_root', 'name', 'tag_count', 'id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('IsRoot' => 0, 'Name' => 1, 'TagCount' => 2, 'Id' => 3, ),
        self::TYPE_CAMELNAME     => array('isRoot' => 0, 'name' => 1, 'tagCount' => 2, 'id' => 3, ),
        self::TYPE_COLNAME       => array(TopicTableMap::COL_IS_ROOT => 0, TopicTableMap::COL_NAME => 1, TopicTableMap::COL_TAG_COUNT => 2, TopicTableMap::COL_ID => 3, ),
        self::TYPE_FIELDNAME     => array('is_root' => 0, 'name' => 1, 'tag_count' => 2, 'id' => 3, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('defender_topic');
        $this->setPhpName('Topic');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Topic');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addColumn('is_root', 'IsRoot', 'BOOLEAN', true, 1, false);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('tag_count', 'TagCount', 'INTEGER', true, null, 0);
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Tag', '\\Tag', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':topic_id',
    1 => ':id',
  ),
), null, null, 'Tags', false);
        $this->addRelation('TopicLink', '\\TopicLink', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':topic_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'TopicLinks', false);
        $this->addRelation('TopicParent', '\\TopicParent', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':topic_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'TopicParents', false);
        $this->addRelation('TopicSynonym', '\\TopicSynonym', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':topic_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'TopicSynonyms', false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'tag_count_aggregate' => array('name' => 'tag_count', 'expression' => 'COUNT(topic_id)', 'condition' => '', 'foreign_table' => 'tag', 'foreign_schema' => '', ),
            'auto_add_pk' => array('name' => 'id', 'autoIncrement' => 'true', 'type' => 'INTEGER', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to defender_topic     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        TopicLinkTableMap::clearInstancePool();
        TopicParentTableMap::clearInstancePool();
        TopicSynonymTableMap::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 3 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? TopicTableMap::CLASS_DEFAULT : TopicTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Topic object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = TopicTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = TopicTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + TopicTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = TopicTableMap::OM_CLASS;
            /** @var Topic $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            TopicTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = TopicTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = TopicTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Topic $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                TopicTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(TopicTableMap::COL_IS_ROOT);
            $criteria->addSelectColumn(TopicTableMap::COL_NAME);
            $criteria->addSelectColumn(TopicTableMap::COL_TAG_COUNT);
            $criteria->addSelectColumn(TopicTableMap::COL_ID);
        } else {
            $criteria->addSelectColumn($alias . '.is_root');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.tag_count');
            $criteria->addSelectColumn($alias . '.id');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(TopicTableMap::DATABASE_NAME)->getTable(TopicTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(TopicTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(TopicTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new TopicTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Topic or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Topic object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TopicTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Topic) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(TopicTableMap::DATABASE_NAME);
            $criteria->add(TopicTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = TopicQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            TopicTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                TopicTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the defender_topic table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return TopicQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Topic or Criteria object.
     *
     * @param mixed               $criteria Criteria or Topic object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TopicTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Topic object
        }

        if ($criteria->containsKey(TopicTableMap::COL_ID) && $criteria->keyContainsValue(TopicTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.TopicTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = TopicQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // TopicTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
TopicTableMap::buildTableMap();
