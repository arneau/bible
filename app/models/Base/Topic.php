<?php

namespace Base;

use \Tag as ChildTag;
use \TagQuery as ChildTagQuery;
use \Topic as ChildTopic;
use \TopicParent as ChildTopicParent;
use \TopicParentQuery as ChildTopicParentQuery;
use \TopicQuery as ChildTopicQuery;
use \TopicSynonym as ChildTopicSynonym;
use \TopicSynonymQuery as ChildTopicSynonymQuery;
use \Exception;
use \PDO;
use Map\TagTableMap;
use Map\TopicParentTableMap;
use Map\TopicSynonymTableMap;
use Map\TopicTableMap;
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
 * Base class that represents a row from the 'defender_topic' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Topic implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\TopicTableMap';


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
     * The value for the is_root field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_root;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the tag_count field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $tag_count;

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * @var        ObjectCollection|ChildTag[] Collection to store aggregation of ChildTag objects.
     */
    protected $collTags;
    protected $collTagsPartial;

    /**
     * @var        ObjectCollection|ChildTopicParent[] Collection to store aggregation of ChildTopicParent objects.
     */
    protected $collTopicParents;
    protected $collTopicParentsPartial;

    /**
     * @var        ObjectCollection|ChildTopicSynonym[] Collection to store aggregation of ChildTopicSynonym objects.
     */
    protected $collTopicSynonyms;
    protected $collTopicSynonymsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTag[]
     */
    protected $tagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTopicParent[]
     */
    protected $topicParentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTopicSynonym[]
     */
    protected $topicSynonymsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_root = false;
        $this->tag_count = 0;
    }

    /**
     * Initializes internal state of Base\Topic object.
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
     * Compares this with another <code>Topic</code> instance.  If
     * <code>obj</code> is an instance of <code>Topic</code>, delegates to
     * <code>equals(Topic)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Topic The current object, for fluid interface
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
     * Get the [is_root] column value.
     *
     * @return boolean
     */
    public function getIsRoot()
    {
        return $this->is_root;
    }

    /**
     * Get the [is_root] column value.
     *
     * @return boolean
     */
    public function isRoot()
    {
        return $this->getIsRoot();
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [tag_count] column value.
     *
     * @return int
     */
    public function getTagCount()
    {
        return $this->tag_count;
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
     * Sets the value of the [is_root] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Topic The current object (for fluent API support)
     */
    public function setIsRoot($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_root !== $v) {
            $this->is_root = $v;
            $this->modifiedColumns[TopicTableMap::COL_IS_ROOT] = true;
        }

        return $this;
    } // setIsRoot()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Topic The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[TopicTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [tag_count] column.
     *
     * @param int $v new value
     * @return $this|\Topic The current object (for fluent API support)
     */
    public function setTagCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->tag_count !== $v) {
            $this->tag_count = $v;
            $this->modifiedColumns[TopicTableMap::COL_TAG_COUNT] = true;
        }

        return $this;
    } // setTagCount()

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Topic The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[TopicTableMap::COL_ID] = true;
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
            if ($this->is_root !== false) {
                return false;
            }

            if ($this->tag_count !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : TopicTableMap::translateFieldName('IsRoot', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_root = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : TopicTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : TopicTableMap::translateFieldName('TagCount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tag_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : TopicTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = TopicTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Topic'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(TopicTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildTopicQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collTags = null;

            $this->collTopicParents = null;

            $this->collTopicSynonyms = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Topic::setDeleted()
     * @see Topic::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TopicTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildTopicQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(TopicTableMap::DATABASE_NAME);
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
                TopicTableMap::addInstanceToPool($this);
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

            if ($this->tagsScheduledForDeletion !== null) {
                if (!$this->tagsScheduledForDeletion->isEmpty()) {
                    \TagQuery::create()
                        ->filterByPrimaryKeys($this->tagsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->tagsScheduledForDeletion = null;
                }
            }

            if ($this->collTags !== null) {
                foreach ($this->collTags as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->topicParentsScheduledForDeletion !== null) {
                if (!$this->topicParentsScheduledForDeletion->isEmpty()) {
                    \TopicParentQuery::create()
                        ->filterByPrimaryKeys($this->topicParentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->topicParentsScheduledForDeletion = null;
                }
            }

            if ($this->collTopicParents !== null) {
                foreach ($this->collTopicParents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->topicSynonymsScheduledForDeletion !== null) {
                if (!$this->topicSynonymsScheduledForDeletion->isEmpty()) {
                    \TopicSynonymQuery::create()
                        ->filterByPrimaryKeys($this->topicSynonymsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->topicSynonymsScheduledForDeletion = null;
                }
            }

            if ($this->collTopicSynonyms !== null) {
                foreach ($this->collTopicSynonyms as $referrerFK) {
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

        $this->modifiedColumns[TopicTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TopicTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TopicTableMap::COL_IS_ROOT)) {
            $modifiedColumns[':p' . $index++]  = 'is_root';
        }
        if ($this->isColumnModified(TopicTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(TopicTableMap::COL_TAG_COUNT)) {
            $modifiedColumns[':p' . $index++]  = 'tag_count';
        }
        if ($this->isColumnModified(TopicTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }

        $sql = sprintf(
            'INSERT INTO defender_topic (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'is_root':
                        $stmt->bindValue($identifier, (int) $this->is_root, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'tag_count':
                        $stmt->bindValue($identifier, $this->tag_count, PDO::PARAM_INT);
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
        $pos = TopicTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getIsRoot();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getTagCount();
                break;
            case 3:
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

        if (isset($alreadyDumpedObjects['Topic'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Topic'][$this->hashCode()] = true;
        $keys = TopicTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getIsRoot(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getTagCount(),
            $keys[3] => $this->getId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collTags) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'tags';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'defender_tags';
                        break;
                    default:
                        $key = 'Tags';
                }

                $result[$key] = $this->collTags->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTopicParents) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'topicParents';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'defender_topic_parents';
                        break;
                    default:
                        $key = 'TopicParents';
                }

                $result[$key] = $this->collTopicParents->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTopicSynonyms) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'topicSynonyms';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'defender_topic_synonyms';
                        break;
                    default:
                        $key = 'TopicSynonyms';
                }

                $result[$key] = $this->collTopicSynonyms->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Topic
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = TopicTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Topic
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setIsRoot($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setTagCount($value);
                break;
            case 3:
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
        $keys = TopicTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setIsRoot($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTagCount($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setId($arr[$keys[3]]);
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
     * @return $this|\Topic The current object, for fluid interface
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
        $criteria = new Criteria(TopicTableMap::DATABASE_NAME);

        if ($this->isColumnModified(TopicTableMap::COL_IS_ROOT)) {
            $criteria->add(TopicTableMap::COL_IS_ROOT, $this->is_root);
        }
        if ($this->isColumnModified(TopicTableMap::COL_NAME)) {
            $criteria->add(TopicTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(TopicTableMap::COL_TAG_COUNT)) {
            $criteria->add(TopicTableMap::COL_TAG_COUNT, $this->tag_count);
        }
        if ($this->isColumnModified(TopicTableMap::COL_ID)) {
            $criteria->add(TopicTableMap::COL_ID, $this->id);
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
        $criteria = ChildTopicQuery::create();
        $criteria->add(TopicTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Topic (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setIsRoot($this->getIsRoot());
        $copyObj->setName($this->getName());
        $copyObj->setTagCount($this->getTagCount());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTag($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTopicParents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTopicParent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTopicSynonyms() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTopicSynonym($relObj->copy($deepCopy));
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
     * @return \Topic Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Tag' == $relationName) {
            return $this->initTags();
        }
        if ('TopicParent' == $relationName) {
            return $this->initTopicParents();
        }
        if ('TopicSynonym' == $relationName) {
            return $this->initTopicSynonyms();
        }
    }

    /**
     * Clears out the collTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTags()
     */
    public function clearTags()
    {
        $this->collTags = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTags collection loaded partially.
     */
    public function resetPartialTags($v = true)
    {
        $this->collTagsPartial = $v;
    }

    /**
     * Initializes the collTags collection.
     *
     * By default this just sets the collTags collection to an empty array (like clearcollTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTags($overrideExisting = true)
    {
        if (null !== $this->collTags && !$overrideExisting) {
            return;
        }

        $collectionClassName = TagTableMap::getTableMap()->getCollectionClassName();

        $this->collTags = new $collectionClassName;
        $this->collTags->setModel('\Tag');
    }

    /**
     * Gets an array of ChildTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTopic is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTag[] List of ChildTag objects
     * @throws PropelException
     */
    public function getTags(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTagsPartial && !$this->isNew();
        if (null === $this->collTags || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTags) {
                // return empty collection
                $this->initTags();
            } else {
                $collTags = ChildTagQuery::create(null, $criteria)
                    ->filterByTopic($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTagsPartial && count($collTags)) {
                        $this->initTags(false);

                        foreach ($collTags as $obj) {
                            if (false == $this->collTags->contains($obj)) {
                                $this->collTags->append($obj);
                            }
                        }

                        $this->collTagsPartial = true;
                    }

                    return $collTags;
                }

                if ($partial && $this->collTags) {
                    foreach ($this->collTags as $obj) {
                        if ($obj->isNew()) {
                            $collTags[] = $obj;
                        }
                    }
                }

                $this->collTags = $collTags;
                $this->collTagsPartial = false;
            }
        }

        return $this->collTags;
    }

    /**
     * Sets a collection of ChildTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $tags A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTopic The current object (for fluent API support)
     */
    public function setTags(Collection $tags, ConnectionInterface $con = null)
    {
        /** @var ChildTag[] $tagsToDelete */
        $tagsToDelete = $this->getTags(new Criteria(), $con)->diff($tags);


        $this->tagsScheduledForDeletion = $tagsToDelete;

        foreach ($tagsToDelete as $tagRemoved) {
            $tagRemoved->setTopic(null);
        }

        $this->collTags = null;
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        $this->collTags = $tags;
        $this->collTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Tag objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Tag objects.
     * @throws PropelException
     */
    public function countTags(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTagsPartial && !$this->isNew();
        if (null === $this->collTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTags) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTags());
            }

            $query = ChildTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTopic($this)
                ->count($con);
        }

        return count($this->collTags);
    }

    /**
     * Method called to associate a ChildTag object to this object
     * through the ChildTag foreign key attribute.
     *
     * @param  ChildTag $l ChildTag
     * @return $this|\Topic The current object (for fluent API support)
     */
    public function addTag(ChildTag $l)
    {
        if ($this->collTags === null) {
            $this->initTags();
            $this->collTagsPartial = true;
        }

        if (!$this->collTags->contains($l)) {
            $this->doAddTag($l);

            if ($this->tagsScheduledForDeletion and $this->tagsScheduledForDeletion->contains($l)) {
                $this->tagsScheduledForDeletion->remove($this->tagsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTag $tag The ChildTag object to add.
     */
    protected function doAddTag(ChildTag $tag)
    {
        $this->collTags[]= $tag;
        $tag->setTopic($this);
    }

    /**
     * @param  ChildTag $tag The ChildTag object to remove.
     * @return $this|ChildTopic The current object (for fluent API support)
     */
    public function removeTag(ChildTag $tag)
    {
        if ($this->getTags()->contains($tag)) {
            $pos = $this->collTags->search($tag);
            $this->collTags->remove($pos);
            if (null === $this->tagsScheduledForDeletion) {
                $this->tagsScheduledForDeletion = clone $this->collTags;
                $this->tagsScheduledForDeletion->clear();
            }
            $this->tagsScheduledForDeletion[]= clone $tag;
            $tag->setTopic(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Topic is new, it will return
     * an empty collection; or if this Topic has previously
     * been saved, it will retrieve related Tags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Topic.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildTag[] List of ChildTag objects
     */
    public function getTagsJoinVerse(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildTagQuery::create(null, $criteria);
        $query->joinWith('Verse', $joinBehavior);

        return $this->getTags($query, $con);
    }

    /**
     * Clears out the collTopicParents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTopicParents()
     */
    public function clearTopicParents()
    {
        $this->collTopicParents = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTopicParents collection loaded partially.
     */
    public function resetPartialTopicParents($v = true)
    {
        $this->collTopicParentsPartial = $v;
    }

    /**
     * Initializes the collTopicParents collection.
     *
     * By default this just sets the collTopicParents collection to an empty array (like clearcollTopicParents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTopicParents($overrideExisting = true)
    {
        if (null !== $this->collTopicParents && !$overrideExisting) {
            return;
        }

        $collectionClassName = TopicParentTableMap::getTableMap()->getCollectionClassName();

        $this->collTopicParents = new $collectionClassName;
        $this->collTopicParents->setModel('\TopicParent');
    }

    /**
     * Gets an array of ChildTopicParent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTopic is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTopicParent[] List of ChildTopicParent objects
     * @throws PropelException
     */
    public function getTopicParents(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTopicParentsPartial && !$this->isNew();
        if (null === $this->collTopicParents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTopicParents) {
                // return empty collection
                $this->initTopicParents();
            } else {
                $collTopicParents = ChildTopicParentQuery::create(null, $criteria)
                    ->filterByTopic($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTopicParentsPartial && count($collTopicParents)) {
                        $this->initTopicParents(false);

                        foreach ($collTopicParents as $obj) {
                            if (false == $this->collTopicParents->contains($obj)) {
                                $this->collTopicParents->append($obj);
                            }
                        }

                        $this->collTopicParentsPartial = true;
                    }

                    return $collTopicParents;
                }

                if ($partial && $this->collTopicParents) {
                    foreach ($this->collTopicParents as $obj) {
                        if ($obj->isNew()) {
                            $collTopicParents[] = $obj;
                        }
                    }
                }

                $this->collTopicParents = $collTopicParents;
                $this->collTopicParentsPartial = false;
            }
        }

        return $this->collTopicParents;
    }

    /**
     * Sets a collection of ChildTopicParent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $topicParents A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTopic The current object (for fluent API support)
     */
    public function setTopicParents(Collection $topicParents, ConnectionInterface $con = null)
    {
        /** @var ChildTopicParent[] $topicParentsToDelete */
        $topicParentsToDelete = $this->getTopicParents(new Criteria(), $con)->diff($topicParents);


        $this->topicParentsScheduledForDeletion = $topicParentsToDelete;

        foreach ($topicParentsToDelete as $topicParentRemoved) {
            $topicParentRemoved->setTopic(null);
        }

        $this->collTopicParents = null;
        foreach ($topicParents as $topicParent) {
            $this->addTopicParent($topicParent);
        }

        $this->collTopicParents = $topicParents;
        $this->collTopicParentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related TopicParent objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related TopicParent objects.
     * @throws PropelException
     */
    public function countTopicParents(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTopicParentsPartial && !$this->isNew();
        if (null === $this->collTopicParents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTopicParents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTopicParents());
            }

            $query = ChildTopicParentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTopic($this)
                ->count($con);
        }

        return count($this->collTopicParents);
    }

    /**
     * Method called to associate a ChildTopicParent object to this object
     * through the ChildTopicParent foreign key attribute.
     *
     * @param  ChildTopicParent $l ChildTopicParent
     * @return $this|\Topic The current object (for fluent API support)
     */
    public function addTopicParent(ChildTopicParent $l)
    {
        if ($this->collTopicParents === null) {
            $this->initTopicParents();
            $this->collTopicParentsPartial = true;
        }

        if (!$this->collTopicParents->contains($l)) {
            $this->doAddTopicParent($l);

            if ($this->topicParentsScheduledForDeletion and $this->topicParentsScheduledForDeletion->contains($l)) {
                $this->topicParentsScheduledForDeletion->remove($this->topicParentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTopicParent $topicParent The ChildTopicParent object to add.
     */
    protected function doAddTopicParent(ChildTopicParent $topicParent)
    {
        $this->collTopicParents[]= $topicParent;
        $topicParent->setTopic($this);
    }

    /**
     * @param  ChildTopicParent $topicParent The ChildTopicParent object to remove.
     * @return $this|ChildTopic The current object (for fluent API support)
     */
    public function removeTopicParent(ChildTopicParent $topicParent)
    {
        if ($this->getTopicParents()->contains($topicParent)) {
            $pos = $this->collTopicParents->search($topicParent);
            $this->collTopicParents->remove($pos);
            if (null === $this->topicParentsScheduledForDeletion) {
                $this->topicParentsScheduledForDeletion = clone $this->collTopicParents;
                $this->topicParentsScheduledForDeletion->clear();
            }
            $this->topicParentsScheduledForDeletion[]= clone $topicParent;
            $topicParent->setTopic(null);
        }

        return $this;
    }

    /**
     * Clears out the collTopicSynonyms collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTopicSynonyms()
     */
    public function clearTopicSynonyms()
    {
        $this->collTopicSynonyms = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTopicSynonyms collection loaded partially.
     */
    public function resetPartialTopicSynonyms($v = true)
    {
        $this->collTopicSynonymsPartial = $v;
    }

    /**
     * Initializes the collTopicSynonyms collection.
     *
     * By default this just sets the collTopicSynonyms collection to an empty array (like clearcollTopicSynonyms());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTopicSynonyms($overrideExisting = true)
    {
        if (null !== $this->collTopicSynonyms && !$overrideExisting) {
            return;
        }

        $collectionClassName = TopicSynonymTableMap::getTableMap()->getCollectionClassName();

        $this->collTopicSynonyms = new $collectionClassName;
        $this->collTopicSynonyms->setModel('\TopicSynonym');
    }

    /**
     * Gets an array of ChildTopicSynonym objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTopic is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTopicSynonym[] List of ChildTopicSynonym objects
     * @throws PropelException
     */
    public function getTopicSynonyms(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTopicSynonymsPartial && !$this->isNew();
        if (null === $this->collTopicSynonyms || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTopicSynonyms) {
                // return empty collection
                $this->initTopicSynonyms();
            } else {
                $collTopicSynonyms = ChildTopicSynonymQuery::create(null, $criteria)
                    ->filterByTopic($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTopicSynonymsPartial && count($collTopicSynonyms)) {
                        $this->initTopicSynonyms(false);

                        foreach ($collTopicSynonyms as $obj) {
                            if (false == $this->collTopicSynonyms->contains($obj)) {
                                $this->collTopicSynonyms->append($obj);
                            }
                        }

                        $this->collTopicSynonymsPartial = true;
                    }

                    return $collTopicSynonyms;
                }

                if ($partial && $this->collTopicSynonyms) {
                    foreach ($this->collTopicSynonyms as $obj) {
                        if ($obj->isNew()) {
                            $collTopicSynonyms[] = $obj;
                        }
                    }
                }

                $this->collTopicSynonyms = $collTopicSynonyms;
                $this->collTopicSynonymsPartial = false;
            }
        }

        return $this->collTopicSynonyms;
    }

    /**
     * Sets a collection of ChildTopicSynonym objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $topicSynonyms A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTopic The current object (for fluent API support)
     */
    public function setTopicSynonyms(Collection $topicSynonyms, ConnectionInterface $con = null)
    {
        /** @var ChildTopicSynonym[] $topicSynonymsToDelete */
        $topicSynonymsToDelete = $this->getTopicSynonyms(new Criteria(), $con)->diff($topicSynonyms);


        $this->topicSynonymsScheduledForDeletion = $topicSynonymsToDelete;

        foreach ($topicSynonymsToDelete as $topicSynonymRemoved) {
            $topicSynonymRemoved->setTopic(null);
        }

        $this->collTopicSynonyms = null;
        foreach ($topicSynonyms as $topicSynonym) {
            $this->addTopicSynonym($topicSynonym);
        }

        $this->collTopicSynonyms = $topicSynonyms;
        $this->collTopicSynonymsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related TopicSynonym objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related TopicSynonym objects.
     * @throws PropelException
     */
    public function countTopicSynonyms(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTopicSynonymsPartial && !$this->isNew();
        if (null === $this->collTopicSynonyms || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTopicSynonyms) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTopicSynonyms());
            }

            $query = ChildTopicSynonymQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTopic($this)
                ->count($con);
        }

        return count($this->collTopicSynonyms);
    }

    /**
     * Method called to associate a ChildTopicSynonym object to this object
     * through the ChildTopicSynonym foreign key attribute.
     *
     * @param  ChildTopicSynonym $l ChildTopicSynonym
     * @return $this|\Topic The current object (for fluent API support)
     */
    public function addTopicSynonym(ChildTopicSynonym $l)
    {
        if ($this->collTopicSynonyms === null) {
            $this->initTopicSynonyms();
            $this->collTopicSynonymsPartial = true;
        }

        if (!$this->collTopicSynonyms->contains($l)) {
            $this->doAddTopicSynonym($l);

            if ($this->topicSynonymsScheduledForDeletion and $this->topicSynonymsScheduledForDeletion->contains($l)) {
                $this->topicSynonymsScheduledForDeletion->remove($this->topicSynonymsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTopicSynonym $topicSynonym The ChildTopicSynonym object to add.
     */
    protected function doAddTopicSynonym(ChildTopicSynonym $topicSynonym)
    {
        $this->collTopicSynonyms[]= $topicSynonym;
        $topicSynonym->setTopic($this);
    }

    /**
     * @param  ChildTopicSynonym $topicSynonym The ChildTopicSynonym object to remove.
     * @return $this|ChildTopic The current object (for fluent API support)
     */
    public function removeTopicSynonym(ChildTopicSynonym $topicSynonym)
    {
        if ($this->getTopicSynonyms()->contains($topicSynonym)) {
            $pos = $this->collTopicSynonyms->search($topicSynonym);
            $this->collTopicSynonyms->remove($pos);
            if (null === $this->topicSynonymsScheduledForDeletion) {
                $this->topicSynonymsScheduledForDeletion = clone $this->collTopicSynonyms;
                $this->topicSynonymsScheduledForDeletion->clear();
            }
            $this->topicSynonymsScheduledForDeletion[]= clone $topicSynonym;
            $topicSynonym->setTopic(null);
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
        $this->is_root = null;
        $this->name = null;
        $this->tag_count = null;
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
            if ($this->collTags) {
                foreach ($this->collTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTopicParents) {
                foreach ($this->collTopicParents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTopicSynonyms) {
                foreach ($this->collTopicSynonyms as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collTags = null;
        $this->collTopicParents = null;
        $this->collTopicSynonyms = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(TopicTableMap::DEFAULT_STRING_FORMAT);
    }

    // tag_count_aggregate behavior

    /**
     * Computes the value of the aggregate column tag_count *
     * @param ConnectionInterface $con A connection object
     *
     * @return mixed The scalar result from the aggregate query
     */
    public function computeTagCount(ConnectionInterface $con)
    {
        $stmt = $con->prepare('SELECT COUNT(topic_id) FROM defender_tag WHERE defender_tag.TOPIC_ID = :p1');
        $stmt->bindValue(':p1', $this->getId());
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Updates the aggregate column tag_count *
     * @param ConnectionInterface $con A connection object
     */
    public function updateTagCount(ConnectionInterface $con)
    {
        $this->setTagCount($this->computeTagCount($con));
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
