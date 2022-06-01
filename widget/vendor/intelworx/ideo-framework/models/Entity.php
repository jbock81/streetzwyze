<?php

/**
 * Description of Entity
 *
 * @author intelWorX
 */
abstract class Entity extends DbTableRow
{

    /**
     *
     * @var array this holds the fields to return when toArray() is called for a particular
     * entity, if fields are not found for the entity, all fields and those matching the $flag for
     * toArray() will be returned.
     * The array is mapped as EntityClass => fields[]
     *
     */
    protected static $toArrayFields = array();
    protected $mvaOrders = array();
    protected $mvaLimitDefault = 512;
    protected $mvaLimits = array();

    const TO_ARRAY_MVA = 0x1;
    const TO_ARRAY_LAZY = 0x2;
    const TO_ARRAY_FK = 0x4;
    const TO_ARRAY_FOR_JSON = 0x8;
    //this specify list of fields you wish to by pass in all MVA
    const COPY_ALL_MVA_FIELD = '**';

    protected static $jsonToArrayFlag = 0;

    /**
     *
     * @var array represents entities that are part of other entities
     */
    protected static $lazyLoaded = array();

    /**
     *
     * @var EntityMvaRelationship[] array prpoperties of an entity that are stored in other tables
     */
    protected $multiValuedProperies = array();
    protected $_use_order;

    /**
     *
     * @var EntityFkRelationship[] representing pattern for loading foreign keys
     * Use the following method:
     *
     */
    protected $foreignKeys = array();

    public function __construct($rowId = null, $data = array(), DbTable $parentTable = null)
    {
        parent::__construct($rowId, $data, $parentTable);
        $this->_migrateLegacyRelationships();
        $this->initRelations();
    }

    /**
     * This method was added to easily migrate classess that use old relationship definititions
     */
    private function _migrateLegacyRelationships()
    {
        if (!empty($this->multiValuedProperies)) {
            $mvas = $this->multiValuedProperies;
            $this->multiValuedProperies = [];
            foreach ($mvas as $name => $managerClass) {
                if (EntityMvaRelationship::aClassOf($managerClass)) {
                    //just incase this is a new definition
                    $this->multiValuedProperies[$name] = $managerClass;
                } else {
                    //migrate old definition
                    $this->setOneToMany($name, $managerClass);
                    //legacy relationship, use MultivaluedAttributeEntityManager for relationship
                    $relationship = $this->getMvaRelationship($name);
                    if (MultivaluedAttributeEntityManager::aClassOf($relationship->getManagerClass())) {
                        $relationship->setFkField($relationship->getManagerClass()->getParentReferenceColumn());
                    }

                    if (isset($this->mvaLimits[$name])) {
                        $relationship->setLimit($this->mvaLimits[$name]);
                    }

                    if (isset($this->mvaOrders[$name])) {
                        $relationship->setOrder($this->mvaOrders[$name]);
                    }
                }
            }
        }

        if (!empty($this->foreignKeys)) {
            $fks = $this->foreignKeys;
            //clear old relationship
            $this->foreignKeys = [];
            foreach ($fks as $objectName => $rel) {
                if (EntityFkRelationship::aClassOf($rel)) {
                    //leave compatible definiation.
                    $this->foreignKeys[$objectName] = $rel;
                } else {
                    list($fkField, $managerClass) = each($rel);
                    $this->setManyToOne($objectName, $managerClass, $fkField);
                }
            }
        }
    }

    protected function initRelations()
    {

    }

    public function getLazilyLoaded()
    {
        $vars = get_class_vars(get_class($this));
        $dynamic = array();
        foreach (get_class_methods($this) as $methodName) {
            if (preg_match('/^_set[a-zA-Z0-9_]/', $methodName)) {
                $propertyName = GeneralUtils::camelCaseToDelimited(preg_replace('/^_set/', '', $methodName));
                $dynamic[$propertyName] = $methodName;
            }
        }

        return array_merge($dynamic, $vars['lazyLoaded']);
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->_data)) {
            $lazyLoaded = $this->getLazilyLoaded();
            if (array_key_exists($name, $this->foreignKeys)) {
                $this->__loadForeignKeyEntity($name);
            } elseif ($this->isMva($name)) {
                $this->__loadMVA($name);
            } elseif (array_key_exists($name, $lazyLoaded)) {
                $func = $lazyLoaded[$name];
                $this->$func();
            } else {
                $method = '_set' . GeneralUtils::delimetedToCamelCased($name, '/_/', GeneralUtils::CAMEL_CASE_STYLE_UCFIRST);
                if (method_exists($this, $method)) {
                    call_user_func(array($this, $method));
                }
            }
        }

        return parent::__get($name);
    }

    public static function getFkReference($entityWithFk, $referencedEntity)
    {
        $tmp = $referencedEntity ? explode("\\", $referencedEntity) : array();

        if (!empty($tmp)) {
            $parentEntityName = array_pop($tmp);
            $refMethod = 'ref' . $parentEntityName;

            $callback = array($entityWithFk, $refMethod);
            if (method_exists($callback[0], $callback[1]) && is_callable($callback) && ($fkRef = call_user_func($callback))) {
                return $fkRef;
            }

            return GeneralUtils::camelCaseToDelimited($parentEntityName) . '_id';
        }

        //no need for validation.
        throw new EntityForeignKeyException("The reference to a target entity could not be determined.");
    }

    /**
     *
     * @param string $name
     * @return EntityMvaRelationship
     */
    protected function getMvaRelationship($name)
    {
        return array_key_exists($name, $this->multiValuedProperies) ? $this->multiValuedProperies[$name] : null;
    }

    protected function __loadMVA($name)
    {
        $spec = $this->getMvaRelationship($name);
        $this->_data[$name] = $spec->getManagerClass()
            ->getEntityTable()
            ->fetchUsingIDAsKey(null, $spec->getFkWhereClause($this)->getWhereString(), $spec->getOrder(), $spec->getLimit());
    }

    public function setLazilyLoadedFields($exclude = null)
    {
        if (!$exclude) {
            $exclude = array();
        }
        $lazyLoaded = $this->getLazilyLoaded();
        foreach ($lazyLoaded as $field => $func) {
            if (in_array($field, $exclude)) {
                continue;
            }
            $this->$func();
        }
    }

    /**
     * @deprecated since version 1.0.1
     * @param string $order
     */
    public function useOrderForAssociative($order = "")
    {
        $this->_use_order = $order;
    }

    public function save($forceReplace = false, $cascade = true)
    {
        if (!$cascade) {
            return parent::save($forceReplace);
        }

        $row_id = null;
        $saveCallback = function () use (&$row_id, $forceReplace) {
            $foundMvaData = array();
            foreach (array_keys($this->multiValuedProperies) as $propertyName) {
                if (isset($this->_data[$propertyName])) {
                    $foundMvaData[$propertyName] = $this->_data[$propertyName];
                    unset($this->_data[$propertyName]);
                }
            }

            $row_id = parent::save($forceReplace);
            SystemLogger::debug("Row ID received: {$row_id}");

            if ($row_id && !empty($foundMvaData)) {
                SystemLogger::debug("Saving all MVA, for ", static::getClass(), " with ID: ", $this->_id, "Fields : ", json_encode(array_keys($foundMvaData)));
                foreach ($foundMvaData as $attribute => $mvaData) {
                    if (!empty($mvaData) && !$this->saveMva($attribute, $mvaData, $forceReplace)) {
                        return false;
                    }
                }
            }

            return !!$row_id;
        };

        DbTable::getDB()->doInTransactionOrRun($saveCallback);
        return $row_id;
    }

    /**
     *
     * Insert or Update Child entities
     * @param string $name the name of the MVA to save.
     * @param array $entities a hash map of id=>mva for the object, when the array is
     *  numerically indexed, as in just plain 0...count-1, then they are assumed to be
     *  new set of objects excpet the primary key fields are also set in each entity data
     *  if the objects are associative, then a test is run to see if the entity has just one primary key field
     *  if so, the keys of the associative array are used as primary keys, if an existing entity is found,
     *  it is update, if not a new entity is created and saved.
     * @param bool $forceInsert set to true to ensure that system always inserts the MVAs
     * @return int, the number of successfully saved children
     *
     */
    public function saveMva($name, $entities, $forceInsert = false)
    {
        if (!$this->isMva($name)) {
            SystemLogger::warn($name, "is not a MVA of the class", static::getClass());
            return false;
        }

        SystemLogger::debug("Saving", $name, "For", static::getClass(), " with ID ", $this->_id);
        $saveCallback = function () use ($name, $entities, $forceInsert) {
            return $this->_saveMvaCallback($name, $entities, $forceInsert);
        };

        //reload MVA
        if (DbTable::getDB()->doInTransactionOrRun($saveCallback)) {
            unset($this->_data[$name]);
            return true;
        }

        return false;
    }

    private function _saveMvaCallback($name, array $entities, $forceInsert = false)
    {
        $spec = $this->getMvaRelationship($name);
        $successful = 0;
        $arrayIsAssoc = array_is_assoc($entities);
        foreach ($entities as $key => $entityData) {
            //$entityPk = $entityData[$primaryKeys[0]];
            if (Entity::aClassOf($entityData)) {
                //if this is an entity object, then 
                $entityData = $entityData->_id ? $entityData->toArray(self::TO_ARRAY_MVA) : $entityData->_data;
            }
            //forced insert doesn't care about update
            //but if not forced, then attempt to update an existing entity
            //the method returns true as long as an entity was found.
            if ($forceInsert || !$this->_updateMva($spec, $key, $entityData, $arrayIsAssoc)) {
                //this is a new data.
                //ensure new entity references parent
                $entityData = array_merge($entityData, $spec->getFkData($this));
                if ($spec->getManagerClass()->createEntity($entityData)->save($forceInsert)) {
                    $successful++;
                } else {
                    return false;
                }
            } else {
                //update attempt was successful.
                $successful++;
            }
        }

        SystemLogger::debug($successful, " MVA entities saved for ", $name, " of ", static::getClass());
        return true;
    }

    private function _updateMva(EntityRelationshipSpec $spec, $key, $entityData, $arrayIsAssoc)
    {
        $pkFields = array();
        $entity = null;
        $mvaEntityPk = $spec->getManagerClass()->getPrimaryKey();

        copyElementsAtKey($mvaEntityPk, $entityData, $pkFields, true);

        //since insert is not being forced, check if this is an update.
        $searchWhere = DbTableWhere::get();

        //detect data primary keys, search for update.
        if (count($pkFields) === count($mvaEntityPk)) {
            //data contains all its primary keys.
            foreach ($pkFields as $pkField => $pkFieldVal) {
                $searchWhere->where($pkField, $pkFieldVal);
            }
        } elseif ($arrayIsAssoc && count($mvaEntityPk) === 1) {
            //check if data is indexed by primary key and only one primary key is needed
            $searchWhere->where($mvaEntityPk[0], $key);
        } else {
            //primary key not found, hence
            //we are not searching as we could not find any primary keys
            $searchWhere = null;
        }

        //we are searching still
        if ($searchWhere) {
            //merge where clause with FK search, to restrict to MVA for this object.
            $searchWhere = $searchWhere->combineWith($spec->getFkWhereClause($this));
            //entity was found, so this is an update.
            if (!($entity = $spec->getManagerClass()->getEntityWhere($searchWhere)) || !$entity->update($entityData)) {
                SystemLogger::debug("Could not upate MVA entity using search: ", $searchWhere->getWhereString());
            }
        }

        return !!$entity;
    }

    /**
     *
     * @param type $attribute
     * @return EntityManager
     */
    public function getMultiValuedPropertyEntityManager($attribute)
    {
        return $this->getMvaManager($attribute);
    }

    /**
     *
     * Add/set multivalued attribute manager
     * @param type $attribute
     * @param EntityManager $manager
     * @return static
     */
    public function addMultiValuedAttributeManager($attribute, EntityManager $manager)
    {
        return $this->setOneToMany($attribute, $manager);
    }

    /**
     * Add/set multivalued attribute manager
     * @param string $attribute
     * @param mixed $manager an EntityManager object or class or a ModelEntity class
     * @param string $mvaOrder
     * @param int $mvaLimit
     * @param array $config configuration information for the relationship
     * @param string $pkField the referenced field in the one part of the relationship
     * @param string | null $fkField the foreign key field used to reference parent in MVA
     * @return static
     * @throws EntityForeignKeyException
     */
    public function setOneToMany($attribute, $manager, $mvaOrder = null, $mvaLimit = EntityMvaRelationship::DEFAULT_MVA_LIMIT, $config = array(), $pkField = null, $fkField = null)
    {
        $managerObj = EntityRelationshipSpec::extractManager($manager);
        //this class is the entity with PK, while, we look for the entity with FK from manager
        $fkField = $fkField ?: self::getFkReference($managerObj->getEntityClass(), get_class($this));
        $pkField = $pkField ?: current($this->getParentTable()->getPrimaryKeyField());
        $this->multiValuedProperies[$attribute] = new EntityMvaRelationship($managerObj, $fkField, $pkField, $mvaOrder, $mvaLimit, $config);
        return $this;
    }

    protected function __loadForeignKeyEntity($entityName)
    {
        $where = $this->foreignKeys[$entityName]->getPkWhereClause($this);
        $this->_data[$entityName] = $this->foreignKeys[$entityName]
            ->getManagerClass()
            ->getEntityWhere($where, false);
    }

    /**
     *
     * @param string $managerClass
     * @return EntityManager
     */
    public static function loadEntityManager($managerClass)
    {
        return is_object($managerClass) && ($managerClass instanceof EntityManager) ? $managerClass : call_user_func(array($managerClass, 'instance'));
    }

    public function setForeignKey($objectName, $managerClass, $refField = null, $clearPrevious = TRUE)
    {
        return $this->setManyToOne($objectName, $managerClass, $refField, $clearPrevious);
    }

    /**
     *
     * @param string $objectName
     * @param string $managerClass
     * @param string $fkField
     * @param bool $clearPrevious
     * @param string|null $idField the primary key or referenced column of the one part
     * @param array $config configuration information for the relationship.
     * @return Entity
     */
    public function setManyToOne($objectName, $managerClass, $fkField = null, $clearPrevious = true, $idField = null, $config = array())
    {
        if ($fkField === null) {
            if (array_key_exists($objectName, $this->foreignKeys)) {
                //this means relationship is being updated.
                $fkField = $this->foreignKeys[$objectName]->getFkField();
            } else {
                $fkField = self::getFkReference(static::getClass(), ucfirst(GeneralUtils::delimetedToCamelCased($objectName)));
            }
        }

        $this->foreignKeys[$objectName] = new EntityFkRelationship($managerClass, $fkField, $idField, $config);

        if ($clearPrevious) {
            unset($this->_data[$objectName]);
        }

        return $this;
    }

    public function update($data = array(), $pkey = "")
    {
        $saveCallback = function () use ($data, $pkey) {
            if (parent::update($data, $pkey)) {
                foreach (array_keys($this->multiValuedProperies) as $name) {
                    if (isset($data[$name]) && !empty($data[$name]) && !$this->saveMva($name, (array)$data[$name])) {
                        return false;
                    }
                }
                return true;
            }

            return false;
        };


        return DbTable::getDB()->doInTransactionOrRun($saveCallback);
    }

    /**
     *
     * @param int $flag
     * The flag should be bitwise combination of
     * Entity::TO_ARRAY_MVA to include multivalued attributes, if not initialized
     * Entity::TO_ARRAY_LAZY to include lazy loaded attributes, if not initialized
     * Entity::TO_ARRAY_LAZY to include fk loaded attributes, if not initialized
     * When applied to an attribute that is also an entity, the flags will be sent in.
     *
     *
     * @return array, the array of attributes
     */
    public function toArray($flag = 0, $depth = 8, $fields = array())
    {
        if ($depth < 0) {
            return array();
        }
        $dataTmp = parent::toArray();
        $data = array();
        $myFields = !empty($fields) ? $fields : self::$toArrayFields[get_class($this)];

        copyElementsAtKey(count($myFields) ? $myFields : $this->getParentTable()->getFields(true), $dataTmp, $data, true);
        $depth--;
        if ($flag & self::TO_ARRAY_MVA) {
            foreach (array_keys($this->multiValuedProperies) as $prop) {
                if (count($myFields) && !in_array($prop, $myFields)) {
                    continue;
                }
                $mvas = $this->$prop;
                $data[$prop] = array();
                if (count($mvas)) {
                    foreach ($mvas as $id => $mva) {
                        $data[$prop][$id] = is_array($mva) ? $mva : $mva->toArray($flag, $depth);
                    }
                }
            }
        }

        if ($flag & self::TO_ARRAY_LAZY) {
            $lazies = array_keys($this->getLazilyLoaded());
            foreach ($lazies as $prop) {
                if (count($myFields) && !in_array($prop, $myFields)) {
                    continue;
                }

                $lazyLoaded = $this->$prop;
                //echo $lazyLoaded, "\n";
                if ($lazyLoaded && is_a($lazyLoaded, Entity::getClass())) {
                    $data[$prop] = $lazyLoaded->toArray($flag, $depth);
                } elseif (is_array($lazyLoaded) && count($lazyLoaded)) {
                    foreach ($lazyLoaded as $id => $lazy) {
                        if (is_a($lazy, Entity::getClass())) {
                            $data[$prop][$id] = $lazy->toArray($flag, $depth);
                        } else {
                            $data[$prop][$id] = $lazy;
                        }
                    }
                } else {
                    $data[$prop] = $lazyLoaded;
                }
            }
        }

        if ($flag & self::TO_ARRAY_FK) {
            foreach (array_keys($this->foreignKeys) as $prop) {
                if (count($myFields) && !in_array($prop, $myFields)) {
                    continue;
                }

                $fkEntity = $this->$prop;
                if ($fkEntity) {
                    $data[$prop] = $fkEntity->toArray($flag, $depth);
                }
            }
        }

        if (($flag & self::TO_ARRAY_FOR_JSON)) {
            $this->_jsonFilterData($data, $flag);
        }

        return $data;
    }

    protected function _jsonFilterData(&$data, $flag = 0)
    {
        $ignores = $this->_jsonIgnoresGet();
        foreach (array_keys($data) as $aKey) {
            if (in_array($aKey, $ignores)) {
                unset($data[$aKey]);
            }

            if (($flag & self::TO_ARRAY_MVA) && ($flag & self::TO_ARRAY_FK)) {
                /* @var $fkObject Entity */
                if ($this->hasFk($aKey) && ($fkObject = $this->_data[$aKey]) && $fkObject->hasMvaOf(static::getClass())) {
                    //disable cyclic dependency.
                    unset($data[$aKey]);
                }
            }
        }

        return $data;
    }

    /**
     * Checks if an entity has FK object with the specified name
     * @param string $name
     * @return boolean
     */
    public function hasFk($name)
    {
        return array_key_exists($name, $this->foreignKeys);
    }

    /**
     * Checks if an entity has MVA of the speciified entity class.
     * @param string $entityClass entity class
     * @param string $attribute a variable passed by reference,
     *  it will hold the name of the MVA if matched
     * @return boolean
     */
    public function hasMvaOf($entityClass, &$attribute = null)
    {
        $entityClass = ltrim($entityClass, '\\');
        foreach ($this->multiValuedProperies as $mva => $spec) {
            if (ltrim($spec->getManagerClass()->getEntityClass(), '\\') == $entityClass) {
                $attribute = $mva;
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param array $fields set fields to return when toArray is called on
     * an Object of this class.
     *
     *
     */
    public static function setToArrayFields($fields = array())
    {
        self::$toArrayFields[get_called_class()] = $fields;
    }

    /**
     * Unregister fields to return when toArray is called on objects of this class,
     * This implies all the fields will be returned.
     *
     */
    public static function unsetToArrayFields()
    {
        unset(self::$toArrayFields[get_called_class()]);
    }

    public static function getToArrayFields($mineAlone = TRUE)
    {
        return $mineAlone ? self::$toArrayFields[get_called_class()] : self::$toArrayFields;
    }

    public function isMva($name)
    {
        return array_key_exists($name, $this->multiValuedProperies);
    }

    public function countMva($name)
    {
        if ($this->isMva($name)) {
            return $this->getMvaRelationship($name)->count($this);
        } else {
            return -1;
        }
    }

    public function deleteMva($name)
    {
        if ($this->isMva($name)) {
            return $this->getMvaRelationship($name)->delete($this);
        }

        return false;
    }

    /**
     *
     * @param string $name of the multivalued attribute.
     * @return EntityManager
     */
    public function getMvaManager($name)
    {
        if (array_key_exists($name, $this->multiValuedProperies)) {
            return $this->multiValuedProperies[$name]->getManagerClass();
        }

        throw (new Exception("The attribute has no attached MVA Entity Manager"));
    }

    public function __call($name, $arguments)
    {
        if (preg_match('/^count[A-Z_][a-zA-Z0-9_]/', $name)) {
            $property = preg_replace('/^count_/', '', GeneralUtils::camelCaseToDelimited($name));
            if ($this->isMva($property)) {
                return $this->countMva($property);
            }
        }

        return parent::__call($name, $arguments);
    }

    /**
     * Get a primary key object, it represents a scalar for the primary key
     * @return EntityPk
     */
    public function getPrimaryKey()
    {
        $primaryKeyFields = $this->_parentTable->getPrimaryKeyField();
        $pkValues = array();
        copyElementsAtKey($primaryKeyFields, $this->_data, $pkValues);
        return new EntityPk($pkValues);
    }

    /**
     *
     * This method copies an entity up to it's maxDepth level, only entity fields
     * and multi-valued properties are copied.
     * @param bool $save
     * @param array $ignoreProperties the properties that should skipped, properties for
     * MVA can be nested in the definition.
     * @param int $maxDepth default maximum depth to copy to
     * @param int $currentDepth should only be used internally.
     * @return static Description
     */
    public function deepCopy($save = false, array $ignoreProperties = array(), $maxDepth = 8)
    {
        $entity = $this->_deepCopy($ignoreProperties, $maxDepth, 0);
        if ($save) {
            $entity->save();
        }
        return $entity;
    }

    /**
     * Internal deep copy.
     * @param bool $save
     * @param array $ignoreProperties
     * @param int $maxDepth
     * @param int $currentDepth
     * @return static
     */
    protected function _deepCopy(array $ignoreProperties = array(), $maxDepth = 8, $currentDepth = 0)
    {
        $copyData = array();

        $fields = $this->_parentTable->getFields(true);
        $allFields = array_key_exists(self::COPY_ALL_MVA_FIELD, $ignoreProperties) ? $ignoreProperties [self::COPY_ALL_MVA_FIELD] : array();

        $skipProperties = array_merge($ignoreProperties, $this->_parentTable->getPrimaryKeyField(), $allFields);

        foreach ($fields as $field) {
            if (!in_array($field, $skipProperties)) {
                $copyData[$field] = $this->_data[$field];
            }
        }

        if (++$currentDepth < $maxDepth && count($this->multiValuedProperies) > 0) {
            //now clone MVA
            foreach ($this->multiValuedProperies as $mvaProperty => $spec) {
                if (!in_array($mvaProperty, $skipProperties)) {
                    //check if this one is not to be skipped
                    $mvaValues = $this->{$mvaProperty}; //load MVA
                    if (count($mvaValues) > 0) {
                        //does MVA have values?
                        $skipMvaProperties = array_key_exists($mvaProperty, $skipProperties) ? (array)$skipProperties[$mvaProperty] : array();
                        //are there any properties specified to be skipped
                        //can we include all fields
                        if (!array_key_exists(self::COPY_ALL_MVA_FIELD, $skipMvaProperties)) {
                            $skipMvaProperties[self::COPY_ALL_MVA_FIELD] = $allFields;
                        }

                        //skip the foreign key field also
                        $refFieldInMva = $spec->getReferencedField();
                        $skipMvaProperties[] = $refFieldInMva;

                        $copyData[$mvaProperty] = array();
                        foreach ($mvaValues as $anMva) {
                            /* @var $anMva Entity */
                            $copyData[$mvaProperty][] = $anMva->_deepCopy($skipMvaProperties, $maxDepth, $currentDepth)->_data;
                        }
                    }
                }
            }
        }

        return new static(null, $copyData, $this->_parentTable);
    }

    public function __ideoIgnoredJsonFields()
    {
        return array_merge(parent::__ideoIgnoredJsonFields(), ['lazilyLoaded', 'primaryKey']);
    }

    public static function setJsonToArrayFlag($flag)
    {
        static::$jsonToArrayFlag = $flag;
    }

    public function jsonSerialize()
    {
        $dataKeys = array_keys($this->toArray(static::$jsonToArrayFlag | self::TO_ARRAY_FOR_JSON, 1));
        $dataIn = [];
        copyElementsAtKey($dataKeys, $this->_data, $dataIn);
        $entityArr = static::jsonTranslateKeys($dataIn, $this->_jsonIgnoresGet());
        $this->_jsonApplyFilterMap($entityArr);
        return array_merge(parent::jsonSerialize(), $entityArr);
    }

    private static function jsonTranslateKeys(array $data, $ignores)
    {
        $result = array();

        foreach ($data as $k => $value) {
            $kTranslated = static::jsonGetFieldName($k);
            if (!in_array($k, $ignores) && !in_array($kTranslated, $ignores)) {
                if (static::$JSON_FIELD_STYLE === IDEO_JSON_FIELD_STYLE_ANY) {
                    $result[$k] = $value;
                } else {
                    $result[$kTranslated] = is_array($value) ? static::translateKeys($value) : $value;
                }
            }
        }

        return $result;
    }

    public function lockForUpdate($reload = true)
    {
        if (DbTable::getDB()->isInTransaction()) {
            $primaryKeys = $this->_parentTable->getPrimaryKeyField();
            $where = DbTableWhere::get();
            foreach ($primaryKeys as $pkField) {
                $where->where($pkField, $this->_data[$pkField]);
            }

            if (($row = $this->_parentTable->fetchRow($where->getWhereString(), null, true))) {
                if ($reload) {
                    $this->_data = $row->_data;
                }
                return true;
            }
        }
        return false;
    }

    public function getAs($newClass)
    {

        if (static::getClass() === $newClass) {
            return $this;
        }

        if (!static::isAncesstorOf($newClass)) {
            throw new EntityCastException($newClass . " is not an instance of " . Entity::getClass());
        }

        return new $newClass($this->_id, $this->_data, $this->_parentTable);
    }

}

class EntityForeignKeyException extends Exception
{

}

class EntityMvaException extends Exception
{

}

class EntityCastException extends RuntimeException
{

}
