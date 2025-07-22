/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

define([
    'jquery',
    'domReady',
    'dropdownDialog'
], function ($, domReady) {
    'use strict';

    var globalOptions = {
        elementIdentifier: null,
        infoContainerIdentifier: null,
        infoLabelIdentifier: [
            'div.field >label >span',
            'div.field.date >fieldset >legend >span'
        ]
    };

    $.widget('mage.productOptionsInfo', {
        options: globalOptions,

        _create: function createProductOptionsInfo() {
        },

        _init: function initProductOptionsInfo() {
            var self = this;

            var elementIdentifier = self.options.elementIdentifier;

            if (elementIdentifier === null) {
                elementIdentifier = self.element;
            }

            var infoContainerIdentifier = self.options.infoContainerIdentifier;

            if (infoContainerIdentifier === null) {
                infoContainerIdentifier = self.element;
            }

            domReady(function() {
                $.each(self.getInfoLabelIdentifier(), function (index, identifier) {
                    var infoElements = $(identifier, elementIdentifier);

                    infoElements.each(function () {
                        var infoTrigger = $(this);
                        var optionWrapper = infoTrigger.closest('.field-wrapper');
                        var optionId = optionWrapper.data('option-id');
                        var infoContent = $('#option-info-' + optionId, infoContainerIdentifier);

                        if (infoContent.length > 0) {
                            infoTrigger.addClass('product-option-info-trigger');

                            infoContent.dropdownDialog({
                                appendTo: '[data-option-id=' + optionId + ']',
                                triggerTarget: '[data-option-id=' + optionId + '] .product-option-info-trigger',
                                triggerEvent: 'mouseenter',
                                timeout: 0,
                                closeOnMouseLeave: true,
                                closeOnEscape: true,
                                triggerClass: 'active',
                                buttons: []
                            });
                        }
                    });
                });

                $('div.field select', elementIdentifier).each(function() {
                    var optionSelectElement = $(this);
                    var optionWrapper = optionSelectElement.closest('.field-wrapper');
                    var optionId = optionWrapper.data('option-id');

                    optionSelectElement.on('select2:open', function (event) {
                        var select = $(event.target);

                        setTimeout(function(){
                            select.parent().find('.select2-container .select2-results ul li').each(function() {
                                var optionValueElement = $(this);
                                var optionElementId = optionValueElement.data('select2-id');

                                if (optionElementId.indexOf('result') !== -1) {
                                    var optionValueId = optionElementId.substring(optionElementId.lastIndexOf('-') + 1);

                                    var infoContent = $('#option-value-info-' + optionId + '-' + optionValueId,
                                        infoContainerIdentifier);

                                    if (infoContent.length > 0) {
                                        optionValueElement.addClass('product-option-info-trigger');

                                        infoContent.dropdownDialog({
                                            appendTo: '[data-option-id=' + optionId + '] .field .control',
                                            triggerEvent: 'mouseenter',
                                            timeout: 0,
                                            closeOnMouseLeave: true,
                                            closeOnEscape: true,
                                            triggerClass: 'active',
                                            buttons: []
                                        });

                                        optionValueElement.on('mouseenter', function() {
                                            infoContent.dropdownDialog('open');
                                        });

                                        optionValueElement.on('mouseleave', function() {
                                            infoContent.dropdownDialog('close');
                                        });

                                        optionSelectElement.on('select2:select', function() {
                                            infoContent.dropdownDialog('close');
                                        });
                                    }
                                }
                            });
                            select.parent().find('.select2-container .select2-results ul li');
                        }, 250);
                    });
                });

                $('div.field div.options-list div.field.choice', elementIdentifier).each(function() {
                    var optionSelectElement = $(this);
                    var optionWrapper = optionSelectElement.closest('.field-wrapper');
                    var optionId = optionWrapper.data('option-id');

                    if (optionId) {
                        var optionValueId = null;

                        var radioButtonElement = optionSelectElement.find('input[type="radio"]');
                        if (radioButtonElement.length > 0) {
                            optionValueId = radioButtonElement.val();
                        }

                        var checkboxButtonElement = optionSelectElement.find('input[type="checkbox"]');
                        if (checkboxButtonElement.length > 0) {
                            optionValueId = checkboxButtonElement.val();
                        }

                        if (optionValueId) {
                            var infoContent = $('#option-value-info-' + optionId + '-' + optionValueId,
                                infoContainerIdentifier);

                            if (infoContent.length > 0) {
                                infoContent.dropdownDialog({
                                    appendTo: '[data-option-id=' + optionId + '] .field .control',
                                    triggerTarget: optionSelectElement,
                                    triggerEvent: 'mouseenter',
                                    timeout: 0,
                                    closeOnMouseLeave: true,
                                    closeOnEscape: true,
                                    triggerClass: 'active',
                                    buttons: []
                                });

                                optionSelectElement.addClass('product-option-info-trigger');
                            }
                        }
                    }
                });
            });
        },

        getInfoLabelIdentifier: function getInfoLabelIdentifier() {
            return this.options.infoLabelIdentifier;
        }
    });

    return $.mage.productOptionsInfo;
});
