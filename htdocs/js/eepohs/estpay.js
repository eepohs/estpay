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
 * Full text of this license is available @license
 *
 * @license http://www.eepohs.com/eepohs-commercial-software-license/
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
                img.src = EepohsPayment.prototype.getServiceUrl('/graphics/logo/x/14/');
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