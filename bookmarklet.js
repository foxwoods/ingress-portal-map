javascript:
(function(){

// use Firebase to store portal data
var setupFirebase = function(){
  $.getScript('https://cdn.firebase.com/v0/firebase.js', function(){

    // ** change the parameter in the following call to Firebase constructor to your firebase ref URL **
    var rootRef = new Firebase('https://czftra.firebaseio.com/');

    window.fbase = {
      upsertPortal: function(data){
        rootRef.child('portals').update(data);
      }
    };
  });
};

// use jQuery Ajax Prefilter to capture portal data
var setupPrefilter = function(){
  $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
    // use jqXHR as a deferred object
    jqXHR.done(function(data){
      // response that contains portal data
      if (data && data.result && $.isNumeric(data.result.minLevelOfDetail)) {
        var minLevelOfDetail = data.result.minLevelOfDetail;
        $.each(data.result.map, function(i){
          var quadkey = i;
          // deleted entity: links, control fields
          $.each(this.deletedGameEntityGuids, function(){
          });
          $.each(this.gameEntities, function(){
            // guid format: <md5>.<type code>
            var guid = this[0];
            var timestamp = this[1];
            var entity = this[2];
            var type = entity.edge ? 'link' : entity.capturedRegion ? 'control_field' : 'portal';
            var json = {};
            entity.timestamp = timestamp;
            json[guid.replace('.', '-')] = entity;

            if(type === 'portal') {
              // save portal data to Firebase
              fbase.upsertPortal(json);
            }
          });
        });
      }
    });
  });
};

// bootstrap
var f = function(){
  setTimeout(function(){
    if(window.jQuery) {
      setupFirebase();
      setupPrefilter();
    } else {
      f();
    }
  }, 100);
};

// only run once
window.fbase || f();

})();
