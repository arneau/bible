<?php

namespace Base;

use \Topic as ChildTopic;
use \TopicQuery as ChildTopicQuery;
use \Exception;
use \PDO;
use Map\TopicTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'defender_topic' table.
 *
 *
 *
 * @method     ChildTopicQuery orderByIsRoot($order = Criteria::ASC) Order by the is_root column
 * @method     ChildTopicQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildTopicQuery orderByTagCount($order = Criteria::ASC) Order by the tag_count column
 * @method     ChildTopicQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildTopicQuery groupByIsRoot() Group by the is_root column
 * @method     ChildTopicQuery groupByName() Group by the name column
 * @method     ChildTopicQuery groupByTagCount() Group by the tag_count column
 * @method     ChildTopicQuery groupById() Group by the id column
 *
 * @method     ChildTopicQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTopicQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTopicQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTopicQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTopicQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTopicQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTopicQuery leftJoinTopicLesson($relationAlias = null) Adds a LEFT JOIN clause to the query using the TopicLesson relation
 * @method     ChildTopicQuery rightJoinTopicLesson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TopicLesson relation
 * @method     ChildTopicQuery innerJoinTopicLesson($relationAlias = null) Adds a INNER JOIN clause to the query using the TopicLesson relation
 *
 * @method     ChildTopicQuery joinWithTopicLesson($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TopicLesson relation
 *
 * @method     ChildTopicQuery leftJoinWithTopicLesson() Adds a LEFT JOIN clause and with to the query using the TopicLesson relation
 * @method     ChildTopicQuery rightJoinWithTopicLesson() Adds a RIGHT JOIN clause and with to the query using the TopicLesson relation
 * @method     ChildTopicQuery innerJoinWithTopicLesson() Adds a INNER JOIN clause and with to the query using the TopicLesson relation
 *
 * @method     ChildTopicQuery leftJoinTopicLink($relationAlias = null) Adds a LEFT JOIN clause to the query using the TopicLink relation
 * @method     ChildTopicQuery rightJoinTopicLink($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TopicLink relation
 * @method     ChildTopicQuery innerJoinTopicLink($relationAlias = null) Adds a INNER JOIN clause to the query using the TopicLink relation
 *
 * @method     ChildTopicQuery joinWithTopicLink($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TopicLink relation
 *
 * @method     ChildTopicQuery leftJoinWithTopicLink() Adds a LEFT JOIN clause and with to the query using the TopicLink relation
 * @method     ChildTopicQuery rightJoinWithTopicLink() Adds a RIGHT JOIN clause and with to the query using the TopicLink relation
 * @method     ChildTopicQuery innerJoinWithTopicLink() Adds a INNER JOIN clause and with to the query using the TopicLink relation
 *
 * @method     ChildTopicQuery leftJoinTopicParent($relationAlias = null) Adds a LEFT JOIN clause to the query using the TopicParent relation
 * @method     ChildTopicQuery rightJoinTopicParent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TopicParent relation
 * @method     ChildTopicQuery innerJoinTopicParent($relationAlias = null) Adds a INNER JOIN clause to the query using the TopicParent relation
 *
 * @method     ChildTopicQuery joinWithTopicParent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TopicParent relation
 *
 * @method     ChildTopicQuery leftJoinWithTopicParent() Adds a LEFT JOIN clause and with to the query using the TopicParent relation
 * @method     ChildTopicQuery rightJoinWithTopicParent() Adds a RIGHT JOIN clause and with to the query using the TopicParent relation
 * @method     ChildTopicQuery innerJoinWithTopicParent() Adds a INNER JOIN clause and with to the query using the TopicParent relation
 *
 * @method     ChildTopicQuery leftJoinTopicTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the TopicTag relation
 * @method     ChildTopicQuery rightJoinTopicTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TopicTag relation
 * @method     ChildTopicQuery innerJoinTopicTag($relationAlias = null) Adds a INNER JOIN clause to the query using the TopicTag relation
 *
 * @method     ChildTopicQuery joinWithTopicTag($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TopicTag relation
 *
 * @method     ChildTopicQuery leftJoinWithTopicTag() Adds a LEFT JOIN clause and with to the query using the TopicTag relation
 * @method     ChildTopicQuery rightJoinWithTopicTag() Adds a RIGHT JOIN clause and with to the query using the TopicTag relation
 * @method     ChildTopicQuery innerJoinWithTopicTag() Adds a INNER JOIN clause and with to the query using the TopicTag relation
 *
 * @method     ChildTopicQuery leftJoinTopicSynonym($relationAlias = null) Adds a LEFT JOIN clause to the query using the TopicSynonym relation
 * @method     ChildTopicQuery rightJoinTopicSynonym($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TopicSynonym relation
 * @method     ChildTopicQuery innerJoinTopicSynonym($relationAlias = null) Adds a INNER JOIN clause to the query using the TopicSynonym relation
 *
 * @method     ChildTopicQuery joinWithTopicSynonym($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TopicSynonym relation
 *
 * @method     ChildTopicQuery leftJoinWithTopicSynonym() Adds a LEFT JOIN clause and with to the query using the TopicSynonym relation
 * @method     ChildTopicQuery rightJoinWithTopicSynonym() Adds a RIGHT JOIN clause and with to the query using the TopicSynonym relation
 * @method     ChildTopicQuery innerJoinWithTopicSynonym() Adds a INNER JOIN clause and with to the query using the TopicSynonym relation
 *
 * @method     \TopicLessonQuery|\TopicLinkQuery|\TopicParentQuery|\TopicTagQuery|\TopicSynonymQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTopic findOne(ConnectionInterface $con = null) Return the first ChildTopic matching the query
 * @method     ChildTopic findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTopic matching the query, or a new ChildTopic object populated from the query conditions when no match is found
 *
 * @method     ChildTopic findOneByIsRoot(boolean $is_root) Return the first ChildTopic filtered by the is_root column
 * @method     ChildTopic findOneByName(string $name) Return the first ChildTopic filtered by the name column
 * @method     ChildTopic findOneByTagCount(int $tag_count) Return the first ChildTopic filtered by the tag_count column
 * @method     ChildTopic findOneById(int $id) Return the first ChildTopic filtered by the id column *

 * @method     ChildTopic requirePk($key, ConnectionInterface $con = null) Return the ChildTopic by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTopic requireOne(ConnectionInterface $con = null) Return the first ChildTopic matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTopic requireOneByIsRoot(boolean $is_root) Return the first ChildTopic filtered by the is_root column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTopic requireOneByName(string $name) Return the first ChildTopic filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTopic requireOneByTagCount(int $tag_count) Return the first ChildTopic filtered by the tag_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTopic requireOneById(int $id) Return the first ChildTopic filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTopic[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTopic objects based on current ModelCriteria
 * @method     ChildTopic[]|ObjectCollection findByIsRoot(boolean $is_root) Return ChildTopic objects filtered by the is_root column
 * @method     ChildTopic[]|ObjectCollection findByName(string $name) Return ChildTopic objects filtered by the name column
 * @method     ChildTopic[]|ObjectCollection findByTagCount(int $tag_count) Return ChildTopic objects filtered by the tag_count column
 * @method     ChildTopic[]|ObjectCollection findById(int $id) Return ChildTopic objects filtered by the id column
 * @method     ChildTopic[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TopicQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\TopicQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Topic', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTopicQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTopicQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTopicQuery) {
            return $criteria;
        }
        $query = new ChildTopicQuery();
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
     * @return ChildTopic|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TopicTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TopicTableMap::DATABASE_NAME);
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
     * @return ChildTopic A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT is_root, name, tag_count, id FROM defender_topic WHERE id = :p0';
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
            /** @var ChildTopic $obj */
            $obj = new ChildTopic();
            $obj->hydrate($row);
            TopicTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildTopic|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TopicTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TopicTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the is_root column
     *
     * Example usage:
     * <code>
     * $query->filterByIsRoot(true); // WHERE is_root = true
     * $query->filterByIsRoot('yes'); // WHERE is_root = true
     * </code>
     *
     * @param     boolean|string $isRoot The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterByIsRoot($isRoot = null, $comparison = null)
    {
        if (is_string($isRoot)) {
            $isRoot = in_array(strtolower($isRoot), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TopicTableMap::COL_IS_ROOT, $isRoot, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TopicTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the tag_count column
     *
     * Example usage:
     * <code>
     * $query->filterByTagCount(1234); // WHERE tag_count = 1234
     * $query->filterByTagCount(array(12, 34)); // WHERE tag_count IN (12, 34)
     * $query->filterByTagCount(array('min' => 12)); // WHERE tag_count > 12
     * </code>
     *
     * @param     mixed $tagCount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterByTagCount($tagCount = null, $comparison = null)
    {
        if (is_array($tagCount)) {
            $useMinMax = false;
            if (isset($tagCount['min'])) {
                $this->addUsingAlias(TopicTableMap::COL_TAG_COUNT, $tagCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tagCount['max'])) {
                $this->addUsingAlias(TopicTableMap::COL_TAG_COUNT, $tagCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TopicTableMap::COL_TAG_COUNT, $tagCount, $comparison);
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
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TopicTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TopicTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TopicTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \TopicLesson object
     *
     * @param \TopicLesson|ObjectCollection $topicLesson the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTopicQuery The current query, for fluid interface
     */
    public function filterByTopicLesson($topicLesson, $comparison = null)
    {
        if ($topicLesson instanceof \TopicLesson) {
            return $this
                ->addUsingAlias(TopicTableMap::COL_ID, $topicLesson->getTopicId(), $comparison);
        } elseif ($topicLesson instanceof ObjectCollection) {
            return $this
                ->useTopicLessonQuery()
                ->filterByPrimaryKeys($topicLesson->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTopicLesson() only accepts arguments of type \TopicLesson or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TopicLesson relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function joinTopicLesson($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TopicLesson');

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
            $this->addJoinObject($join, 'TopicLesson');
        }

        return $this;
    }

    /**
     * Use the TopicLesson relation TopicLesson object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TopicLessonQuery A secondary query class using the current class as primary query
     */
    public function useTopicLessonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTopicLesson($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TopicLesson', '\TopicLessonQuery');
    }

    /**
     * Filter the query by a related \TopicLink object
     *
     * @param \TopicLink|ObjectCollection $topicLink the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTopicQuery The current query, for fluid interface
     */
    public function filterByTopicLink($topicLink, $comparison = null)
    {
        if ($topicLink instanceof \TopicLink) {
            return $this
                ->addUsingAlias(TopicTableMap::COL_ID, $topicLink->getTopicId(), $comparison);
        } elseif ($topicLink instanceof ObjectCollection) {
            return $this
                ->useTopicLinkQuery()
                ->filterByPrimaryKeys($topicLink->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTopicLink() only accepts arguments of type \TopicLink or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TopicLink relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function joinTopicLink($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TopicLink');

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
            $this->addJoinObject($join, 'TopicLink');
        }

        return $this;
    }

    /**
     * Use the TopicLink relation TopicLink object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TopicLinkQuery A secondary query class using the current class as primary query
     */
    public function useTopicLinkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTopicLink($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TopicLink', '\TopicLinkQuery');
    }

    /**
     * Filter the query by a related \TopicParent object
     *
     * @param \TopicParent|ObjectCollection $topicParent the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTopicQuery The current query, for fluid interface
     */
    public function filterByTopicParent($topicParent, $comparison = null)
    {
        if ($topicParent instanceof \TopicParent) {
            return $this
                ->addUsingAlias(TopicTableMap::COL_ID, $topicParent->getTopicId(), $comparison);
        } elseif ($topicParent instanceof ObjectCollection) {
            return $this
                ->useTopicParentQuery()
                ->filterByPrimaryKeys($topicParent->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTopicParent() only accepts arguments of type \TopicParent or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TopicParent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function joinTopicParent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TopicParent');

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
            $this->addJoinObject($join, 'TopicParent');
        }

        return $this;
    }

    /**
     * Use the TopicParent relation TopicParent object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TopicParentQuery A secondary query class using the current class as primary query
     */
    public function useTopicParentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTopicParent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TopicParent', '\TopicParentQuery');
    }

    /**
     * Filter the query by a related \TopicTag object
     *
     * @param \TopicTag|ObjectCollection $topicTag the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTopicQuery The current query, for fluid interface
     */
    public function filterByTopicTag($topicTag, $comparison = null)
    {
        if ($topicTag instanceof \TopicTag) {
            return $this
                ->addUsingAlias(TopicTableMap::COL_ID, $topicTag->getTopicId(), $comparison);
        } elseif ($topicTag instanceof ObjectCollection) {
            return $this
                ->useTopicTagQuery()
                ->filterByPrimaryKeys($topicTag->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTopicTag() only accepts arguments of type \TopicTag or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TopicTag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function joinTopicTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TopicTag');

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
            $this->addJoinObject($join, 'TopicTag');
        }

        return $this;
    }

    /**
     * Use the TopicTag relation TopicTag object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TopicTagQuery A secondary query class using the current class as primary query
     */
    public function useTopicTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTopicTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TopicTag', '\TopicTagQuery');
    }

    /**
     * Filter the query by a related \TopicSynonym object
     *
     * @param \TopicSynonym|ObjectCollection $topicSynonym the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTopicQuery The current query, for fluid interface
     */
    public function filterByTopicSynonym($topicSynonym, $comparison = null)
    {
        if ($topicSynonym instanceof \TopicSynonym) {
            return $this
                ->addUsingAlias(TopicTableMap::COL_ID, $topicSynonym->getTopicId(), $comparison);
        } elseif ($topicSynonym instanceof ObjectCollection) {
            return $this
                ->useTopicSynonymQuery()
                ->filterByPrimaryKeys($topicSynonym->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTopicSynonym() only accepts arguments of type \TopicSynonym or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TopicSynonym relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function joinTopicSynonym($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TopicSynonym');

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
            $this->addJoinObject($join, 'TopicSynonym');
        }

        return $this;
    }

    /**
     * Use the TopicSynonym relation TopicSynonym object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TopicSynonymQuery A secondary query class using the current class as primary query
     */
    public function useTopicSynonymQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTopicSynonym($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TopicSynonym', '\TopicSynonymQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTopic $topic Object to remove from the list of results
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function prune($topic = null)
    {
        if ($topic) {
            $this->addUsingAlias(TopicTableMap::COL_ID, $topic->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the defender_topic table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TopicTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TopicTableMap::clearInstancePool();
            TopicTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TopicTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TopicTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TopicTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TopicTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // TopicQuery
