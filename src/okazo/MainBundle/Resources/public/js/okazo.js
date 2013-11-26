/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Raise a message error
 * @param {string} title Titre du message
 * @param {string} message Message
 * @param {integer} lifeDuration Durée d'affichage
 * @returns {undefined}
 */
function raiseError(title, message, lifeDuration) {
        if (!lifeDuration) { lifeDuration=2000;}
        $.notific8(message, {
            life: lifeDuration,
            heading: title,
            theme: 'ruby',
            sticky: false,
            horizontalEdge: 'bottom',
            verticalEdge: 'left',
            zindex: 1500
        });
    }
    function raiseSuccess(title, message, lifeDuration) {
        if (!lifeDuration) { lifeDuration=2000;}
        $.notific8(message, {
            life: lifeDuration,
            heading: title,
            theme: 'lime',
            sticky: false,
            horizontalEdge: 'bottom',
            verticalEdge: 'left',
            zindex: 1500
        });
    }
    function raiseInformation(title, message, lifeDuration) {
        if (!lifeDuration) { lifeDuration=2000;}
        $.notific8(message, {
            life: lifeDuration,
            heading: title,
            theme: 'teal',
            sticky: false,
            horizontalEdge: 'bottom',
            verticalEdge: 'left',
            zindex: 1500
        });
    }

    function confirmBox(message) {
        var defer = $.Deferred();
        //confirmation box
        $('<div></div>').appendTo('body')
                .html('<div><h6>' + message + '</h6></div>')
                .dialog({
                    modal: true, title: 'Confirmation', zIndex: 10000, autoOpen: true, draggable: false,
                    width: '400px', resizable: false,
                    buttons: {
                        Yes: function() {
                            defer.resolve("yes"); //on Yes click, end deferred state successfully (done)
                            $(this).dialog("close");
                        },
                        No: function() {
                            defer.resolve("no");
                            $(this).dialog("close");
                        }
                    },
                    close: function(event, ui) {
                        $(this).remove();
                    }
                });
        return defer.promise(); //important to return the defer promise

    }
