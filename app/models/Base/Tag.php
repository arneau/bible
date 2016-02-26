<?php

namespace Base;

use \Tag as ChildTag;
use \TagQuery as ChildTagQuery;
use \TagVote as ChildTagVote;
use \TagVoteQuery as ChildTagVoteQuery;
use \Topic as ChildTopic;
use \TopicQuery as ChildTopicQuery;
use \Verse as ChildVerse;
use \VerseQuery as ChildVerseQuery;
use \Exception;
use \PDO;
use Map\TagTableMap;
use Map\TagVoteTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'defender_tag' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Tag implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\TagTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the relevant_words field.
     *
     * @var        string
     */
    protected $relevant_words;

    /**
     * The value for the topic_id field.
     *
     * @var        int
     */
    protected $topic_id;

    /**
     * The value for the verse_id field.
     *
     * @var        int
     */
    protected $verse_id;

    /**
     * The value for the vote_count field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $vote_count;

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * @var        ChildTopic
     */
    protected $aTopic;

    /**
     * @var        ChildVerse
     */
    protected $aVerse;

    /**
     * @var        ObjectCollection|ChildTagVote[] Collection to store aggregation of ChildTagVote objects.
     */
    protected $collTagVotes;
    protected $collTagVotesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // aggregate_column_relation_tag_count_aggregate behavior
    /**
     * @var ChildTopic
     */
    protected $oldTopicTagCount;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTagVote[]
     */
    protected $tagVotesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->vote_count = 0;
    }

    /**
     * Initializes internal state of Base\Tag object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Tag</code> instance.  If
     * <code>obj</code> is an instance of <code>Tag</code>, delegates to
     * <code>equals(Tag)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Tag The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [relevant_words] column value.
     *
     * @return string
     */
    public function getRelevantWords()
    {
        return $this->relevant_words;
    }

    /**
     * Get the [topic_id] column value.
     *
     * @return int
     */
    public function getTopicId()
    {
        return $this->topic_id;
    }

    /**
     * Get the [verse_id] column value.
     *
     * @return int
     */
    public function getVerseId()
    {
        return $this->verse_id;
    }

    /**
     * Get the [vote_count] column value.
     *
     * @return int
     */
    public function getVoteCount()
    {
        return $this->vote_count;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of [relevant_words] column.
     *
     * @param string $v new value
     * @return $this|\Tag The current object (for fluent API support)
     */
    public function setRelevantWords($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->relevant_words !== $v) {
            $this->relevant_words = $v;
            $this->modifiedColumns[TagTableMap::COL_RELEVANT_WORDS] = true;
        }

        return $this;
    } // setRelevantWords()

    /**
     * Set the value of [topic_id] column.
     *
     * @param int $v new value
     * @return $this|\Tag The current object (for fluent API support)
     */
    public function setTopicId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->topic_id !== $v) {
            $this->topic_id = $v;
            $this->modifiedColumns[TagTableMap::COL_TOPIC_ID] = true;
        }

        if ($this->aTopic !== null && $this->aTopic->getId() !== $v) {
            $this->aTopic = null;
        }

        return $this;
    } // setTopicId()

    /**
     * Set the value of [verse_id] column.
     *
     * @param int $v new value
     * @return $this|\Tag The current object (for fluent API support)
     */
    public function setVerseId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->verse_id !== $v) {
            $this->verse_id = $v;
            $this->modifiedColumns[TagTableMap::COL_VERSE_ID] = true;
        }

        if ($this->aVerse !== null && $this->aVerse->getId() !== $v) {
            $this->aVerse = null;
        }

        return $this;
    } // setVerseId()

    /**
     * Set the value of [vote_count] column.
     *
     * @param int $v new value
     * @return $this|\Tag The current object (for fluent API support)
     */
    public function setVoteCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->vote_count !== $v) {
            $this->vote_count = $v;
            $this->modifiedColumns[TagTableMap::COL_VOTE_COUNT] = true;
        }

        return $this;
    } // setVoteCount()

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Tag The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[TagTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->vote_count !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : TagTableMap::translateFieldName('RelevantWords', TableMap::TYPE_PHPNAME, $indexType)];
            $this->relevant_words = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : TagTableMap::translateFieldName('TopicId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->topic_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : TagTableMap::translateFieldName('VerseId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->verse_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : TagTableMap::translateFieldName('VoteCount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->vote_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : TagTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = TagTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Tag'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aTopic !== null && $this->topic_id !== $this->aTopic->getId()) {
            $this->aTopic = null;
        }
        if ($this->aVerse !== null && $this->verse_id !== $this->aVerse->getId()) {
            $this->aVerse = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TagTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildTagQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aTopic = null;
            $this->aVerse = null;
            $this->collTagVotes = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Tag::setDeleted()
     * @see Tag::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TagTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildTagQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TagTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                // aggregate_column_relation_tag_count_aggregate behavior
                $this->updateRelatedTopicTagCount($con);
                TagTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aTopic !== null) {
                if ($this->aTopic->isModified() || $this->aTopic->isNew()) {
                    $affectedRows += $this->aTopic->save($con);
                }
                $this->setTopic($this->aTopic);
            }

            if ($this->aVerse !== null) {
                if ($this->aVerse->isModified() || $this->aVerse->isNew()) {
                    $affectedRows += $this->aVerse->save($con);
                }
                $this->setVerse($this->aVerse);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->tagVotesScheduledForDeletion !== null) {
                if (!$this->tagVotesScheduledForDeletion->isEmpty()) {
                    \TagVoteQuery::create()
                        ->filterByPrimaryKeys($this->tagVotesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->tagVotesScheduledForDeletion = null;
                }
            }

            if ($this->collTagVotes !== null) {
                foreach ($this->collTagVotes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[TagTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TagTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TagTableMap::COL_RELEVANT_WORDS)) {
            $modifiedColumns[':p' . $index++]  = 'relevant_words';
        }
        if ($this->isColumnModified(TagTableMap::COL_TOPIC_ID)) {
            $modifiedColumns[':p' . $index++]  = 'topic_id';
        }
        if ($this->isColumnModified(TagTableMap::COL_VERSE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'verse_id';
        }
        if ($this->isColumnModified(TagTableMap::COL_VOTE_COUNT)) {
            $modifiedColumns[':p' . $index++]  = 'vote_count';
        }
        if ($this->isColumnModified(TagTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }

        $sql = sprintf(
            'INSERT INTO defender_tag (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'relevant_words':
                        $stmt->bindValue($identifier, $this->relevant_words, PDO::PARAM_STR);
                        break;
                    case 'topic_id':
                        $stmt->bindValue($identifier, $this->topic_id, PDO::PARAM_INT);
                        break;
                    case 'verse_id':
                        $stmt->bindValue($identifier, $this->verse_id, PDO::PARAM_INT);
                        break;
                    case 'vote_count':
                        $stmt->bindValue($identifier, $this->vote_count, PDO::PARAM_INT);
                        break;
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = TagTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getRelevantWords();
                break;
            case 1:
                return $this->getTopicId();
                break;
            case 2:
                return $this->getVerseId();
                break;
            case 3:
                return $this->getVoteCount();
                break;
            case 4:
                return $this->getId();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Tag'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Tag'][$this->hashCode()] = true;
        $keys = TagTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getRelevantWords(),
            $keys[1] => $this->getTopicId(),
            $keys[2] => $this->getVerseId(),
            $keys[3] => $this->getVoteCount(),
            $keys[4] => $this->getId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aTopic) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'topic';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'defender_topic';
                        break;
                    default:
                        $key = 'Topic';
                }

                $result[$key] = $this->aTopic->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aVerse) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'verse';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'defender_verse';
                        break;
                    default:
                        $key = 'Verse';
                }

                $result[$key] = $this->aVerse->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collTagVotes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'tagVotes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'defender_tag_votes';
                        break;
                    default:
                        $key = 'TagVotes';
                }

                $result[$key] = $this->collTagVotes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Tag
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = TagTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Tag
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setRelevantWords($value);
                break;
            case 1:
                $this->setTopicId($value);
                break;
            case 2:
                $this->setVerseId($value);
                break;
            case 3:
                $this->setVoteCount($value);
                break;
            case 4:
                $this->setId($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = TagTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setRelevantWords($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTopicId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setVerseId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setVoteCount($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setId($arr[$keys[4]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Tag The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(TagTableMap::DATABASE_NAME);

        if ($this->isColumnModified(TagTableMap::COL_RELEVANT_WORDS)) {
            $criteria->add(TagTableMap::COL_RELEVANT_WORDS, $this->relevant_words);
        }
        if ($this->isColumnModified(TagTableMap::COL_TOPIC_ID)) {
            $criteria->add(TagTableMap::COL_TOPIC_ID, $this->topic_id);
        }
        if ($this->isColumnModified(TagTableMap::COL_VERSE_ID)) {
            $criteria->add(TagTableMap::COL_VERSE_ID, $this->verse_id);
        }
        if ($this->isColumnModified(TagTableMap::COL_VOTE_COUNT)) {
            $criteria->add(TagTableMap::COL_VOTE_COUNT, $this->vote_count);
        }
        if ($this->isColumnModified(TagTableMap::COL_ID)) {
            $criteria->add(TagTableMap::COL_ID, $this->id);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildTagQuery::create();
        $criteria->add(TagTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Tag (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setRelevantWords($this->getRelevantWords());
        $copyObj->setTopicId($this->getTopicId());
        $copyObj->setVerseId($this->getVerseId());
        $copyObj->setVoteCount($this->getVoteCount());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getTagVotes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTagVote($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Tag Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildTopic object.
     *
     * @param  ChildTopic $v
     * @return $this|\Tag The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTopic(ChildTopic $v = null)
    {
        // aggregate_column_relation behavior
        if (null !== $this->aTopic && $v !== $this->aTopic) {
            $this->oldTopicTagCount = $this->aTopic;
        }
        if ($v === null) {
            $this->setTopicId(NULL);
        } else {
            $this->setTopicId($v->getId());
        }

        $this->aTopic = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildTopic object, it will not be re-added.
        if ($v !== null) {
            $v->addTag($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildTopic object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildTopic The associated ChildTopic object.
     * @throws PropelException
     */
    public function getTopic(ConnectionInterface $con = null)
    {
        if ($this->aTopic === null && ($this->topic_id !== null)) {
            $this->aTopic = ChildTopicQuery::create()->findPk($this->topic_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTopic->addTags($this);
             */
        }

        return $this->aTopic;
    }

    /**
     * Declares an association between this object and a ChildVerse object.
     *
     * @param  ChildVerse $v
     * @return $this|\Tag The current object (for fluent API support)
     * @throws PropelException
     */
    public function setVerse(ChildVerse $v = null)
    {
        if ($v === null) {
            $this->setVerseId(NULL);
        } else {
            $this->setVerseId($v->getId());
        }

        $this->aVerse = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildVerse object, it will not be re-added.
        if ($v !== null) {
            $v->addTag($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildVerse object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildVerse The associated ChildVerse object.
     * @throws PropelException
     */
    public function getVerse(ConnectionInterface $con = null)
    {
        if ($this->aVerse === null && ($this->verse_id !== null)) {
            $this->aVerse = ChildVerseQuery::create()->findPk($this->verse_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aVerse->addTags($this);
             */
        }

        return $this->aVerse;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('TagVote' == $relationName) {
            return $this->initTagVotes();
        }
    }

    /**
     * Clears out the collTagVotes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTagVotes()
     */
    public function clearTagVotes()
    {
        $this->collTagVotes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTagVotes collection loaded partially.
     */
    public function resetPartialTagVotes($v = true)
    {
        $this->collTagVotesPartial = $v;
    }

    /**
     * Initializes the collTagVotes collection.
     *
     * By default this just sets the collTagVotes collection to an empty array (like clearcollTagVotes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTagVotes($overrideExisting = true)
    {
        if (null !== $this->collTagVotes && !$overrideExisting) {
            return;
        }

        $collectionClassName = TagVoteTableMap::getTableMap()->getCollectionClassName();

        $this->collTagVotes = new $collectionClassName;
        $this->collTagVotes->setModel('\TagVote');
    }

    /**
     * Gets an array of ChildTagVote objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTagVote[] List of ChildTagVote objects
     * @throws PropelException
     */
    public function getTagVotes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTagVotesPartial && !$this->isNew();
        if (null === $this->collTagVotes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTagVotes) {
                // return empty collection
                $this->initTagVotes();
            } else {
                $collTagVotes = ChildTagVoteQuery::create(null, $criteria)
                    ->filterByTag($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTagVotesPartial && count($collTagVotes)) {
                        $this->initTagVotes(false);

                        foreach ($collTagVotes as $obj) {
                            if (false == $this->collTagVotes->contains($obj)) {
                                $this->collTagVotes->append($obj);
                            }
                        }

                        $this->collTagVotesPartial = true;
                    }

                    return $collTagVotes;
                }

                if ($partial && $this->collTagVotes) {
                    foreach ($this->collTagVotes as $obj) {
                        if ($obj->isNew()) {
                            $collTagVotes[] = $obj;
                        }
                    }
                }

                $this->collTagVotes = $collTagVotes;
                $this->collTagVotesPartial = false;
            }
        }

        return $this->collTagVotes;
    }

    /**
     * Sets a collection of ChildTagVote objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $tagVotes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTag The current object (for fluent API support)
     */
    public function setTagVotes(Collection $tagVotes, ConnectionInterface $con = null)
    {
        /** @var ChildTagVote[] $tagVotesToDelete */
        $tagVotesToDelete = $this->getTagVotes(new Criteria(), $con)->diff($tagVotes);


        $this->tagVotesScheduledForDeletion = $tagVotesToDelete;

        foreach ($tagVotesToDelete as $tagVoteRemoved) {
            $tagVoteRemoved->setTag(null);
        }

        $this->collTagVotes = null;
        foreach ($tagVotes as $tagVote) {
            $this->addTagVote($tagVote);
        }

        $this->collTagVotes = $tagVotes;
        $this->collTagVotesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related TagVote objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related TagVote objects.
     * @throws PropelException
     */
    public function countTagVotes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTagVotesPartial && !$this->isNew();
        if (null === $this->collTagVotes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTagVotes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTagVotes());
            }

            $query = ChildTagVoteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTag($this)
                ->count($con);
        }

        return count($this->collTagVotes);
    }

    /**
     * Method called to associate a ChildTagVote object to this object
     * through the ChildTagVote foreign key attribute.
     *
     * @param  ChildTagVote $l ChildTagVote
     * @return $this|\Tag The current object (for fluent API support)
     */
    public function addTagVote(ChildTagVote $l)
    {
        if ($this->collTagVotes === null) {
            $this->initTagVotes();
            $this->collTagVotesPartial = true;
        }

        if (!$this->collTagVotes->contains($l)) {
            $this->doAddTagVote($l);

            if ($this->tagVotesScheduledForDeletion and $this->tagVotesScheduledForDeletion->contains($l)) {
                $this->tagVotesScheduledForDeletion->remove($this->tagVotesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTagVote $tagVote The ChildTagVote object to add.
     */
    protected function doAddTagVote(ChildTagVote $tagVote)
    {
        $this->collTagVotes[]= $tagVote;
        $tagVote->setTag($this);
    }

    /**
     * @param  ChildTagVote $tagVote The ChildTagVote object to remove.
     * @return $this|ChildTag The current object (for fluent API support)
     */
    public function removeTagVote(ChildTagVote $tagVote)
    {
        if ($this->getTagVotes()->contains($tagVote)) {
            $pos = $this->collTagVotes->search($tagVote);
            $this->collTagVotes->remove($pos);
            if (null === $this->tagVotesScheduledForDeletion) {
                $this->tagVotesScheduledForDeletion = clone $this->collTagVotes;
                $this->tagVotesScheduledForDeletion->clear();
            }
            $this->tagVotesScheduledForDeletion[]= clone $tagVote;
            $tagVote->setTag(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aTopic) {
            $this->aTopic->removeTag($this);
        }
        if (null !== $this->aVerse) {
            $this->aVerse->removeTag($this);
        }
        $this->relevant_words = null;
        $this->topic_id = null;
        $this->verse_id = null;
        $this->vote_count = null;
        $this->id = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collTagVotes) {
                foreach ($this->collTagVotes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collTagVotes = null;
        $this->aTopic = null;
        $this->aVerse = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(TagTableMap::DEFAULT_STRING_FORMAT);
    }

    // vote_count_aggregate behavior

    /**
     * Computes the value of the aggregate column vote_count *
     * @param ConnectionInterface $con A connection object
     *
     * @return mixed The scalar result from the aggregate query
     */
    public function computeVoteCount(ConnectionInterface $con)
    {
        $stmt = $con->prepare('SELECT COUNT(tag_id) FROM defender_tag_vote WHERE defender_tag_vote.TAG_ID = :p1');
        $stmt->bindValue(':p1', $this->getId());
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Updates the aggregate column vote_count *
     * @param ConnectionInterface $con A connection object
     */
    public function updateVoteCount(ConnectionInterface $con)
    {
        $this->setVoteCount($this->computeVoteCount($con));
        $this->save($con);
    }

    // aggregate_column_relation_tag_count_aggregate behavior

    /**
     * Update the aggregate column in the related Topic object
     *
     * @param ConnectionInterface $con A connection object
     */
    protected function updateRelatedTopicTagCount(ConnectionInterface $con)
    {
        if ($topic = $this->getTopic()) {
            $topic->updateTagCount($con);
        }
        if ($this->oldTopicTagCount) {
            $this->oldTopicTagCount->updateTagCount($con);
            $this->oldTopicTagCount = null;
        }
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
