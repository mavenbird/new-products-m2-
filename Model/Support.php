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

use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Support extends \Magento\Config\Block\System\Config\Form\Field
{
   /**
    * Render
    *
    * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
    */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '';
        $html .= '<div style="float: left;">
		<a href="https://www.mavenbird.com" target="_blank"><img src="https://www.mavenbird.com/media/logo/stores/1/mavenbird-logo_1.png" style="float:left; padding-right: 35px; margin-top: 30px;" /></a></div>
		<div style="float:left">
		<h2><b>MavenBird New Products Extension</b><br>
		<b>Installed Version: v1.0.0</b><br>
		</h2>
		<p>
		Do you need Extension Support? Please create support ticket from <a href="http://support.mavenbird.com" target="_blank">here</a> or <br> Please contact us on <a href="mailto:support@mavenbird.com">support@mavenbird.com</a> for quick reply.
		</p>
		</div>';
        return $html;
    }
}
