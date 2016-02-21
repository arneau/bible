<?php

namespace Map;

use \Verse;
use \VerseQuery;
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
 * This class defines the structure of the 'defender_verse' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class VerseTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.VerseTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'defender_verse';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Verse';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Verse';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the bible_id field
     */
    const COL_BIBLE_ID = 'defender_verse.bible_id';

    /**
     * the column name for the book_id field
     */
    const COL_BOOK_ID = 'defender_verse.book_id';

    /**
     * the column name for the chapter_number field
     */
    const COL_CHAPTER_NUMBER = 'defender_verse.chapter_number';

    /**
     * the column name for the text field
     */
    const COL_TEXT = 'defender_verse.text';

    /**
     * the column name for the verse_number field
     */
    const COL_VERSE_NUMBER = 'defender_verse.verse_number';

    /**
     * the column name for the word_count field
     */
    const COL_WORD_COUNT = 'defender_verse.word_count';

    /**
     * the column name for the id field
     */
    const COL_ID = 'defender_verse.id';

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
        self::TYPE_PHPNAME       => array('BibleId', 'BookId', 'ChapterNumber', 'Text', 'VerseNumber', 'WordCount', 'Id', ),
        self::TYPE_CAMELNAME     => array('bibleId', 'bookId', 'chapterNumber', 'text', 'verseNumber', 'wordCount', 'id', ),
        self::TYPE_COLNAME       => array(VerseTableMap::COL_BIBLE_ID, VerseTableMap::COL_BOOK_ID, VerseTableMap::COL_CHAPTER_NUMBER, VerseTableMap::COL_TEXT, VerseTableMap::COL_VERSE_NUMBER, VerseTableMap::COL_WORD_COUNT, VerseTableMap::COL_ID, ),
        self::TYPE_FIELDNAME     => array('bible_id', 'book_id', 'chapter_number', 'text', 'verse_number', 'word_count', 'id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('BibleId' => 0, 'BookId' => 1, 'ChapterNumber' => 2, 'Text' => 3, 'VerseNumber' => 4, 'WordCount' => 5, 'Id' => 6, ),
        self::TYPE_CAMELNAME     => array('bibleId' => 0, 'bookId' => 1, 'chapterNumber' => 2, 'text' => 3, 'verseNumber' => 4, 'wordCount' => 5, 'id' => 6, ),
        self::TYPE_COLNAME       => array(VerseTableMap::COL_BIBLE_ID => 0, VerseTableMap::COL_BOOK_ID => 1, VerseTableMap::COL_CHAPTER_NUMBER => 2, VerseTableMap::COL_TEXT => 3, VerseTableMap::COL_VERSE_NUMBER => 4, VerseTableMap::COL_WORD_COUNT => 5, VerseTableMap::COL_ID => 6, ),
        self::TYPE_FIELDNAME     => array('bible_id' => 0, 'book_id' => 1, 'chapter_number' => 2, 'text' => 3, 'verse_number' => 4, 'word_count' => 5, 'id' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('defender_verse');
        $this->setPhpName('Verse');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Verse');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addForeignKey('bible_id', 'BibleId', 'INTEGER', 'defender_bible', 'id', true, null, null);
        $this->addForeignKey('book_id', 'BookId', 'INTEGER', 'defender_book', 'id', true, null, null);
        $this->addColumn('chapter_number', 'ChapterNumber', 'INTEGER', true, null, null);
        $this->addColumn('text', 'Text', 'VARCHAR', true, 1000, null);
        $this->addColumn('verse_number', 'VerseNumber', 'INTEGER', true, null, null);
        $this->addColumn('word_count', 'WordCount', 'INTEGER', true, null, null);
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Bible', '\\Bible', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':bible_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Book', '\\Book', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':book_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Tag', '\\Tag', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':verse_id',
    1 => ':id',
  ),
), null, null, 'Tags', false);
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
            'auto_add_pk' => array('name' => 'id', 'autoIncrement' => 'true', 'type' => 'INTEGER', ),
        );
    } // getBehaviors()

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 6 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 6 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 6 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 6 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 6 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 6 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
                ? 6 + $offset
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
        return $withPrefix ? VerseTableMap::CLASS_DEFAULT : VerseTableMap::OM_CLASS;
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
     * @return array           (Verse object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = VerseTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = VerseTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + VerseTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = VerseTableMap::OM_CLASS;
            /** @var Verse $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            VerseTableMap::addInstanceToPool($obj, $key);
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
            $key = VerseTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = VerseTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Verse $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                VerseTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(VerseTableMap::COL_BIBLE_ID);
            $criteria->addSelectColumn(VerseTableMap::COL_BOOK_ID);
            $criteria->addSelectColumn(VerseTableMap::COL_CHAPTER_NUMBER);
            $criteria->addSelectColumn(VerseTableMap::COL_TEXT);
            $criteria->addSelectColumn(VerseTableMap::COL_VERSE_NUMBER);
            $criteria->addSelectColumn(VerseTableMap::COL_WORD_COUNT);
            $criteria->addSelectColumn(VerseTableMap::COL_ID);
        } else {
            $criteria->addSelectColumn($alias . '.bible_id');
            $criteria->addSelectColumn($alias . '.book_id');
            $criteria->addSelectColumn($alias . '.chapter_number');
            $criteria->addSelectColumn($alias . '.text');
            $criteria->addSelectColumn($alias . '.verse_number');
            $criteria->addSelectColumn($alias . '.word_count');
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
        return Propel::getServiceContainer()->getDatabaseMap(VerseTableMap::DATABASE_NAME)->getTable(VerseTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(VerseTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(VerseTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new VerseTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Verse or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Verse object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(VerseTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Verse) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(VerseTableMap::DATABASE_NAME);
            $criteria->add(VerseTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = VerseQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            VerseTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                VerseTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the defender_verse table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return VerseQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Verse or Criteria object.
     *
     * @param mixed               $criteria Criteria or Verse object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(VerseTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Verse object
        }

        if ($criteria->containsKey(VerseTableMap::COL_ID) && $criteria->keyContainsValue(VerseTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.VerseTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = VerseQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // VerseTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
VerseTableMap::buildTableMap();
