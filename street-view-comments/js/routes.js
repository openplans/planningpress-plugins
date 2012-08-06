var Fitzgerald = Fitzgerald || {};

(function(F, $) {

  F.Router = Backbone.Router.extend({
    initialize: function(options) {
      this.collection = options.collection;
    },

    routes: {
      ':id': 'goToIntersection'
    },

    goToIntersection: function(id) {
      var collection = this.collection.get(id);

      if (collection) {
        F.trigger('locationupdatebyrouter', collection);
      }
    }
  });
})(Fitzgerald, jQuery);