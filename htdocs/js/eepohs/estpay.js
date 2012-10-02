/**
 * @package    Eepohs
 * @subpackage Estpay
 */

/**
 * Custom JS for Estpay payment module
 *
 * PLEASE READ THIS SOFTWARE LICENSE AGREEMENT ("LICENSE") CAREFULLY
 * BEFORE USING THE SOFTWARE. BY USING THE SOFTWARE, YOU ARE AGREEING
 * TO BE BOUND BY THE TERMS OF THIS LICENSE.
 * IF YOU DO NOT AGREE TO THE TERMS OF THIS LICENSE, DO NOT USE THE SOFTWARE.
 *
 * Copyright (c) $year$, Eepohs OÜ
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @license http://opensource.org/licenses/bsd-license.php
 * @version $version$
 * @author Eepohs OÜ
 * @copyright $year$ Eepohs OÜ http://www.eepohs.com/
 *
 * @package    Eepohs
 * @subpackage Estpay
 * @category   Payment methods
 */
Event.observe(window, 'load', function() {
    initEepohsPayment();
});

EepohsPayment = Class.create();
EepohsPayment.prototype = {
    servicesApi:'http://services.eepohs.com',
    version:0,
    initialize: function(){

    },
    getServiceUrl: function(url){
        return this.servicesApi + url;
    },
    /**
     * @elements array of element id-s / payment methods
     */
    addLogoTypes: function(elementIds){
        $A(elementIds).each(
            function(item){
                var img = document.createElement('img');
                Element.extend(img);
                img.src = '/skin/adminhtml/default/default/images/eepohs/estpay/eepohs_logo_small.png';
                img.addClassName('eepohs-logo title-logo');
                img.writeAttribute('alt', 'Eepohs');
                img.writeAttribute('title', 'Eepohs');
                $(item).appendChild(img);
            }
            );
    },
    addCss: function(elementClass, classPrefixes){
        $A(classPrefixes).each(function(item){
            $(item).up('div.'+elementClass).addClassName('eepohs-payment-method-head');
        });
    },
    /**
     * Adds blocks with support information
     */
    addSupportInformation: function(elementIds){
        var template = $('supportInfoTemplate');
        $A(elementIds).each(
            function(item){
                $(item).appendChild(template.clone(true).removeClassName('no-display'));
            }
            );

    },
    addRegisterButton: function(){

    },
    /**
     * Sets current module version
     */
    setModuleVersion: function(version){
        this.version = version;
    },
    /**
     * Checks for current module upgrades from Eepohs Services API
     */
    checkForUpgrades: function(module, version){
        //TODO implement local AJAX proxy
        //        var url = this.servicesApi + '/software/checkVersion';
        //        new Ajax.Request(url,{
        //            onSuccess: function(response){
        //                alert( response.responseJSON );
        //            }
        //        });
        return;
    }
}