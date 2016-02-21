<?php

namespace Base;

use \KeywordSynonym as ChildKeywordSynonym;
use \KeywordSynonymQuery as ChildKeywordSynonymQuery;
use \Exception;
use \PDO;
use Map\KeywordSynonymTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'defender_keyword_synonym' table.
 *
 *
 *
 * @method     ChildKeywordSynonymQuery orderByKeywordId($order = Criteria::ASC) Order by the keyword_id column
 * @method     ChildKeywordSynonymQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method     ChildKeywordSynonymQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildKeywordSynonymQuery groupByKeywordId() Group by the keyword_id column
 * @method     ChildKeywordSynonymQuery groupByValue() Group by the value column
 * @method     ChildKeywordSynonymQuery groupById() Group by the id column
 *
 * @method     ChildKeywordSynonymQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildKeywordSynonymQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildKeywordSynonymQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildKeywordSynonymQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildKeywordSynonymQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildKeywordSynonymQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildKeywordSynonymQuery leftJoinKeyword($relationAlias = null) Adds a LEFT JOIN clause to the query using the Keyword relation
 * @method     ChildKeywordSynonymQuery rightJoinKeyword($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Keyword relation
 * @method     ChildKeywordSynonymQuery innerJoinKeyword($relationAlias = null) Adds a INNER JOIN clause to the query using the Keyword relation
 *
 * @method     ChildKeywordSynonymQuery joinWithKeyword($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Keyword relation
 *
 * @method     ChildKeywordSynonymQuery leftJoinWithKeyword() Adds a LEFT JOIN clause and with to the query using the Keyword relation
 * @method     ChildKeywordSynonymQuery rightJoinWithKeyword() Adds a RIGHT JOIN clause and with to the query using the Keyword relation
 * @method     ChildKeywordSynonymQuery innerJoinWithKeyword() Adds a INNER JOIN clause and with to the query using the Keyword relation
 *
 * @method     \KeywordQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildKeywordSynonym findOne(ConnectionInterface $con = null) Return the first ChildKeywordSynonym matching the query
 * @method     ChildKeywordSynonym findOneOrCreate(ConnectionInterface $con = null) Return the first ChildKeywordSynonym matching the query, or a new ChildKeywordSynonym object populated from the query conditions when no match is found
 *
 * @method     ChildKeywordSynonym findOneByKeywordId(int $keyword_id) Return the first ChildKeywordSynonym filtered by the keyword_id column
 * @method     ChildKeywordSynonym findOneByValue(string $value) Return the first ChildKeywordSynonym filtered by the value column
 * @method     ChildKeywordSynonym findOneById(int $id) Return the first ChildKeywordSynonym filtered by the id column *

 * @method     ChildKeywordSynonym requirePk($key, ConnectionInterface $con = null) Return the ChildKeywordSynonym by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildKeywordSynonym requireOne(ConnectionInterface $con = null) Return the first ChildKeywordSynonym matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildKeywordSynonym requireOneByKeywordId(int $keyword_id) Return the first ChildKeywordSynonym filtered by the keyword_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildKeywordSynonym requireOneByValue(string $value) Return the first ChildKeywordSynonym filtered by the value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildKeywordSynonym requireOneById(int $id) Return the first ChildKeywordSynonym filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildKeywordSynonym[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildKeywordSynonym objects based on current ModelCriteria
 * @method     ChildKeywordSynonym[]|ObjectCollection findByKeywordId(int $keyword_id) Return ChildKeywordSynonym objects filtered by the keyword_id column
 * @method     ChildKeywordSynonym[]|ObjectCollection findByValue(string $value) Return ChildKeywordSynonym objects filtered by the value column
 * @method     ChildKeywordSynonym[]|ObjectCollection findById(int $id) Return ChildKeywordSynonym objects filtered by the id column
 * @method     ChildKeywordSynonym[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class KeywordSynonymQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\KeywordSynonymQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\KeywordSynonym', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildKeywordSynonymQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildKeywordSynonymQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildKeywordSynonymQuery) {
            return $criteria;
        }
        $query = new ChildKeywordSynonymQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildKeywordSynonym|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = KeywordSynonymTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(KeywordSynonymTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildKeywordSynonym A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT keyword_id, value, id FROM defender_keyword_synonym WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildKeywordSynonym $obj */
            $obj = new ChildKeywordSynonym();
            $obj->hydrate($row);
            KeywordSynonymTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildKeywordSynonym|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildKeywordSynonymQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(KeywordSynonymTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildKeywordSynonymQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(KeywordSynonymTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the keyword_id column
     *
     * Example usage:
     * <code>
     * $query->filterByKeywordId(1234); // WHERE keyword_id = 1234
     * $query->filterByKeywordId(array(12, 34)); // WHERE keyword_id IN (12, 34)
     * $query->filterByKeywordId(array('min' => 12)); // WHERE keyword_id > 12
     * </code>
     *
     * @see       filterByKeyword()
     *
     * @param     mixed $keywordId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildKeywordSynonymQuery The current query, for fluid interface
     */
    public function filterByKeywordId($keywordId = null, $comparison = null)
    {
        if (is_array($keywordId)) {
            $useMinMax = false;
            if (isset($keywordId['min'])) {
                $this->addUsingAlias(KeywordSynonymTableMap::COL_KEYWORD_ID, $keywordId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($keywordId['max'])) {
                $this->addUsingAlias(KeywordSynonymTableMap::COL_KEYWORD_ID, $keywordId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(KeywordSynonymTableMap::COL_KEYWORD_ID, $keywordId, $comparison);
    }

    /**
     * Filter the query on the value column
     *
     * Example usage:
     * <code>
     * $query->filterByValue('fooValue');   // WHERE value = 'fooValue'
     * $query->filterByValue('%fooValue%'); // WHERE value LIKE '%fooValue%'
     * </code>
     *
     * @param     string $value The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildKeywordSynonymQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($value)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $value)) {
                $value = str_replace('*', '%', $value);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(KeywordSynonymTableMap::COL_VALUE, $value, $comparison);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildKeywordSynonymQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(KeywordSynonymTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(KeywordSynonymTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(KeywordSynonymTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \Keyword object
     *
     * @param \Keyword|ObjectCollection $keyword The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildKeywordSynonymQuery The current query, for fluid interface
     */
    public function filterByKeyword($keyword, $comparison = null)
    {
        if ($keyword instanceof \Keyword) {
            return $this
                ->addUsingAlias(KeywordSynonymTableMap::COL_KEYWORD_ID, $keyword->getId(), $comparison);
        } elseif ($keyword instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(KeywordSynonymTableMap::COL_KEYWORD_ID, $keyword->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByKeyword() only accepts arguments of type \Keyword or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Keyword relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildKeywordSynonymQuery The current query, for fluid interface
     */
    public function joinKeyword($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Keyword');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Keyword');
        }

        return $this;
    }

    /**
     * Use the Keyword relation Keyword object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \KeywordQuery A secondary query class using the current class as primary query
     */
    public function useKeywordQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinKeyword($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Keyword', '\KeywordQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildKeywordSynonym $keywordSynonym Object to remove from the list of results
     *
     * @return $this|ChildKeywordSynonymQuery The current query, for fluid interface
     */
    public function prune($keywordSynonym = null)
    {
        if ($keywordSynonym) {
            $this->addUsingAlias(KeywordSynonymTableMap::COL_ID, $keywordSynonym->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the defender_keyword_synonym table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(KeywordSynonymTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            KeywordSynonymTableMap::clearInstancePool();
            KeywordSynonymTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(KeywordSynonymTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(KeywordSynonymTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            KeywordSynonymTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            KeywordSynonymTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // KeywordSynonymQuery
