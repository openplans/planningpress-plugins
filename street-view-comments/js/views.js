var Fitzgerald = Fitzgerald || {};

(function(F, $) {
  // Setup the namespace to trigger/bind events
  _.extend(F, Backbone.Events);

  F.LocationTitleView = Backbone.View.extend({
    initialize: function(){
      var self = this;

      // Allow override
      self.setTitle = self.options.setTitle || self.setTitle;

      F.on('locationupdatebyslider', this.onLocationUpdate, this);
      F.on('locationupdatebyrouter', this.onLocationUpdate, this);
      F.on('locationupdatebygraph', this.onLocationUpdate, this);
    },
    onLocationUpdate: function(model) {
      if (model) {
        this.locationModel = model;
        this.render();
      }
    },
    render: function(){
      this.setTitle(this.locationModel.get('name'));
    },
    setTitle: function(title) {
      this.$el.html(title);
    }
  });

  F.StreetviewView = Backbone.View.extend({
    initialize: function(){
      var self = this;

      self.panorama = new google.maps.StreetViewPanorama(self.$el.get(0), self.options.panoOptions);

      google.maps.event.addListener(self.panorama, 'pov_changed', function() {
        var pov = self.panorama.getPov();
        F.trigger('povupdatebystreetview', pov.heading, pov.pitch, pov.zoom);
      });

      google.maps.event.addListener(self.panorama, 'position_changed', function() {
        var latLng = self.panorama.getPosition();
        F.trigger('locationupdatebystreetview', latLng.lat(), latLng.lng());
      });

      F.on('locationupdatebyslider', this.onLocationUpdate, this);
      F.on('locationupdatebyrouter', this.onLocationUpdate, this);
      F.on('locationupdatebygraph', this.onLocationUpdate, this);
      F.on('povupdatebyview', this.setPov, this);
    },
    onLocationUpdate: function(model) {
      if (model) {
        this.locationModel = model;
        this.render();
      }
    },
    render: function() {
      this.setPosition(this.locationModel.get('lat'), this.locationModel.get('lng'));
      this.setPov(0, 0, 1);
    },
    setPosition: _.debounce(function(lat, lng) {
      var latLng = new google.maps.LatLng(lat, lng);
      this.panorama.setPosition(latLng);
    }, 200),
    setPov: _.debounce(function(heading, pitch, zoom) {
      this.panorama.setPov({
        heading: heading,
        pitch: pitch,
        zoom: zoom
      });
    }, 300)
  });

  F.FeedbackFormView = Backbone.View.extend({
    initialize: function(){
      var self = this;

      // Add hidden fields
      self.$el.append(
        '<input name="lat" type="hidden"></input>' +
        '<input name="lng" type="hidden"></input>' +
        '<input name="heading" type="hidden"></input>' +
        '<input name="pitch" type="hidden"></input>' +
        '<input name="zoom" type="hidden"></input>'
      );

      // Handle submit event
      self.$el.submit(function() {
        self.submit.apply(self, Array.prototype.slice.call(arguments));
      });

      $(document).delegate(self.options.showFormEl, 'click', function() {
        self.showForm.apply(self, Array.prototype.slice.call(arguments));
      });

      self.$el.dialog({
        title: 'Add a Comment',
        autoOpen: false,
        modal: true,
        width: 400,
        resizable: false,
        buttons: [
          {
            id: 'fitzgerald-dialog-save',
            text: "Save",
            click: function() {
              self.$el.submit();
              $(this).dialog("close");
            }
          }
        ]
      });

      self.initCharCounter();

      F.on('locationupdatebyslider', this.onLocationUpdate, this);
      F.on('locationupdatebyrouter', this.onLocationUpdate, this);
      F.on('locationupdatebygraph', this.onLocationUpdate, this);
      F.on('povupdatebystreetview', this.setPov, this);
    },
    onLocationUpdate: function(model) {
      if (model) {
        this.locationModel = model;
        this.render();
      }
    },
    render: function(){
      $('input[name="lat"]').val(this.locationModel.get('lat'));
      $('input[name="lng"]').val(this.locationModel.get('lng'));
    },
    setPov: function(heading, pitch, zoom) {
      this.$('input[name="heading"]').val(heading);
      this.$('input[name="pitch"]').val(pitch);
      this.$('input[name="zoom"]').val(zoom);
    },
    showForm: function() {
      this.$('.dot-survey-item').val('');
      this.updateCharCount();
      this.$el.show().dialog('open');
    },
    submit: function(evt){
      var feedback = {},
          self = this;

      evt.preventDefault();

      $.each(self.$el.serializeArray(), function(i, item) {
        feedback[item.name] = item.value;
      });

      feedback.heading = parseFloat(feedback.heading);
      feedback.pitch = parseFloat(feedback.pitch);
      feedback.zoom = parseInt(feedback.zoom, 10);

      this.save(feedback);
    },
    save: function(feedback) {
      var self = this;
          feedback.intersection_id = self.locationModel.get('id');

      new F.FeedbackModel().save(feedback, {
        success: function (model, response) {
          // Copy the array
          var allFeedback = self.locationModel.get('feedback').slice();
          allFeedback.push(feedback);
          self.locationModel.set({'feedback': allFeedback});
        }
      });
    },
    initCharCounter: function() {
      var self = this;
      this.$saveBtn = $('#fitzgerald-dialog-save');
      this.$textarea = self.$('textarea');
      this.$counter = $('<div class="fitzgerald-counter">counter</div>').insertAfter(this.$textarea);

      this.$textarea.keyup(function() { self.updateCharCount.call(self); });
      this.$textarea.change(function() { self.updateCharCount.call(self); });

      this.updateCharCount();
    },
    updateCharCount: function() {
      var available,
          self = this;

      function charsLeft() {
        var chars = self.$textarea.val().length;
        return self.options.maxChars - chars;
      }

      available = charsLeft();
      // Update counter
      self.$counter.html(available);

      if (available < 0 || available === self.options.maxChars) {
        // Disable
        self.$saveBtn.attr('disabled', 'disabled');
      } else {
        // Enable
        self.$saveBtn.removeAttr('disabled');
      }
    }
  });

  F.FeedbackListView = Backbone.View.extend({
    initialize: function(){
      var self = this;
      self.$list = self.$('.dot-feedback');
      self.$nav = self.$('.dot-feedback-nav');
      self.topCommentIndex = 0;

      // Update the list if we move locations
      F.on('locationupdatebyslider', self.onLocationUpdate, self);
      F.on('locationupdatebyrouter', self.onLocationUpdate, self);
      F.on('locationupdatebygraph', self.onLocationUpdate, self);
      // Check if we should show the view link or not
      F.on('povupdatebystreetview', self.onPovUpdate, self);

      // Update the list if the model changes
      self.collection.bind('change', self.render, self);

      // Click the comment
      self.$list.delegate('li', 'click', function(evt){
        evt.preventDefault();
        var feedbackList = self.locationModel.get('feedback'),
            index = parseInt($(this).attr('data-index'), 10);

        self.focusOnFeedback(feedbackList, index);
      });

      // Set a class on the "next" comment, supports looping.
      // Assumes most recent feedback is at the end, so next
      // feedback is older (smaller index).
      self.$el.delegate('.dot-feedback-nav-next', 'click', function(evt){
        evt.preventDefault();
        var feedbackList = self.locationModel.get('feedback'),
            index = (self.topCommentIndex-1 < 0) ? feedbackList.length-1 : self.topCommentIndex-1;

        self.focusOnFeedback(feedbackList, index);
      });

      // Set a class on the "previous" comment, supports looping.
      // Assumes most recent feedback is at the end, so previous
      // feedback is newer (larger index).
      self.$el.delegate('.dot-feedback-nav-prev', 'click', function(evt){
        evt.preventDefault();
        var feedbackList = self.locationModel.get('feedback'),
            index = (self.topCommentIndex+1 >= feedbackList.length) ? 0 : self.topCommentIndex+1;

        self.focusOnFeedback(feedbackList, index);
      });
    },
    onPovUpdate: function(heading, pitch, zoom) {
      var f, feedbackList = this.locationModel.get('feedback');

      if (feedbackList.length > 0) {
        f = feedbackList[this.topCommentIndex];
        if (f.heading !== heading || f.pitch !== pitch || f.zoom !== zoom) {
          this.$('.dot-feedback-top .fitzgerald-view-comment-link').show();
        }
      }
    },
    onLocationUpdate: function(model) {
      if (model) {
        this.locationModel = model;
        this.render();
      }
    },
    render: function(){
      var self = this,
          feedbackList = self.locationModel.get('feedback'),
          feedbackLen = feedbackList.length;
      self.$list.empty();

      _.each(feedbackList, function(attrs, i) {
        var color = self.options.colors[i % self.options.colors.length];
            charClass = '';

        if (attrs.desc.length < 50) {
          charClass = 'lt50';
        } else if (attrs.desc.length < 100) {
          charClass = 'lt100';
        }

        self.$list.append('<li data-index="'+i+'" class="'+ color +'"><span class="'+charClass+'">' +
          '<a href="#">'+ attrs.desc + '</a></span>' +
          '<a href="#" class="fitzgerald-view-comment-link">click to view</a></li>');
      });

      if (feedbackLen > 0) {
        self.focusOnFeedback(feedbackList, feedbackLen-1, true);
        self.$list.show();
      } else {
        self.$list.hide();
      }

      if (feedbackLen > 1) self.$nav.show(); else self.$nav.hide();
    },
    focusOnFeedback: function(feedbackList, index, preventTrigger) {
      var feedbackLen = feedbackList.length;
      this.topCommentIndex = index;
      // Remove top class
      this.$list.find('li').removeClass('dot-feedback-top');
      // Reset the top class
      this.$list.find('li[data-index=' + this.topCommentIndex + ']').addClass('dot-feedback-top');
      // Adjust Street View direction
      if (!preventTrigger) {
        F.trigger('povupdatebyview', feedbackList[index].heading, feedbackList[index].pitch, feedbackList[index].zoom);
      }
      // Set the state (1 of 12) or whatever
      this.$nav.find('.dot-feedback-nav-state').html(feedbackLen-index+ ' of ' + feedbackLen);

      this.$('.dot-feedback-top .fitzgerald-view-comment-link').hide();
    }
  });

  F.FeedbackActivityView = Backbone.View.extend({
    initialize: function(){
      this.collection.bind('reset', this.render, this);
      this.collection.bind('change', this.render, this);
    },
    render: function(){
      var self = this,
          values = $.map(self.collection.toJSON(), function(location, i) {
            return location.feedback.length;
          }),
          config = {
            type: 'bar',
            height: 20,
            barSpacing: 2,
            barColor: '#4b99da',
            negBarColor: '#4b99da',
            disableTooltips: true
          };

      config.barWidth = Math.floor((self.$el.parent().width() - ((values.length - 1) * config.barSpacing)) / values.length);
      self.$el.sparkline(values, config);
      self.$el.bind('sparklineClick', function(evt) {
        var sparkline = evt.sparklines[0],
            region = sparkline.getCurrentRegionFields()[0];
        F.trigger('locationupdatebygraph', self.collection.at(region.offset));
      });
    }
  });

  F.YouarehereTooltipView = Backbone.View.extend({
    initialize: function(){
      F.on('locationupdatebyslider', this.hide, this);
      F.on('locationupdatebyrouter', this.onLocationUpdate, this);
      F.on('locationupdatebygraph', this.hide, this);
    },
    onLocationUpdate: function(model) {
      if (model) {
        this.locationModel = model;
        this.render();
      }
    },
    render: function(){
      var percent = this.collection.indexOf(this.locationModel) / this.collection.length;
      this.$el.css('left', (percent*100) + '%').show();
    },
    hide: function() {
      this.$el.hide();
    }
  });

  F.TooltipView = Backbone.View.extend({
    initialize: function(){
      F.on('locationupdatebyslider', this.onLocationUpdate, this);
      F.on('locationupdatebyrouter', this.onLocationUpdate, this);
      F.on('locationupdatebygraph', this.onLocationUpdate, this);
      this.collection.bind('change', this.render, this);
    },
    onLocationUpdate: function(model) {
      if (model) {
        this.locationModel = model;
        this.render();
      }
    },
    render: function(){
      var percent = this.collection.indexOf(this.locationModel) / this.collection.length,
          count = this.locationModel.get('feedback').length,
          label = 'Comment' + ((count !== 1) ? 's' : '');

      this.$el
        .css('left', (percent*100) + '%')
        .html('<strong>' + count + '</strong> ' + label)
        .show();
    }
  });

  // The map slider view
  F.NavigatorView = Backbone.View.extend({
    initialize: function(){
      // Render thyself when the data shows up
      this.collection.bind('reset', this.render, this);

      F.on('locationupdatebyrouter', this.onLocationUpdate, this);
      F.on('locationupdatebygraph', this.onLocationUpdate, this);
    },
    onLocationUpdate: function(model) {
      if (model) {
        this.locationModel = model;
        this.setPosition();

        if (this.router) {
          this.router.navigate(this.locationModel.get('id').toString());
        }
      }
    },
    render: function() {
      var self = this,
          max = self.collection.length-1;

      // Setup slider
      self.$el.slider({
        max: max,
        slide: function(evt, ui) {
          F.trigger('locationupdatebyslider', self.collection.at(ui.value));
        },
        stop: function(evt, ui) {
          // Update the cursor icon
          $(ui.handle).removeClass('grabbed');
          self.router.navigate(self.collection.at(ui.value).get('id').toString());
        }
      });

      // Change to the grabbed icon
      self.$('.ui-slider-handle').mousedown(function(){
        $(this).addClass('grabbed');
      });

      // Update to the first location
      F.trigger('locationupdatebyrouter', self.collection.at(Math.round(max / 2)));

      // Setup routing
      self.router = new F.Router({
        collection: self.collection
      });
      Backbone.history.start();
    },
    setPosition: function(){
      this.$el.slider('value', this.collection.indexOf(this.locationModel));
    }
  });
})(Fitzgerald, jQuery);