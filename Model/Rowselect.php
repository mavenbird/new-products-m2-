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

class Rowselect implements \Magento\Framework\Option\ArrayInterface
{

   
    /**
     * To option array
     */
    public function toOptionArray()
    {
        return [['value' => 1, 'label' => __('1')],['value' => 2, 'label' => __('2')], ['value' =>3, 'label' => __('3')],['value' =>4, 'label' => __('4')],['value' =>5, 'label' => __('5')],['value' =>6, 'label' => __('6')]];
    }
    
    /**
     * To array
     */
    public function toArray()
    {
        return [6 => __('6'),5 => __('5'),4 => __('4'),3 => __('3'), 2 => __('2'),1=>__('1')];
    }
}
