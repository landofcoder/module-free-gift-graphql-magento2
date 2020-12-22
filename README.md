# Magento 2 Free Gifts - Sales Gift Rule GraphQL / PWA (FREE)

    ``landofcoder/module-free-gift-graphql-magento2``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)
 
## Requiments
- landofcoder/module-free-gift-magento2

## Main Functionalities
[Landofcoder Free Gifts for Magento 2](https://landofcoder.com/magento-2-free-gift-extension.html) is a handy tool that simplifies the process of giving away free gifts to customers on the online store. 

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Lof`
 - Enable the module by running `php bin/magento module:enable Lof_GiftSaleRuleGraphQl
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require landofcoder/module-free-gift-magento2`
 - enable the module by running `php bin/magento module:enable Lof_GiftSaleRuleGraphQl`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

