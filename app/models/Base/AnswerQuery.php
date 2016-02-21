<?php

namespace Base;

use \Answer as ChildAnswer;
use \AnswerQuery as ChildAnswerQuery;
use \Exception;
use \PDO;
use Map\AnswerTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'defender_answer' table.
 *
 *
 *
 * @method     ChildAnswerQuery orderByAnswerTypeId($order = Criteria::ASC) Order by the answer_type_id column
 * @method     ChildAnswerQuery orderByResponseId($order = Criteria::ASC) Order by the response_id column
 * @method     ChildAnswerQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method     ChildAnswerQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildAnswerQuery groupByAnswerTypeId() Group by the answer_type_id column
 * @method     ChildAnswerQuery groupByResponseId() Group by the response_id column
 * @method     ChildAnswerQuery groupByText() Group by the text column
 * @method     ChildAnswerQuery groupById() Group by the id column
 *
 * @method     ChildAnswerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAnswerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAnswerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAnswerQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAnswerQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAnswerQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAnswerQuery leftJoinAnswerType($relationAlias = null) Adds a LEFT JOIN clause to the query using the AnswerType relation
 * @method     ChildAnswerQuery rightJoinAnswerType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AnswerType relation
 * @method     ChildAnswerQuery innerJoinAnswerType($relationAlias = null) Adds a INNER JOIN clause to the query using the AnswerType relation
 *
 * @method     ChildAnswerQuery joinWithAnswerType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AnswerType relation
 *
 * @method     ChildAnswerQuery leftJoinWithAnswerType() Adds a LEFT JOIN clause and with to the query using the AnswerType relation
 * @method     ChildAnswerQuery rightJoinWithAnswerType() Adds a RIGHT JOIN clause and with to the query using the AnswerType relation
 * @method     ChildAnswerQuery innerJoinWithAnswerType() Adds a INNER JOIN clause and with to the query using the AnswerType relation
 *
 * @method     ChildAnswerQuery leftJoinResponse($relationAlias = null) Adds a LEFT JOIN clause to the query using the Response relation
 * @method     ChildAnswerQuery rightJoinResponse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Response relation
 * @method     ChildAnswerQuery innerJoinResponse($relationAlias = null) Adds a INNER JOIN clause to the query using the Response relation
 *
 * @method     ChildAnswerQuery joinWithResponse($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Response relation
 *
 * @method     ChildAnswerQuery leftJoinWithResponse() Adds a LEFT JOIN clause and with to the query using the Response relation
 * @method     ChildAnswerQuery rightJoinWithResponse() Adds a RIGHT JOIN clause and with to the query using the Response relation
 * @method     ChildAnswerQuery innerJoinWithResponse() Adds a INNER JOIN clause and with to the query using the Response relation
 *
 * @method     \AnswerTypeQuery|\ResponseQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAnswer findOne(ConnectionInterface $con = null) Return the first ChildAnswer matching the query
 * @method     ChildAnswer findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAnswer matching the query, or a new ChildAnswer object populated from the query conditions when no match is found
 *
 * @method     ChildAnswer findOneByAnswerTypeId(int $answer_type_id) Return the first ChildAnswer filtered by the answer_type_id column
 * @method     ChildAnswer findOneByResponseId(int $response_id) Return the first ChildAnswer filtered by the response_id column
 * @method     ChildAnswer findOneByText(string $text) Return the first ChildAnswer filtered by the text column
 * @method     ChildAnswer findOneById(int $id) Return the first ChildAnswer filtered by the id column *

 * @method     ChildAnswer requirePk($key, ConnectionInterface $con = null) Return the ChildAnswer by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAnswer requireOne(ConnectionInterface $con = null) Return the first ChildAnswer matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAnswer requireOneByAnswerTypeId(int $answer_type_id) Return the first ChildAnswer filtered by the answer_type_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAnswer requireOneByResponseId(int $response_id) Return the first ChildAnswer filtered by the response_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAnswer requireOneByText(string $text) Return the first ChildAnswer filtered by the text column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAnswer requireOneById(int $id) Return the first ChildAnswer filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAnswer[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAnswer objects based on current ModelCriteria
 * @method     ChildAnswer[]|ObjectCollection findByAnswerTypeId(int $answer_type_id) Return ChildAnswer objects filtered by the answer_type_id column
 * @method     ChildAnswer[]|ObjectCollection findByResponseId(int $response_id) Return ChildAnswer objects filtered by the response_id column
 * @method     ChildAnswer[]|ObjectCollection findByText(string $text) Return ChildAnswer objects filtered by the text column
 * @method     ChildAnswer[]|ObjectCollection findById(int $id) Return ChildAnswer objects filtered by the id column
 * @method     ChildAnswer[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AnswerQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\AnswerQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Answer', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAnswerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAnswerQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAnswerQuery) {
            return $criteria;
        }
        $query = new ChildAnswerQuery();
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
     * @return ChildAnswer|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AnswerTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AnswerTableMap::DATABASE_NAME);
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
     * @return ChildAnswer A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT answer_type_id, response_id, text, id FROM defender_answer WHERE id = :p0';
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
            /** @var ChildAnswer $obj */
            $obj = new ChildAnswer();
            $obj->hydrate($row);
            AnswerTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAnswer|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAnswerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AnswerTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAnswerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AnswerTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the answer_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAnswerTypeId(1234); // WHERE answer_type_id = 1234
     * $query->filterByAnswerTypeId(array(12, 34)); // WHERE answer_type_id IN (12, 34)
     * $query->filterByAnswerTypeId(array('min' => 12)); // WHERE answer_type_id > 12
     * </code>
     *
     * @see       filterByAnswerType()
     *
     * @param     mixed $answerTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAnswerQuery The current query, for fluid interface
     */
    public function filterByAnswerTypeId($answerTypeId = null, $comparison = null)
    {
        if (is_array($answerTypeId)) {
            $useMinMax = false;
            if (isset($answerTypeId['min'])) {
                $this->addUsingAlias(AnswerTableMap::COL_ANSWER_TYPE_ID, $answerTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($answerTypeId['max'])) {
                $this->addUsingAlias(AnswerTableMap::COL_ANSWER_TYPE_ID, $answerTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AnswerTableMap::COL_ANSWER_TYPE_ID, $answerTypeId, $comparison);
    }

    /**
     * Filter the query on the response_id column
     *
     * Example usage:
     * <code>
     * $query->filterByResponseId(1234); // WHERE response_id = 1234
     * $query->filterByResponseId(array(12, 34)); // WHERE response_id IN (12, 34)
     * $query->filterByResponseId(array('min' => 12)); // WHERE response_id > 12
     * </code>
     *
     * @see       filterByResponse()
     *
     * @param     mixed $responseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAnswerQuery The current query, for fluid interface
     */
    public function filterByResponseId($responseId = null, $comparison = null)
    {
        if (is_array($responseId)) {
            $useMinMax = false;
            if (isset($responseId['min'])) {
                $this->addUsingAlias(AnswerTableMap::COL_RESPONSE_ID, $responseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($responseId['max'])) {
                $this->addUsingAlias(AnswerTableMap::COL_RESPONSE_ID, $responseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AnswerTableMap::COL_RESPONSE_ID, $responseId, $comparison);
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
     * @return $this|ChildAnswerQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AnswerTableMap::COL_TEXT, $text, $comparison);
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
     * @return $this|ChildAnswerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AnswerTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AnswerTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AnswerTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \AnswerType object
     *
     * @param \AnswerType|ObjectCollection $answerType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAnswerQuery The current query, for fluid interface
     */
    public function filterByAnswerType($answerType, $comparison = null)
    {
        if ($answerType instanceof \AnswerType) {
            return $this
                ->addUsingAlias(AnswerTableMap::COL_ANSWER_TYPE_ID, $answerType->getId(), $comparison);
        } elseif ($answerType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AnswerTableMap::COL_ANSWER_TYPE_ID, $answerType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAnswerType() only accepts arguments of type \AnswerType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AnswerType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAnswerQuery The current query, for fluid interface
     */
    public function joinAnswerType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AnswerType');

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
            $this->addJoinObject($join, 'AnswerType');
        }

        return $this;
    }

    /**
     * Use the AnswerType relation AnswerType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \AnswerTypeQuery A secondary query class using the current class as primary query
     */
    public function useAnswerTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAnswerType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AnswerType', '\AnswerTypeQuery');
    }

    /**
     * Filter the query by a related \Response object
     *
     * @param \Response|ObjectCollection $response The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAnswerQuery The current query, for fluid interface
     */
    public function filterByResponse($response, $comparison = null)
    {
        if ($response instanceof \Response) {
            return $this
                ->addUsingAlias(AnswerTableMap::COL_RESPONSE_ID, $response->getId(), $comparison);
        } elseif ($response instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AnswerTableMap::COL_RESPONSE_ID, $response->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByResponse() only accepts arguments of type \Response or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Response relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAnswerQuery The current query, for fluid interface
     */
    public function joinResponse($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Response');

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
            $this->addJoinObject($join, 'Response');
        }

        return $this;
    }

    /**
     * Use the Response relation Response object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ResponseQuery A secondary query class using the current class as primary query
     */
    public function useResponseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinResponse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Response', '\ResponseQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAnswer $answer Object to remove from the list of results
     *
     * @return $this|ChildAnswerQuery The current query, for fluid interface
     */
    public function prune($answer = null)
    {
        if ($answer) {
            $this->addUsingAlias(AnswerTableMap::COL_ID, $answer->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the defender_answer table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AnswerTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AnswerTableMap::clearInstancePool();
            AnswerTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AnswerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AnswerTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AnswerTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AnswerTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AnswerQuery
