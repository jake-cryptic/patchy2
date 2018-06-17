// This code will stop document.cookie from working, greatly securing sections of the site
if(!document.__defineGetter__) {
    Object.defineProperty(document, 'cookie', {
        get: function(){return ''},
        set: function(){return true},
    });
} else {
    document.__defineGetter__("cookie", function() { return 'Patchy Security prevents cookie use'; } );
    document.__defineSetter__("cookie", function() { return 'Patchy Security prevents cookie use'; } );
}
console.log("{Info}: Vulnerable function removed :)");