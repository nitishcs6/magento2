<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Authorization\Model;

/**
 * Admin Role Model
 *
 * @api
 * @method int getParentId()
 * @method \Magento\Authorization\Model\Role setParentId(int $value)
 * @method int getTreeLevel()
 * @method \Magento\Authorization\Model\Role setTreeLevel(int $value)
 * @method int getSortOrder()
 * @method \Magento\Authorization\Model\Role setSortOrder(int $value)
 * @method string getRoleType()
 * @method \Magento\Authorization\Model\Role setRoleType(string $value)
 * @method int getUserId()
 * @method \Magento\Authorization\Model\Role setUserId(int $value)
 * @method string getUserType()
 * @method \Magento\Authorization\Model\Role setUserType(string $value)
 * @method string getRoleName()
 * @method \Magento\Authorization\Model\Role setRoleName(string $value)
 * @api
 * @since 100.0.2
 */
class Role extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'authorization_roles';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Authorization\Model\ResourceModel\Role $resource
     * @param \Magento\Authorization\Model\ResourceModel\Role\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Authorization\Model\ResourceModel\Role $resource,
        \Magento\Authorization\Model\ResourceModel\Role\Collection $resourceCollection,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritDoc
     *
     * @SuppressWarnings(PHPMD.SerializationAware)
     * @deprecated Do not use PHP serialization.
     */
    public function __sleep()
    {
        throw new \RuntimeException('tst');//trigger_error('Using PHP serialization is deprecated', E_USER_DEPRECATED);

        $properties = parent::__sleep();
        return array_diff($properties, ['_resource', '_resourceCollection']);
    }

    /**
     * @inheritDoc
     *
     * @SuppressWarnings(PHPMD.SerializationAware)
     * @deprecated Do not use PHP serialization.
     */
    public function __wakeup()
    {
        throw new \RuntimeException('tst');//trigger_error('Using PHP serialization is deprecated', E_USER_DEPRECATED);

        parent::__wakeup();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_resource = $objectManager->get(\Magento\Authorization\Model\ResourceModel\Role::class);
        $this->_resourceCollection = $objectManager->get(
            \Magento\Authorization\Model\ResourceModel\Role\Collection::class
        );
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Magento\Authorization\Model\ResourceModel\Role::class);
    }

    /**
     * Update object into database
     *
     * @return $this
     */
    public function update()
    {
        $this->getResource()->update($this);
        return $this;
    }

    /**
     * Return users for role
     *
     * @return array
     */
    public function getRoleUsers()
    {
        return $this->getResource()->getRoleUsers($this);
    }
}
