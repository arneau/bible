<?php

namespace Base;

use \LessonTag as ChildLessonTag;
use \LessonTagQuery as ChildLessonTagQuery;
use \Tag as ChildTag;
use \TagQuery as ChildTagQuery;
use \TagTranslation as ChildTagTranslation;
use \TagTranslationQuery as ChildTagTranslationQuery;
use \TagVote as ChildTagVote;
use \TagVoteQuery as ChildTagVoteQuery;
use \TopicTag as ChildTopicTag;
use \TopicTagQuery as ChildTopicTagQuery;
use \Verse as ChildVerse;
use \VerseQuery as ChildVerseQuery;
use \Exception;
use \PDO;
use Map\LessonTagTableMap;
use Map\TagTableMap;
use Map\TagTranslationTableMap;
use Map\TagVoteTableMap;
use Map\TopicTagTableMap;
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
     * @var        ChildVerse
     */
    protected $aVerse;

    /**
     * @var        ObjectCollection|ChildTagTranslation[] Collection to store aggregation of ChildTagTranslation objects.
     */
    protected $collTagTranslations;
    protected $collTagTranslationsPartial;

    /**
     * @var        ObjectCollection|ChildTagVote[] Collection to store aggregation of ChildTagVote objects.
     */
    protected $collTagVotes;
    protected $collTagVotesPartial;

    /**
     * @var        ObjectCollection|ChildTopicTag[] Collection to store aggregation of ChildTopicTag objects.
     */
    protected $collTopicTags;
    protected $collTopicTagsPartial;

    /**
     * @var        ObjectCollection|ChildLessonTag[] Collection to store aggregation of ChildLessonTag objects.
     */
    protected $collLessonTags;
    protected $collLessonTagsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTagTranslation[]
     */
    protected $tagTranslationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTagVote[]
     */
    protected $tagVotesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTopicTag[]
     */
    protected $topicTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLessonTag[]
     */
    protected $lessonTagsScheduledForDeletion = null;

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : TagTableMap::translateFieldName('VerseId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->verse_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : TagTableMap::translateFieldName('VoteCount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->vote_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : TagTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = TagTableMap::NUM_HYDRATE_COLUMNS.

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

            $this->aVerse = null;
            $this->collTagTranslations = null;

            $this->collTagVotes = null;

            $this->collTopicTags = null;

            $this->collLessonTags = null;

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

            if ($this->tagTranslationsScheduledForDeletion !== null) {
                if (!$this->tagTranslationsScheduledForDeletion->isEmpty()) {
                    \TagTranslationQuery::create()
                        ->filterByPrimaryKeys($this->tagTranslationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->tagTranslationsScheduledForDeletion = null;
                }
            }

            if ($this->collTagTranslations !== null) {
                foreach ($this->collTagTranslations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

            if ($this->topicTagsScheduledForDeletion !== null) {
                if (!$this->topicTagsScheduledForDeletion->isEmpty()) {
                    \TopicTagQuery::create()
                        ->filterByPrimaryKeys($this->topicTagsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->topicTagsScheduledForDeletion = null;
                }
            }

            if ($this->collTopicTags !== null) {
                foreach ($this->collTopicTags as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->lessonTagsScheduledForDeletion !== null) {
                if (!$this->lessonTagsScheduledForDeletion->isEmpty()) {
                    \LessonTagQuery::create()
                        ->filterByPrimaryKeys($this->lessonTagsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->lessonTagsScheduledForDeletion = null;
                }
            }

            if ($this->collLessonTags !== null) {
                foreach ($this->collLessonTags as $referrerFK) {
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
                return $this->getVerseId();
                break;
            case 1:
                return $this->getVoteCount();
                break;
            case 2:
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
            $keys[0] => $this->getVerseId(),
            $keys[1] => $this->getVoteCount(),
            $keys[2] => $this->getId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
            if (null !== $this->collTagTranslations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'tagTranslations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'defender_tag_translations';
                        break;
                    default:
                        $key = 'TagTranslations';
                }

                $result[$key] = $this->collTagTranslations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collTopicTags) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'topicTags';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'defender_topic_tags';
                        break;
                    default:
                        $key = 'TopicTags';
                }

                $result[$key] = $this->collTopicTags->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLessonTags) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'lessonTags';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'defender_lesson_tags';
                        break;
                    default:
                        $key = 'LessonTags';
                }

                $result[$key] = $this->collLessonTags->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setVerseId($value);
                break;
            case 1:
                $this->setVoteCount($value);
                break;
            case 2:
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
            $this->setVerseId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setVoteCount($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setId($arr[$keys[2]]);
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
        $copyObj->setVerseId($this->getVerseId());
        $copyObj->setVoteCount($this->getVoteCount());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getTagTranslations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTagTranslation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTagVotes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTagVote($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTopicTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTopicTag($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLessonTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLessonTag($relObj->copy($deepCopy));
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
        if ('TagTranslation' == $relationName) {
            return $this->initTagTranslations();
        }
        if ('TagVote' == $relationName) {
            return $this->initTagVotes();
        }
        if ('TopicTag' == $relationName) {
            return $this->initTopicTags();
        }
        if ('LessonTag' == $relationName) {
            return $this->initLessonTags();
        }
    }

    /**
     * Clears out the collTagTranslations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTagTranslations()
     */
    public function clearTagTranslations()
    {
        $this->collTagTranslations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTagTranslations collection loaded partially.
     */
    public function resetPartialTagTranslations($v = true)
    {
        $this->collTagTranslationsPartial = $v;
    }

    /**
     * Initializes the collTagTranslations collection.
     *
     * By default this just sets the collTagTranslations collection to an empty array (like clearcollTagTranslations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTagTranslations($overrideExisting = true)
    {
        if (null !== $this->collTagTranslations && !$overrideExisting) {
            return;
        }

        $collectionClassName = TagTranslationTableMap::getTableMap()->getCollectionClassName();

        $this->collTagTranslations = new $collectionClassName;
        $this->collTagTranslations->setModel('\TagTranslation');
    }

    /**
     * Gets an array of ChildTagTranslation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTagTranslation[] List of ChildTagTranslation objects
     * @throws PropelException
     */
    public function getTagTranslations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTagTranslationsPartial && !$this->isNew();
        if (null === $this->collTagTranslations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTagTranslations) {
                // return empty collection
                $this->initTagTranslations();
            } else {
                $collTagTranslations = ChildTagTranslationQuery::create(null, $criteria)
                    ->filterByTag($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTagTranslationsPartial && count($collTagTranslations)) {
                        $this->initTagTranslations(false);

                        foreach ($collTagTranslations as $obj) {
                            if (false == $this->collTagTranslations->contains($obj)) {
                                $this->collTagTranslations->append($obj);
                            }
                        }

                        $this->collTagTranslationsPartial = true;
                    }

                    return $collTagTranslations;
                }

                if ($partial && $this->collTagTranslations) {
                    foreach ($this->collTagTranslations as $obj) {
                        if ($obj->isNew()) {
                            $collTagTranslations[] = $obj;
                        }
                    }
                }

                $this->collTagTranslations = $collTagTranslations;
                $this->collTagTranslationsPartial = false;
            }
        }

        return $this->collTagTranslations;
    }

    /**
     * Sets a collection of ChildTagTranslation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $tagTranslations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTag The current object (for fluent API support)
     */
    public function setTagTranslations(Collection $tagTranslations, ConnectionInterface $con = null)
    {
        /** @var ChildTagTranslation[] $tagTranslationsToDelete */
        $tagTranslationsToDelete = $this->getTagTranslations(new Criteria(), $con)->diff($tagTranslations);


        $this->tagTranslationsScheduledForDeletion = $tagTranslationsToDelete;

        foreach ($tagTranslationsToDelete as $tagTranslationRemoved) {
            $tagTranslationRemoved->setTag(null);
        }

        $this->collTagTranslations = null;
        foreach ($tagTranslations as $tagTranslation) {
            $this->addTagTranslation($tagTranslation);
        }

        $this->collTagTranslations = $tagTranslations;
        $this->collTagTranslationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related TagTranslation objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related TagTranslation objects.
     * @throws PropelException
     */
    public function countTagTranslations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTagTranslationsPartial && !$this->isNew();
        if (null === $this->collTagTranslations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTagTranslations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTagTranslations());
            }

            $query = ChildTagTranslationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTag($this)
                ->count($con);
        }

        return count($this->collTagTranslations);
    }

    /**
     * Method called to associate a ChildTagTranslation object to this object
     * through the ChildTagTranslation foreign key attribute.
     *
     * @param  ChildTagTranslation $l ChildTagTranslation
     * @return $this|\Tag The current object (for fluent API support)
     */
    public function addTagTranslation(ChildTagTranslation $l)
    {
        if ($this->collTagTranslations === null) {
            $this->initTagTranslations();
            $this->collTagTranslationsPartial = true;
        }

        if (!$this->collTagTranslations->contains($l)) {
            $this->doAddTagTranslation($l);

            if ($this->tagTranslationsScheduledForDeletion and $this->tagTranslationsScheduledForDeletion->contains($l)) {
                $this->tagTranslationsScheduledForDeletion->remove($this->tagTranslationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTagTranslation $tagTranslation The ChildTagTranslation object to add.
     */
    protected function doAddTagTranslation(ChildTagTranslation $tagTranslation)
    {
        $this->collTagTranslations[]= $tagTranslation;
        $tagTranslation->setTag($this);
    }

    /**
     * @param  ChildTagTranslation $tagTranslation The ChildTagTranslation object to remove.
     * @return $this|ChildTag The current object (for fluent API support)
     */
    public function removeTagTranslation(ChildTagTranslation $tagTranslation)
    {
        if ($this->getTagTranslations()->contains($tagTranslation)) {
            $pos = $this->collTagTranslations->search($tagTranslation);
            $this->collTagTranslations->remove($pos);
            if (null === $this->tagTranslationsScheduledForDeletion) {
                $this->tagTranslationsScheduledForDeletion = clone $this->collTagTranslations;
                $this->tagTranslationsScheduledForDeletion->clear();
            }
            $this->tagTranslationsScheduledForDeletion[]= clone $tagTranslation;
            $tagTranslation->setTag(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Tag is new, it will return
     * an empty collection; or if this Tag has previously
     * been saved, it will retrieve related TagTranslations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Tag.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildTagTranslation[] List of ChildTagTranslation objects
     */
    public function getTagTranslationsJoinBible(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildTagTranslationQuery::create(null, $criteria);
        $query->joinWith('Bible', $joinBehavior);

        return $this->getTagTranslations($query, $con);
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
     * Clears out the collTopicTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTopicTags()
     */
    public function clearTopicTags()
    {
        $this->collTopicTags = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTopicTags collection loaded partially.
     */
    public function resetPartialTopicTags($v = true)
    {
        $this->collTopicTagsPartial = $v;
    }

    /**
     * Initializes the collTopicTags collection.
     *
     * By default this just sets the collTopicTags collection to an empty array (like clearcollTopicTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTopicTags($overrideExisting = true)
    {
        if (null !== $this->collTopicTags && !$overrideExisting) {
            return;
        }

        $collectionClassName = TopicTagTableMap::getTableMap()->getCollectionClassName();

        $this->collTopicTags = new $collectionClassName;
        $this->collTopicTags->setModel('\TopicTag');
    }

    /**
     * Gets an array of ChildTopicTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTopicTag[] List of ChildTopicTag objects
     * @throws PropelException
     */
    public function getTopicTags(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTopicTagsPartial && !$this->isNew();
        if (null === $this->collTopicTags || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTopicTags) {
                // return empty collection
                $this->initTopicTags();
            } else {
                $collTopicTags = ChildTopicTagQuery::create(null, $criteria)
                    ->filterByTag($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTopicTagsPartial && count($collTopicTags)) {
                        $this->initTopicTags(false);

                        foreach ($collTopicTags as $obj) {
                            if (false == $this->collTopicTags->contains($obj)) {
                                $this->collTopicTags->append($obj);
                            }
                        }

                        $this->collTopicTagsPartial = true;
                    }

                    return $collTopicTags;
                }

                if ($partial && $this->collTopicTags) {
                    foreach ($this->collTopicTags as $obj) {
                        if ($obj->isNew()) {
                            $collTopicTags[] = $obj;
                        }
                    }
                }

                $this->collTopicTags = $collTopicTags;
                $this->collTopicTagsPartial = false;
            }
        }

        return $this->collTopicTags;
    }

    /**
     * Sets a collection of ChildTopicTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $topicTags A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTag The current object (for fluent API support)
     */
    public function setTopicTags(Collection $topicTags, ConnectionInterface $con = null)
    {
        /** @var ChildTopicTag[] $topicTagsToDelete */
        $topicTagsToDelete = $this->getTopicTags(new Criteria(), $con)->diff($topicTags);


        $this->topicTagsScheduledForDeletion = $topicTagsToDelete;

        foreach ($topicTagsToDelete as $topicTagRemoved) {
            $topicTagRemoved->setTag(null);
        }

        $this->collTopicTags = null;
        foreach ($topicTags as $topicTag) {
            $this->addTopicTag($topicTag);
        }

        $this->collTopicTags = $topicTags;
        $this->collTopicTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related TopicTag objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related TopicTag objects.
     * @throws PropelException
     */
    public function countTopicTags(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTopicTagsPartial && !$this->isNew();
        if (null === $this->collTopicTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTopicTags) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTopicTags());
            }

            $query = ChildTopicTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTag($this)
                ->count($con);
        }

        return count($this->collTopicTags);
    }

    /**
     * Method called to associate a ChildTopicTag object to this object
     * through the ChildTopicTag foreign key attribute.
     *
     * @param  ChildTopicTag $l ChildTopicTag
     * @return $this|\Tag The current object (for fluent API support)
     */
    public function addTopicTag(ChildTopicTag $l)
    {
        if ($this->collTopicTags === null) {
            $this->initTopicTags();
            $this->collTopicTagsPartial = true;
        }

        if (!$this->collTopicTags->contains($l)) {
            $this->doAddTopicTag($l);

            if ($this->topicTagsScheduledForDeletion and $this->topicTagsScheduledForDeletion->contains($l)) {
                $this->topicTagsScheduledForDeletion->remove($this->topicTagsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTopicTag $topicTag The ChildTopicTag object to add.
     */
    protected function doAddTopicTag(ChildTopicTag $topicTag)
    {
        $this->collTopicTags[]= $topicTag;
        $topicTag->setTag($this);
    }

    /**
     * @param  ChildTopicTag $topicTag The ChildTopicTag object to remove.
     * @return $this|ChildTag The current object (for fluent API support)
     */
    public function removeTopicTag(ChildTopicTag $topicTag)
    {
        if ($this->getTopicTags()->contains($topicTag)) {
            $pos = $this->collTopicTags->search($topicTag);
            $this->collTopicTags->remove($pos);
            if (null === $this->topicTagsScheduledForDeletion) {
                $this->topicTagsScheduledForDeletion = clone $this->collTopicTags;
                $this->topicTagsScheduledForDeletion->clear();
            }
            $this->topicTagsScheduledForDeletion[]= clone $topicTag;
            $topicTag->setTag(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Tag is new, it will return
     * an empty collection; or if this Tag has previously
     * been saved, it will retrieve related TopicTags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Tag.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildTopicTag[] List of ChildTopicTag objects
     */
    public function getTopicTagsJoinTopic(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildTopicTagQuery::create(null, $criteria);
        $query->joinWith('Topic', $joinBehavior);

        return $this->getTopicTags($query, $con);
    }

    /**
     * Clears out the collLessonTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLessonTags()
     */
    public function clearLessonTags()
    {
        $this->collLessonTags = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLessonTags collection loaded partially.
     */
    public function resetPartialLessonTags($v = true)
    {
        $this->collLessonTagsPartial = $v;
    }

    /**
     * Initializes the collLessonTags collection.
     *
     * By default this just sets the collLessonTags collection to an empty array (like clearcollLessonTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLessonTags($overrideExisting = true)
    {
        if (null !== $this->collLessonTags && !$overrideExisting) {
            return;
        }

        $collectionClassName = LessonTagTableMap::getTableMap()->getCollectionClassName();

        $this->collLessonTags = new $collectionClassName;
        $this->collLessonTags->setModel('\LessonTag');
    }

    /**
     * Gets an array of ChildLessonTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLessonTag[] List of ChildLessonTag objects
     * @throws PropelException
     */
    public function getLessonTags(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLessonTagsPartial && !$this->isNew();
        if (null === $this->collLessonTags || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLessonTags) {
                // return empty collection
                $this->initLessonTags();
            } else {
                $collLessonTags = ChildLessonTagQuery::create(null, $criteria)
                    ->filterByTag($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLessonTagsPartial && count($collLessonTags)) {
                        $this->initLessonTags(false);

                        foreach ($collLessonTags as $obj) {
                            if (false == $this->collLessonTags->contains($obj)) {
                                $this->collLessonTags->append($obj);
                            }
                        }

                        $this->collLessonTagsPartial = true;
                    }

                    return $collLessonTags;
                }

                if ($partial && $this->collLessonTags) {
                    foreach ($this->collLessonTags as $obj) {
                        if ($obj->isNew()) {
                            $collLessonTags[] = $obj;
                        }
                    }
                }

                $this->collLessonTags = $collLessonTags;
                $this->collLessonTagsPartial = false;
            }
        }

        return $this->collLessonTags;
    }

    /**
     * Sets a collection of ChildLessonTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $lessonTags A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTag The current object (for fluent API support)
     */
    public function setLessonTags(Collection $lessonTags, ConnectionInterface $con = null)
    {
        /** @var ChildLessonTag[] $lessonTagsToDelete */
        $lessonTagsToDelete = $this->getLessonTags(new Criteria(), $con)->diff($lessonTags);


        $this->lessonTagsScheduledForDeletion = $lessonTagsToDelete;

        foreach ($lessonTagsToDelete as $lessonTagRemoved) {
            $lessonTagRemoved->setTag(null);
        }

        $this->collLessonTags = null;
        foreach ($lessonTags as $lessonTag) {
            $this->addLessonTag($lessonTag);
        }

        $this->collLessonTags = $lessonTags;
        $this->collLessonTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related LessonTag objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related LessonTag objects.
     * @throws PropelException
     */
    public function countLessonTags(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLessonTagsPartial && !$this->isNew();
        if (null === $this->collLessonTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLessonTags) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLessonTags());
            }

            $query = ChildLessonTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTag($this)
                ->count($con);
        }

        return count($this->collLessonTags);
    }

    /**
     * Method called to associate a ChildLessonTag object to this object
     * through the ChildLessonTag foreign key attribute.
     *
     * @param  ChildLessonTag $l ChildLessonTag
     * @return $this|\Tag The current object (for fluent API support)
     */
    public function addLessonTag(ChildLessonTag $l)
    {
        if ($this->collLessonTags === null) {
            $this->initLessonTags();
            $this->collLessonTagsPartial = true;
        }

        if (!$this->collLessonTags->contains($l)) {
            $this->doAddLessonTag($l);

            if ($this->lessonTagsScheduledForDeletion and $this->lessonTagsScheduledForDeletion->contains($l)) {
                $this->lessonTagsScheduledForDeletion->remove($this->lessonTagsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLessonTag $lessonTag The ChildLessonTag object to add.
     */
    protected function doAddLessonTag(ChildLessonTag $lessonTag)
    {
        $this->collLessonTags[]= $lessonTag;
        $lessonTag->setTag($this);
    }

    /**
     * @param  ChildLessonTag $lessonTag The ChildLessonTag object to remove.
     * @return $this|ChildTag The current object (for fluent API support)
     */
    public function removeLessonTag(ChildLessonTag $lessonTag)
    {
        if ($this->getLessonTags()->contains($lessonTag)) {
            $pos = $this->collLessonTags->search($lessonTag);
            $this->collLessonTags->remove($pos);
            if (null === $this->lessonTagsScheduledForDeletion) {
                $this->lessonTagsScheduledForDeletion = clone $this->collLessonTags;
                $this->lessonTagsScheduledForDeletion->clear();
            }
            $this->lessonTagsScheduledForDeletion[]= clone $lessonTag;
            $lessonTag->setTag(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Tag is new, it will return
     * an empty collection; or if this Tag has previously
     * been saved, it will retrieve related LessonTags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Tag.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLessonTag[] List of ChildLessonTag objects
     */
    public function getLessonTagsJoinLesson(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLessonTagQuery::create(null, $criteria);
        $query->joinWith('Lesson', $joinBehavior);

        return $this->getLessonTags($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aVerse) {
            $this->aVerse->removeTag($this);
        }
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
            if ($this->collTagTranslations) {
                foreach ($this->collTagTranslations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTagVotes) {
                foreach ($this->collTagVotes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTopicTags) {
                foreach ($this->collTopicTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLessonTags) {
                foreach ($this->collLessonTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collTagTranslations = null;
        $this->collTagVotes = null;
        $this->collTopicTags = null;
        $this->collLessonTags = null;
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

    // aggregate_column behavior

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
