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
                img.src = EepohsPayment.prototype.getServiceUrl('/graphics/logo/x/16/');
                img.addClassName('eepohs-logo title-logo');
                img.writeAttribute('alt', 'Eepohs');
                img.writeAttribute('title', 'Eepohs');
                $(item).appendChild(img);
            }
            );
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
    checkForUpgrades: function(version){

    }
}