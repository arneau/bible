<?php

namespace Base;

use \Tag as ChildTag;
use \TagQuery as ChildTagQuery;
use \Exception;
use \PDO;
use Map\TagTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'defender_tag' table.
 *
 *
 *
 * @method     ChildTagQuery orderByVerseId($order = Criteria::ASC) Order by the verse_id column
 * @method     ChildTagQuery orderByVoteCount($order = Criteria::ASC) Order by the vote_count column
 * @method     ChildTagQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildTagQuery groupByVerseId() Group by the verse_id column
 * @method     ChildTagQuery groupByVoteCount() Group by the vote_count column
 * @method     ChildTagQuery groupById() Group by the id column
 *
 * @method     ChildTagQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTagQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTagQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTagQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTagQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTagQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTagQuery leftJoinVerse($relationAlias = null) Adds a LEFT JOIN clause to the query using the Verse relation
 * @method     ChildTagQuery rightJoinVerse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Verse relation
 * @method     ChildTagQuery innerJoinVerse($relationAlias = null) Adds a INNER JOIN clause to the query using the Verse relation
 *
 * @method     ChildTagQuery joinWithVerse($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Verse relation
 *
 * @method     ChildTagQuery leftJoinWithVerse() Adds a LEFT JOIN clause and with to the query using the Verse relation
 * @method     ChildTagQuery rightJoinWithVerse() Adds a RIGHT JOIN clause and with to the query using the Verse relation
 * @method     ChildTagQuery innerJoinWithVerse() Adds a INNER JOIN clause and with to the query using the Verse relation
 *
 * @method     ChildTagQuery leftJoinTagTranslation($relationAlias = null) Adds a LEFT JOIN clause to the query using the TagTranslation relation
 * @method     ChildTagQuery rightJoinTagTranslation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TagTranslation relation
 * @method     ChildTagQuery innerJoinTagTranslation($relationAlias = null) Adds a INNER JOIN clause to the query using the TagTranslation relation
 *
 * @method     ChildTagQuery joinWithTagTranslation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TagTranslation relation
 *
 * @method     ChildTagQuery leftJoinWithTagTranslation() Adds a LEFT JOIN clause and with to the query using the TagTranslation relation
 * @method     ChildTagQuery rightJoinWithTagTranslation() Adds a RIGHT JOIN clause and with to the query using the TagTranslation relation
 * @method     ChildTagQuery innerJoinWithTagTranslation() Adds a INNER JOIN clause and with to the query using the TagTranslation relation
 *
 * @method     ChildTagQuery leftJoinTagVote($relationAlias = null) Adds a LEFT JOIN clause to the query using the TagVote relation
 * @method     ChildTagQuery rightJoinTagVote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TagVote relation
 * @method     ChildTagQuery innerJoinTagVote($relationAlias = null) Adds a INNER JOIN clause to the query using the TagVote relation
 *
 * @method     ChildTagQuery joinWithTagVote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TagVote relation
 *
 * @method     ChildTagQuery leftJoinWithTagVote() Adds a LEFT JOIN clause and with to the query using the TagVote relation
 * @method     ChildTagQuery rightJoinWithTagVote() Adds a RIGHT JOIN clause and with to the query using the TagVote relation
 * @method     ChildTagQuery innerJoinWithTagVote() Adds a INNER JOIN clause and with to the query using the TagVote relation
 *
 * @method     ChildTagQuery leftJoinTopicTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the TopicTag relation
 * @method     ChildTagQuery rightJoinTopicTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TopicTag relation
 * @method     ChildTagQuery innerJoinTopicTag($relationAlias = null) Adds a INNER JOIN clause to the query using the TopicTag relation
 *
 * @method     ChildTagQuery joinWithTopicTag($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TopicTag relation
 *
 * @method     ChildTagQuery leftJoinWithTopicTag() Adds a LEFT JOIN clause and with to the query using the TopicTag relation
 * @method     ChildTagQuery rightJoinWithTopicTag() Adds a RIGHT JOIN clause and with to the query using the TopicTag relation
 * @method     ChildTagQuery innerJoinWithTopicTag() Adds a INNER JOIN clause and with to the query using the TopicTag relation
 *
 * @method     ChildTagQuery leftJoinLessonTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the LessonTag relation
 * @method     ChildTagQuery rightJoinLessonTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LessonTag relation
 * @method     ChildTagQuery innerJoinLessonTag($relationAlias = null) Adds a INNER JOIN clause to the query using the LessonTag relation
 *
 * @method     ChildTagQuery joinWithLessonTag($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LessonTag relation
 *
 * @method     ChildTagQuery leftJoinWithLessonTag() Adds a LEFT JOIN clause and with to the query using the LessonTag relation
 * @method     ChildTagQuery rightJoinWithLessonTag() Adds a RIGHT JOIN clause and with to the query using the LessonTag relation
 * @method     ChildTagQuery innerJoinWithLessonTag() Adds a INNER JOIN clause and with to the query using the LessonTag relation
 *
 * @method     \VerseQuery|\TagTranslationQuery|\TagVoteQuery|\TopicTagQuery|\LessonTagQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTag findOne(ConnectionInterface $con = null) Return the first ChildTag matching the query
 * @method     ChildTag findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTag matching the query, or a new ChildTag object populated from the query conditions when no match is found
 *
 * @method     ChildTag findOneByVerseId(int $verse_id) Return the first ChildTag filtered by the verse_id column
 * @method     ChildTag findOneByVoteCount(int $vote_count) Return the first ChildTag filtered by the vote_count column
 * @method     ChildTag findOneById(int $id) Return the first ChildTag filtered by the id column *

 * @method     ChildTag requirePk($key, ConnectionInterface $con = null) Return the ChildTag by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOne(ConnectionInterface $con = null) Return the first ChildTag matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTag requireOneByVerseId(int $verse_id) Return the first ChildTag filtered by the verse_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByVoteCount(int $vote_count) Return the first ChildTag filtered by the vote_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneById(int $id) Return the first ChildTag filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTag[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTag objects based on current ModelCriteria
 * @method     ChildTag[]|ObjectCollection findByVerseId(int $verse_id) Return ChildTag objects filtered by the verse_id column
 * @method     ChildTag[]|ObjectCollection findByVoteCount(int $vote_count) Return ChildTag objects filtered by the vote_count column
 * @method     ChildTag[]|ObjectCollection findById(int $id) Return ChildTag objects filtered by the id column
 * @method     ChildTag[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TagQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\TagQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tag', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTagQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTagQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTagQuery) {
            return $criteria;
        }
        $query = new ChildTagQuery();
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
     * @return ChildTag|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TagTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TagTableMap::DATABASE_NAME);
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
     * @return ChildTag A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT verse_id, vote_count, id FROM defender_tag WHERE id = :p0';
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
            /** @var ChildTag $obj */
            $obj = new ChildTag();
            $obj->hydrate($row);
            TagTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildTag|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TagTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TagTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByVerseId($verseId = null, $comparison = null)
    {
        if (is_array($verseId)) {
            $useMinMax = false;
            if (isset($verseId['min'])) {
                $this->addUsingAlias(TagTableMap::COL_VERSE_ID, $verseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($verseId['max'])) {
                $this->addUsingAlias(TagTableMap::COL_VERSE_ID, $verseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_VERSE_ID, $verseId, $comparison);
    }

    /**
     * Filter the query on the vote_count column
     *
     * Example usage:
     * <code>
     * $query->filterByVoteCount(1234); // WHERE vote_count = 1234
     * $query->filterByVoteCount(array(12, 34)); // WHERE vote_count IN (12, 34)
     * $query->filterByVoteCount(array('min' => 12)); // WHERE vote_count > 12
     * </code>
     *
     * @param     mixed $voteCount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByVoteCount($voteCount = null, $comparison = null)
    {
        if (is_array($voteCount)) {
            $useMinMax = false;
            if (isset($voteCount['min'])) {
                $this->addUsingAlias(TagTableMap::COL_VOTE_COUNT, $voteCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($voteCount['max'])) {
                $this->addUsingAlias(TagTableMap::COL_VOTE_COUNT, $voteCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_VOTE_COUNT, $voteCount, $comparison);
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
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TagTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TagTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \Verse object
     *
     * @param \Verse|ObjectCollection $verse The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildTagQuery The current query, for fluid interface
     */
    public function filterByVerse($verse, $comparison = null)
    {
        if ($verse instanceof \Verse) {
            return $this
                ->addUsingAlias(TagTableMap::COL_VERSE_ID, $verse->getId(), $comparison);
        } elseif ($verse instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TagTableMap::COL_VERSE_ID, $verse->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildTagQuery The current query, for fluid interface
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
     * Filter the query by a related \TagTranslation object
     *
     * @param \TagTranslation|ObjectCollection $tagTranslation the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTagQuery The current query, for fluid interface
     */
    public function filterByTagTranslation($tagTranslation, $comparison = null)
    {
        if ($tagTranslation instanceof \TagTranslation) {
            return $this
                ->addUsingAlias(TagTableMap::COL_ID, $tagTranslation->getTagId(), $comparison);
        } elseif ($tagTranslation instanceof ObjectCollection) {
            return $this
                ->useTagTranslationQuery()
                ->filterByPrimaryKeys($tagTranslation->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTagTranslation() only accepts arguments of type \TagTranslation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TagTranslation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function joinTagTranslation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TagTranslation');

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
            $this->addJoinObject($join, 'TagTranslation');
        }

        return $this;
    }

    /**
     * Use the TagTranslation relation TagTranslation object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TagTranslationQuery A secondary query class using the current class as primary query
     */
    public function useTagTranslationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTagTranslation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TagTranslation', '\TagTranslationQuery');
    }

    /**
     * Filter the query by a related \TagVote object
     *
     * @param \TagVote|ObjectCollection $tagVote the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTagQuery The current query, for fluid interface
     */
    public function filterByTagVote($tagVote, $comparison = null)
    {
        if ($tagVote instanceof \TagVote) {
            return $this
                ->addUsingAlias(TagTableMap::COL_ID, $tagVote->getTagId(), $comparison);
        } elseif ($tagVote instanceof ObjectCollection) {
            return $this
                ->useTagVoteQuery()
                ->filterByPrimaryKeys($tagVote->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTagVote() only accepts arguments of type \TagVote or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TagVote relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function joinTagVote($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TagVote');

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
            $this->addJoinObject($join, 'TagVote');
        }

        return $this;
    }

    /**
     * Use the TagVote relation TagVote object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TagVoteQuery A secondary query class using the current class as primary query
     */
    public function useTagVoteQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTagVote($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TagVote', '\TagVoteQuery');
    }

    /**
     * Filter the query by a related \TopicTag object
     *
     * @param \TopicTag|ObjectCollection $topicTag the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTagQuery The current query, for fluid interface
     */
    public function filterByTopicTag($topicTag, $comparison = null)
    {
        if ($topicTag instanceof \TopicTag) {
            return $this
                ->addUsingAlias(TagTableMap::COL_ID, $topicTag->getTagId(), $comparison);
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
     * @return $this|ChildTagQuery The current query, for fluid interface
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
     * Filter the query by a related \LessonTag object
     *
     * @param \LessonTag|ObjectCollection $lessonTag the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTagQuery The current query, for fluid interface
     */
    public function filterByLessonTag($lessonTag, $comparison = null)
    {
        if ($lessonTag instanceof \LessonTag) {
            return $this
                ->addUsingAlias(TagTableMap::COL_ID, $lessonTag->getTagId(), $comparison);
        } elseif ($lessonTag instanceof ObjectCollection) {
            return $this
                ->useLessonTagQuery()
                ->filterByPrimaryKeys($lessonTag->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLessonTag() only accepts arguments of type \LessonTag or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LessonTag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function joinLessonTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LessonTag');

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
            $this->addJoinObject($join, 'LessonTag');
        }

        return $this;
    }

    /**
     * Use the LessonTag relation LessonTag object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LessonTagQuery A secondary query class using the current class as primary query
     */
    public function useLessonTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLessonTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LessonTag', '\LessonTagQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTag $tag Object to remove from the list of results
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function prune($tag = null)
    {
        if ($tag) {
            $this->addUsingAlias(TagTableMap::COL_ID, $tag->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the defender_tag table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TagTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TagTableMap::clearInstancePool();
            TagTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TagTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TagTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TagTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TagTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // TagQuery
