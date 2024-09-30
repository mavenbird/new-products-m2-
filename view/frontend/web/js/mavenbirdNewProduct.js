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
define([
    'jquery',
    'jquery-ui-modules/core',
    'jquery-ui-modules/widget',
    'mavenbird/npowlcarousel'
], function ($) {
    "use strict";

    $.widget('mavenbird.mavenbirdNewProduct', {
        options: {
            nav: '',
            autoplay: '',
            slider_id: '', // Ensure slider_id is provided in options
            product_count: 0, // Ensure product_count is provided and is a number
            dots: ''
        },
        is_loading: 0,

        _create: function () {
            this._initialize();
        },

        _initialize: function () {
            var self = this;
            self.removeRedirectAttr();
            self.findPaginationElement();
            self.addSlider();

            // Set up MutationObserver to observe changes in the DOM
            var observer = new MutationObserver(function (mutationsList) {
                for (var mutation of mutationsList) {
                    if (mutation.type === 'childList') {
                        // Check for added nodes and perform actions if .cwsNew is present
                        $(mutation.addedNodes).each(function () {
                            if ($(this).hasClass('cwsNew') || $(this).find('.cwsNew').length) {
                                self.removeRedirectAttr();
                                self.findPaginationElement();
                            }
                        });
                    }
                }
            });

            // Start observing the document body for changes
            observer.observe(document.body, { childList: true, subtree: true });
        },

        removeRedirectAttr: function () {
            var self = this;
            $("body .mageNewToolbar").find('[data-mage-init]').each(function () {
                var $element = $(this);
                if ($element.attr('id') === "limiter") {
                    $element.removeAttr('data-mage-init').on('change', function () {
                        var url = $(this).val();
                        self.ajaxLoadContent(url);
                    });
                }
            });
        },

        findPaginationElement: function () {
            var self = this;
            $("body .mageNewToolbar").find("a").each(function () {
                var $link = $(this);
                var linkClass = $link.attr("class");
                var classes = ["page", "action previous", "action next"];

                if (classes.includes(linkClass)) {
                    $link.attr("onclick", "return false;");
                    var url = $link.attr("href");
                    $link.on('click', function () {
                        if (self.is_loading === 0) {
                            self.ajaxLoadContent(url);
                        }
                    });
                }
            });
        },

        ajaxLoadContent: function (url) {
            var self = this;
            self.is_loading = 1;
        
            $.ajax({
                url: url,
                type: "GET",
                cache: false,
                beforeSend: function () {
                    $('body').addClass('np-stop-scrolling');
                    $('#np_scroll_loading').show();
                },
                success: function (data) {
                    self.is_loading = 0;
                    $('body').removeClass('np-stop-scrolling');
                    history.pushState({}, "", url);
                    $('#np_scroll_loading').hide();
        
                    var $data = $(data);
                    var pageContent = $data.find('.cwsNew').html() || $data.filter('.cwsNew').html();
                    $(".cwsNew").html(pageContent).trigger('contentUpdated');
                },
                error: function () {
                    self.is_loading = 0;
                    $('body').removeClass('np-stop-scrolling');
                    $('#np_scroll_loading').hide();
                    console.error("Failed to load content from URL: " + url);
                }
            });
        },
        

        addSlider: function () {
            var nav = this.options.nav === "true";
            var autoplay = this.options.autoplay === "true";
            var dots = this.options.dots === "true";
            var $owl = $('#' + this.options.slider_id);
            var itemAmount = parseInt(this.options.product_count, 10); 
            // console.log("product_count:", this.options.product_count);
            // console.log("itemAmount:", itemAmount);
            var loopOptions = [
                itemAmount > 1,
                itemAmount > 2,
                itemAmount > 3,
                itemAmount > 4,
                itemAmount > 5
            ];
            
            $owl.owlCarousel({
                slideSpeed: 200,
                paginationSpeed: 800,
                nav: nav,
                dots: dots,
                autoplay: autoplay,
                margin: 20,
                responsiveClass: true,
                responsive: {
                    300: { items: 1, loop: loopOptions[0] },
                    479: { items: 2, loop: loopOptions[1] },
                    600: { items: 2, loop: loopOptions[1] },
                    767: { items: 3, loop: loopOptions[2] },
                    999: { items: 4, loop: loopOptions[3] },
                    1280: { items: 5, loop: loopOptions[4] }
                }
            });

            if (autoplay) {
                $owl.on('mouseenter', function () {
                    $owl.trigger('stop.owl.autoplay');
                }).on('mouseleave', function () {
                    $owl.trigger('play.owl.autoplay', [200]);
                });
            }
        }
    });

    return $.mavenbird.mavenbirdNewProduct;
});
