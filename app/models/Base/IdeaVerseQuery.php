<?php

namespace Base;

use \IdeaVerse as ChildIdeaVerse;
use \IdeaVerseQuery as ChildIdeaVerseQuery;
use \Exception;
use \PDO;
use Map\IdeaVerseTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'defender_idea_verse' table.
 *
 *
 *
 * @method     ChildIdeaVerseQuery orderByIdeaId($order = Criteria::ASC) Order by the idea_id column
 * @method     ChildIdeaVerseQuery orderByVerseId($order = Criteria::ASC) Order by the verse_id column
 * @method     ChildIdeaVerseQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildIdeaVerseQuery groupByIdeaId() Group by the idea_id column
 * @method     ChildIdeaVerseQuery groupByVerseId() Group by the verse_id column
 * @method     ChildIdeaVerseQuery groupById() Group by the id column
 *
 * @method     ChildIdeaVerseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildIdeaVerseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildIdeaVerseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildIdeaVerseQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildIdeaVerseQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildIdeaVerseQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildIdeaVerseQuery leftJoinIdea($relationAlias = null) Adds a LEFT JOIN clause to the query using the Idea relation
 * @method     ChildIdeaVerseQuery rightJoinIdea($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Idea relation
 * @method     ChildIdeaVerseQuery innerJoinIdea($relationAlias = null) Adds a INNER JOIN clause to the query using the Idea relation
 *
 * @method     ChildIdeaVerseQuery joinWithIdea($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Idea relation
 *
 * @method     ChildIdeaVerseQuery leftJoinWithIdea() Adds a LEFT JOIN clause and with to the query using the Idea relation
 * @method     ChildIdeaVerseQuery rightJoinWithIdea() Adds a RIGHT JOIN clause and with to the query using the Idea relation
 * @method     ChildIdeaVerseQuery innerJoinWithIdea() Adds a INNER JOIN clause and with to the query using the Idea relation
 *
 * @method     ChildIdeaVerseQuery leftJoinVerse($relationAlias = null) Adds a LEFT JOIN clause to the query using the Verse relation
 * @method     ChildIdeaVerseQuery rightJoinVerse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Verse relation
 * @method     ChildIdeaVerseQuery innerJoinVerse($relationAlias = null) Adds a INNER JOIN clause to the query using the Verse relation
 *
 * @method     ChildIdeaVerseQuery joinWithVerse($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Verse relation
 *
 * @method     ChildIdeaVerseQuery leftJoinWithVerse() Adds a LEFT JOIN clause and with to the query using the Verse relation
 * @method     ChildIdeaVerseQuery rightJoinWithVerse() Adds a RIGHT JOIN clause and with to the query using the Verse relation
 * @method     ChildIdeaVerseQuery innerJoinWithVerse() Adds a INNER JOIN clause and with to the query using the Verse relation
 *
 * @method     \IdeaQuery|\VerseQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildIdeaVerse findOne(ConnectionInterface $con = null) Return the first ChildIdeaVerse matching the query
 * @method     ChildIdeaVerse findOneOrCreate(ConnectionInterface $con = null) Return the first ChildIdeaVerse matching the query, or a new ChildIdeaVerse object populated from the query conditions when no match is found
 *
 * @method     ChildIdeaVerse findOneByIdeaId(int $idea_id) Return the first ChildIdeaVerse filtered by the idea_id column
 * @method     ChildIdeaVerse findOneByVerseId(int $verse_id) Return the first ChildIdeaVerse filtered by the verse_id column
 * @method     ChildIdeaVerse findOneById(int $id) Return the first ChildIdeaVerse filtered by the id column *

 * @method     ChildIdeaVerse requirePk($key, ConnectionInterface $con = null) Return the ChildIdeaVerse by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIdeaVerse requireOne(ConnectionInterface $con = null) Return the first ChildIdeaVerse matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildIdeaVerse requireOneByIdeaId(int $idea_id) Return the first ChildIdeaVerse filtered by the idea_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIdeaVerse requireOneByVerseId(int $verse_id) Return the first ChildIdeaVerse filtered by the verse_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIdeaVerse requireOneById(int $id) Return the first ChildIdeaVerse filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildIdeaVerse[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildIdeaVerse objects based on current ModelCriteria
 * @method     ChildIdeaVerse[]|ObjectCollection findByIdeaId(int $idea_id) Return ChildIdeaVerse objects filtered by the idea_id column
 * @method     ChildIdeaVerse[]|ObjectCollection findByVerseId(int $verse_id) Return ChildIdeaVerse objects filtered by the verse_id column
 * @method     ChildIdeaVerse[]|ObjectCollection findById(int $id) Return ChildIdeaVerse objects filtered by the id column
 * @method     ChildIdeaVerse[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class IdeaVerseQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\IdeaVerseQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\IdeaVerse', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildIdeaVerseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildIdeaVerseQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildIdeaVerseQuery) {
            return $criteria;
        }
        $query = new ChildIdeaVerseQuery();
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
     * @return ChildIdeaVerse|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = IdeaVerseTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(IdeaVerseTableMap::DATABASE_NAME);
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
     * @return ChildIdeaVerse A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT idea_id, verse_id, id FROM defender_idea_verse WHERE id = :p0';
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
            /** @var ChildIdeaVerse $obj */
            $obj = new ChildIdeaVerse();
            $obj->hydrate($row);
            IdeaVerseTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildIdeaVerse|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildIdeaVerseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(IdeaVerseTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildIdeaVerseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(IdeaVerseTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the idea_id column
     *
     * Example usage:
     * <code>
     * $query->filterByIdeaId(1234); // WHERE idea_id = 1234
     * $query->filterByIdeaId(array(12, 34)); // WHERE idea_id IN (12, 34)
     * $query->filterByIdeaId(array('min' => 12)); // WHERE idea_id > 12
     * </code>
     *
     * @see       filterByIdea()
     *
     * @param     mixed $ideaId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildIdeaVerseQuery The current query, for fluid interface
     */
    public function filterByIdeaId($ideaId = null, $comparison = null)
    {
        if (is_array($ideaId)) {
            $useMinMax = false;
            if (isset($ideaId['min'])) {
                $this->addUsingAlias(IdeaVerseTableMap::COL_IDEA_ID, $ideaId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ideaId['max'])) {
                $this->addUsingAlias(IdeaVerseTableMap::COL_IDEA_ID, $ideaId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IdeaVerseTableMap::COL_IDEA_ID, $ideaId, $comparison);
    }

    /**
     * Filter the query on the verse_id column
     *
     * Example usage:
     * <code>
     * $query->filterByVerseId(1234); // WHERE verse_id = 1234
     * $query->filterByVerseId(array(12, 34)); // WHERE verse_id IN (12, 34)
     * $query->filterByVerseId(array('min' => 12)); // WHERE verse_id > 12
     * </code>
     *
     * @see       filterByVerse()
     *
     * @param     mixed $verseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildIdeaVerseQuery The current query, for fluid interface
     */
    public function filterByVerseId($verseId = null, $comparison = null)
    {
        if (is_array($verseId)) {
            $useMinMax = false;
            if (isset($verseId['min'])) {
                $this->addUsingAlias(IdeaVerseTableMap::COL_VERSE_ID, $verseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($verseId['max'])) {
                $this->addUsingAlias(IdeaVerseTableMap::COL_VERSE_ID, $verseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IdeaVerseTableMap::COL_VERSE_ID, $verseId, $comparison);
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
     * @return $this|ChildIdeaVerseQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(IdeaVerseTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(IdeaVerseTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IdeaVerseTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \Idea object
     *
     * @param \Idea|ObjectCollection $idea The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildIdeaVerseQuery The current query, for fluid interface
     */
    public function filterByIdea($idea, $comparison = null)
    {
        if ($idea instanceof \Idea) {
            return $this
                ->addUsingAlias(IdeaVerseTableMap::COL_IDEA_ID, $idea->getId(), $comparison);
        } elseif ($idea instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IdeaVerseTableMap::COL_IDEA_ID, $idea->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByIdea() only accepts arguments of type \Idea or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Idea relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildIdeaVerseQuery The current query, for fluid interface
     */
    public function joinIdea($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Idea');

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
            $this->addJoinObject($join, 'Idea');
        }

        return $this;
    }

    /**
     * Use the Idea relation Idea object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \IdeaQuery A secondary query class using the current class as primary query
     */
    public function useIdeaQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinIdea($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Idea', '\IdeaQuery');
    }

    /**
     * Filter the query by a related \Verse object
     *
     * @param \Verse|ObjectCollection $verse The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildIdeaVerseQuery The current query, for fluid interface
     */
    public function filterByVerse($verse, $comparison = null)
    {
        if ($verse instanceof \Verse) {
            return $this
                ->addUsingAlias(IdeaVerseTableMap::COL_VERSE_ID, $verse->getId(), $comparison);
        } elseif ($verse instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IdeaVerseTableMap::COL_VERSE_ID, $verse->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByVerse() only accepts arguments of type \Verse or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Verse relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildIdeaVerseQuery The current query, for fluid interface
     */
    public function joinVerse($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Verse');

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
            $this->addJoinObject($join, 'Verse');
        }

        return $this;
    }

    /**
     * Use the Verse relation Verse object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \VerseQuery A secondary query class using the current class as primary query
     */
    public function useVerseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinVerse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Verse', '\VerseQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildIdeaVerse $ideaVerse Object to remove from the list of results
     *
     * @return $this|ChildIdeaVerseQuery The current query, for fluid interface
     */
    public function prune($ideaVerse = null)
    {
        if ($ideaVerse) {
            $this->addUsingAlias(IdeaVerseTableMap::COL_ID, $ideaVerse->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     ConnectionInterface $con The connection object used by the query
     */
    protected function basePreDelete(ConnectionInterface $con)
    {
        // aggregate_column_relation_aggregate_column behavior
        $this->findRelatedIdeaVerseCounts($con);

        return $this->preDelete($con);
    }

    /**
     * Code to execute after every DELETE statement
     *
     * @param     int $affectedRows the number of deleted rows
     * @param     ConnectionInterface $con The connection object used by the query
     */
    protected function basePostDelete($affectedRows, ConnectionInterface $con)
    {
        // aggregate_column_relation_aggregate_column behavior
        $this->updateRelatedIdeaVerseCounts($con);

        return $this->postDelete($affectedRows, $con);
    }

    /**
     * Code to execute before every UPDATE statement
     *
     * @param     array $values The associative array of columns and values for the update
     * @param     ConnectionInterface $con The connection object used by the query
     * @param     boolean $forceIndividualSaves If false (default), the resulting call is a Criteria::doUpdate(), otherwise it is a series of save() calls on all the found objects
     */
    protected function basePreUpdate(&$values, ConnectionInterface $con, $forceIndividualSaves = false)
    {
        // aggregate_column_relation_aggregate_column behavior
        $this->findRelatedIdeaVerseCounts($con);

        return $this->preUpdate($values, $con, $forceIndividualSaves);
    }

    /**
     * Code to execute after every UPDATE statement
     *
     * @param     int $affectedRows the number of updated rows
     * @param     ConnectionInterface $con The connection object used by the query
     */
    protected function basePostUpdate($affectedRows, ConnectionInterface $con)
    {
        // aggregate_column_relation_aggregate_column behavior
        $this->updateRelatedIdeaVerseCounts($con);

        return $this->postUpdate($affectedRows, $con);
    }

    /**
     * Deletes all rows from the defender_idea_verse table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IdeaVerseTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            IdeaVerseTableMap::clearInstancePool();
            IdeaVerseTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(IdeaVerseTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(IdeaVerseTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            IdeaVerseTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            IdeaVerseTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // aggregate_column_relation_aggregate_column behavior

    /**
     * Finds the related Idea objects and keep them for later
     *
     * @param ConnectionInterface $con A connection object
     */
    protected function findRelatedIdeaVerseCounts($con)
    {
        $criteria = clone $this;
        if ($this->useAliasInSQL) {
            $alias = $this->getModelAlias();
            $criteria->removeAlias($alias);
        } else {
            $alias = '';
        }
        $this->ideaVerseCounts = \IdeaQuery::create()
            ->joinIdeaVerse($alias)
            ->mergeWith($criteria)
            ->find($con);
    }

    protected function updateRelatedIdeaVerseCounts($con)
    {
        foreach ($this->ideaVerseCounts as $ideaVerseCount) {
            $ideaVerseCount->updateVerseCount($con);
        }
        $this->ideaVerseCounts = array();
    }

} // IdeaVerseQuery
