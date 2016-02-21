<?php

namespace Base;

use \Response as ChildResponse;
use \ResponseQuery as ChildResponseQuery;
use \Exception;
use \PDO;
use Map\ResponseTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'defender_response' table.
 *
 *
 *
 * @method     ChildResponseQuery orderByExplanation($order = Criteria::ASC) Order by the explanation column
 * @method     ChildResponseQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method     ChildResponseQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildResponseQuery groupByExplanation() Group by the explanation column
 * @method     ChildResponseQuery groupByText() Group by the text column
 * @method     ChildResponseQuery groupById() Group by the id column
 *
 * @method     ChildResponseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildResponseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildResponseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildResponseQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildResponseQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildResponseQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildResponseQuery leftJoinAnswer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Answer relation
 * @method     ChildResponseQuery rightJoinAnswer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Answer relation
 * @method     ChildResponseQuery innerJoinAnswer($relationAlias = null) Adds a INNER JOIN clause to the query using the Answer relation
 *
 * @method     ChildResponseQuery joinWithAnswer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Answer relation
 *
 * @method     ChildResponseQuery leftJoinWithAnswer() Adds a LEFT JOIN clause and with to the query using the Answer relation
 * @method     ChildResponseQuery rightJoinWithAnswer() Adds a RIGHT JOIN clause and with to the query using the Answer relation
 * @method     ChildResponseQuery innerJoinWithAnswer() Adds a INNER JOIN clause and with to the query using the Answer relation
 *
 * @method     ChildResponseQuery leftJoinStatement($relationAlias = null) Adds a LEFT JOIN clause to the query using the Statement relation
 * @method     ChildResponseQuery rightJoinStatement($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Statement relation
 * @method     ChildResponseQuery innerJoinStatement($relationAlias = null) Adds a INNER JOIN clause to the query using the Statement relation
 *
 * @method     ChildResponseQuery joinWithStatement($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Statement relation
 *
 * @method     ChildResponseQuery leftJoinWithStatement() Adds a LEFT JOIN clause and with to the query using the Statement relation
 * @method     ChildResponseQuery rightJoinWithStatement() Adds a RIGHT JOIN clause and with to the query using the Statement relation
 * @method     ChildResponseQuery innerJoinWithStatement() Adds a INNER JOIN clause and with to the query using the Statement relation
 *
 * @method     \AnswerQuery|\StatementQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildResponse findOne(ConnectionInterface $con = null) Return the first ChildResponse matching the query
 * @method     ChildResponse findOneOrCreate(ConnectionInterface $con = null) Return the first ChildResponse matching the query, or a new ChildResponse object populated from the query conditions when no match is found
 *
 * @method     ChildResponse findOneByExplanation(string $explanation) Return the first ChildResponse filtered by the explanation column
 * @method     ChildResponse findOneByText(string $text) Return the first ChildResponse filtered by the text column
 * @method     ChildResponse findOneById(int $id) Return the first ChildResponse filtered by the id column *

 * @method     ChildResponse requirePk($key, ConnectionInterface $con = null) Return the ChildResponse by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResponse requireOne(ConnectionInterface $con = null) Return the first ChildResponse matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildResponse requireOneByExplanation(string $explanation) Return the first ChildResponse filtered by the explanation column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResponse requireOneByText(string $text) Return the first ChildResponse filtered by the text column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResponse requireOneById(int $id) Return the first ChildResponse filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildResponse[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildResponse objects based on current ModelCriteria
 * @method     ChildResponse[]|ObjectCollection findByExplanation(string $explanation) Return ChildResponse objects filtered by the explanation column
 * @method     ChildResponse[]|ObjectCollection findByText(string $text) Return ChildResponse objects filtered by the text column
 * @method     ChildResponse[]|ObjectCollection findById(int $id) Return ChildResponse objects filtered by the id column
 * @method     ChildResponse[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ResponseQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ResponseQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Response', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildResponseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildResponseQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildResponseQuery) {
            return $criteria;
        }
        $query = new ChildResponseQuery();
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
     * @return ChildResponse|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ResponseTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ResponseTableMap::DATABASE_NAME);
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
     * @return ChildResponse A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT explanation, text, id FROM defender_response WHERE id = :p0';
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
            /** @var ChildResponse $obj */
            $obj = new ChildResponse();
            $obj->hydrate($row);
            ResponseTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildResponse|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildResponseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ResponseTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildResponseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ResponseTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the explanation column
     *
     * Example usage:
     * <code>
     * $query->filterByExplanation('fooValue');   // WHERE explanation = 'fooValue'
     * $query->filterByExplanation('%fooValue%'); // WHERE explanation LIKE '%fooValue%'
     * </code>
     *
     * @param     string $explanation The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResponseQuery The current query, for fluid interface
     */
    public function filterByExplanation($explanation = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($explanation)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $explanation)) {
                $explanation = str_replace('*', '%', $explanation);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ResponseTableMap::COL_EXPLANATION, $explanation, $comparison);
    }

    /**
     * Filter the query on the text column
     *
     * Example usage:
     * <code>
     * $query->filterByText('fooValue');   // WHERE text = 'fooValue'
     * $query->filterByText('%fooValue%'); // WHERE text LIKE '%fooValue%'
     * </code>
     *
     * @param     string $text The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResponseQuery The current query, for fluid interface
     */
    public function filterByText($text = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($text)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $text)) {
                $text = str_replace('*', '%', $text);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ResponseTableMap::COL_TEXT, $text, $comparison);
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
     * @return $this|ChildResponseQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ResponseTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ResponseTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResponseTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \Answer object
     *
     * @param \Answer|ObjectCollection $answer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildResponseQuery The current query, for fluid interface
     */
    public function filterByAnswer($answer, $comparison = null)
    {
        if ($answer instanceof \Answer) {
            return $this
                ->addUsingAlias(ResponseTableMap::COL_ID, $answer->getResponseId(), $comparison);
        } elseif ($answer instanceof ObjectCollection) {
            return $this
                ->useAnswerQuery()
                ->filterByPrimaryKeys($answer->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAnswer() only accepts arguments of type \Answer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Answer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildResponseQuery The current query, for fluid interface
     */
    public function joinAnswer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Answer');

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
            $this->addJoinObject($join, 'Answer');
        }

        return $this;
    }

    /**
     * Use the Answer relation Answer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \AnswerQuery A secondary query class using the current class as primary query
     */
    public function useAnswerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAnswer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Answer', '\AnswerQuery');
    }

    /**
     * Filter the query by a related \Statement object
     *
     * @param \Statement|ObjectCollection $statement the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildResponseQuery The current query, for fluid interface
     */
    public function filterByStatement($statement, $comparison = null)
    {
        if ($statement instanceof \Statement) {
            return $this
                ->addUsingAlias(ResponseTableMap::COL_ID, $statement->getResponseId(), $comparison);
        } elseif ($statement instanceof ObjectCollection) {
            return $this
                ->useStatementQuery()
                ->filterByPrimaryKeys($statement->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStatement() only accepts arguments of type \Statement or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Statement relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildResponseQuery The current query, for fluid interface
     */
    public function joinStatement($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Statement');

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
            $this->addJoinObject($join, 'Statement');
        }

        return $this;
    }

    /**
     * Use the Statement relation Statement object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \StatementQuery A secondary query class using the current class as primary query
     */
    public function useStatementQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStatement($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Statement', '\StatementQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildResponse $response Object to remove from the list of results
     *
     * @return $this|ChildResponseQuery The current query, for fluid interface
     */
    public function prune($response = null)
    {
        if ($response) {
            $this->addUsingAlias(ResponseTableMap::COL_ID, $response->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the defender_response table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ResponseTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ResponseTableMap::clearInstancePool();
            ResponseTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ResponseTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ResponseTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ResponseTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ResponseTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ResponseQuery
