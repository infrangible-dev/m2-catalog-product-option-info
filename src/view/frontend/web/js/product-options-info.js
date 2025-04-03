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
        info: {}
    };

    $.widget('mage.productOptionsInfo', {
        options: globalOptions,

        _create: function createProductOptionsInfo() {
        },

        _init: function initProductOptionsInfo() {
            var self = this;

            domReady(function() {
                $.each(self.getInfoIdentifier(), function (index, identifier) {
                    var infoElements = $(identifier);

                    infoElements.each(function () {
                        var infoTrigger = $(this);
                        var optionWrapper = infoTrigger.closest('.field-wrapper');
                        var optionId = optionWrapper.data('option-id');
                        var infoContent = $('#option-info-' + optionId);

                        if (infoContent.length > 0) {
                            infoContent.dropdownDialog({
                                appendTo: '[data-option-id=' + optionId + ']',
                                triggerTarget: identifier,
                                triggerEvent: 'mouseenter',
                                timeout: 0,
                                closeOnMouseLeave: true,
                                closeOnEscape: true,
                                triggerClass: 'active',
                                buttons: []
                            });

                            infoTrigger.addClass('info');
                        }
                    });
                });

                $.each(self.getValueInfoIdentifier(), function (index, identifier) {
                    var infoElements = $(identifier);

                    infoElements.each(function () {
                        var infoTrigger = $(this);
                        var optionWrapper = infoTrigger.closest('.field-wrapper');
                        var optionId = optionWrapper.data('option-id');
                        var optionValueId = infoTrigger.val();

                        if (optionValueId) {
                            var infoContent = $('#option-value-info-' + optionId + '-' + optionValueId);

                            if (infoContent.length > 0) {
                                var triggerTargetIdentifier;

                                if (infoTrigger.prop('nodeName') === 'OPTION') {
                                    triggerTargetIdentifier = '#product-option-' + optionId +
                                        ' select option[value=' + optionValueId + ']';
                                }

                                if (triggerTargetIdentifier) {
                                    infoContent.dropdownDialog({
                                        appendTo: '[data-option-id=' + optionId + ']',
                                        triggerTarget: triggerTargetIdentifier,
                                        triggerEvent: 'mouseover',
                                        timeout: 0,
                                        closeOnMouseLeave: true,
                                        closeOnEscape: true,
                                        triggerClass: 'active',
                                        buttons: []
                                    });

                                    infoTrigger.addClass('info');
                                }
                            }
                        }
                    });
                });
            });
        },

        getInfoIdentifier: function getInfoIdentifier() {
            return [
                '#product-options-wrapper div.field label span',
                '#product-options-wrapper div.field.date legend span'
            ];
        },

        getValueInfoIdentifier: function getInfoIdentifier() {
            return [
                '#product-options-wrapper div.field select option'
            ];
        },
    });

    return $.mage.productOptionsInfo;
});
