<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\TestFramework\Helper\Bootstrap;
use Magento\Config\Model\Config\Factory as ConfigFactory;
use Magento\Store\Model\Website;
use Magento\Store\Model\Store;
use Magento\CatalogSearch\Model\Indexer\Fulltext as FulltextIndex;

//Creating second website with a store.
$website = Bootstrap::getObjectManager()->create(Website::class);
/** @var $website \Magento\Store\Model\Website */
if (!$website->load('test', 'code')->getId()) {
    $website->setData([
        'code' => 'test',
        'name' => 'Test Website',
        'default_group_id' => '1',
        'is_default' => '0'
    ]);
    $website->save();
}
$websiteId = $website->getId();
$store = Bootstrap::getObjectManager()->create(Store::class);
if (!$store->load('fixture_second_store', 'code')->getId()) {
    $groupId = Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getWebsite()->getDefaultGroupId();
    $store->setCode(
        'fixture_second_store'
    )->setWebsiteId(
        $websiteId
    )->setGroupId(
        $groupId
    )->setName(
        'Fixture Second Store'
    )->setSortOrder(
        10
    )->setIsActive(
        1
    );
    $store->save();
}

/* Refresh stores memory cache */
Bootstrap::getObjectManager()->get(
    \Magento\Store\Model\StoreManagerInterface::class
)->reinitStores();

/* Refresh CatalogSearch index */
/** @var \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry */
$indexerRegistry = Bootstrap::getObjectManager()
    ->create(\Magento\Framework\Indexer\IndexerRegistry::class);
$indexerRegistry->get(FulltextIndex::INDEXER_ID)->reindexAll();

//Setting up allowed countries
/** @var ConfigFactory $configFactory */
$configFactory = Bootstrap::getObjectManager()->get(ConfigFactory::class);
//Allowed countries for default website.
$configData = [
    'section' => 'general',
    'website' => 1,
    'store' => null,
    'groups' => [
        'country' => [
            'default' => ['inherit' => 1],
            'allow' => ['value' => ['FR']]
        ]
    ]
];
$configModel = $configFactory->create(['data' => $configData]);
$configModel->save();
//Allowed countries for second website
$configData = [
    'section' => 'general',
    'website' => $websiteId,
    'store' => null,
    'groups' => [
        'country' => [
            'default' => ['inherit' => 1],
            'allow' => ['value' => ['ES']]
        ]
    ]
];
$configModel = $configFactory->create(['data' => $configData]);
$configModel->save();

/* Refresh stores memory cache */
Bootstrap::getObjectManager()->get(
    \Magento\Store\Model\StoreManagerInterface::class
)->reinitStores();

/* Refresh CatalogSearch index */
/** @var \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry */
$indexerRegistry = Bootstrap::getObjectManager()
    ->create(\Magento\Framework\Indexer\IndexerRegistry::class);
$indexerRegistry->get(FulltextIndex::INDEXER_ID)->reindexAll();
