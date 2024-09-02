<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_Newproduct
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */
namespace Mavenbird\Newproduct\Model;

class Sortby implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * To option array
     */
    public function toOptionArray()
    {
        return [['value' =>'position', 'label' => __('Position')],['value' =>'price', 'label' => __('Price')], ['value' =>'name', 'label' => __('Name')]];
    }

    /**
     * To array
     */
    public function toArray()
    {
        return [0 => __('Name'), 1 => __('Price'),2=>__('Position')];
    }
}
