var StreetViewPlus = (function($) {
  return function(o){
    var options = $.extend({
          target: 'body',
          url: '',
          survey: [],
          panoOptions: {},
          onSubmit: function(){}
        }, o),
        $container,
        $surveyForm,
        panorama,
        self = {};

    // Init the Streetview widget
    var initStreetView = function($svContainer) {
      panorama = new google.maps.StreetViewPanorama($svContainer.get(0), options.panoOptions);
    };

    // Init a survey
    var initSurvey = function($surveyContainer) {
      var latLng = panorama.getPosition(),
          lat = latLng ? latLng.lat() : '',
          lng = latLng ? latLng.lng() : '',
          pov = panorama.getPov();

      $surveyContainer.empty();

      $surveyContainer.append(
        '<input name="lat" type="hidden" value="'+lat+'"></input>' +
        '<input name="lng" type="hidden" value="'+lng+'"></input>' +
        '<input name="heading" type="hidden" value="'+pov.heading+'"></input>' +
        '<input name="pitch" type="hidden" value="'+pov.pitch+'"></input>' +
        '<input name="zoom" type="hidden" value="'+pov.zoom+'"></input>'
      );

      $.each(options.survey, function(i, item) {
        var name = item.name || item.id,
            input = item.type === 'textarea' ?
              '<textarea id="'+item.id+'" class="'+item['class']+'" name="'+name+'"></textarea>' :
              '<input id="'+item.id+'" class="'+item['class']+'" name="'+name+'" type="'+item.type+'"></input>';

        $surveyContainer.append('<label for="'+item.id+'">'+item.title+'</label>'+ input);
      });

      $surveyContainer.append('<input id="svp-submit" type="submit" value="Save"></input>');
    };

    // Bind events
    var bindEvents = function(){
      $surveyForm.submit(function() {
        var args = Array.prototype.slice.call(arguments);
        if (options.onSubmit) {
          options.onSubmit.apply(this, args);
        }
      });

      google.maps.event.addListener(panorama, 'pov_changed', function() {
        var pov = panorama.getPov();
        $('.svp-survey input[name="heading"]').val(pov.heading);
        $('.svp-survey input[name="pitch"]').val(pov.pitch);
        $('.svp-survey input[name="zoom"]').val(pov.zoom);
      });

      google.maps.event.addListener(panorama, 'position_changed', function() {
        var latLng = panorama.getPosition();
        $('.svp-survey input[name="lat"]').val(latLng.lat());
        $('.svp-survey input[name="lng"]').val(latLng.lng());
      });
    };

    // Init the thing
    var init = function() {
      $container = $('<div class="svp-container">' +
          '<div class="svp-streetview"></div>' +
          '<form class="svp-survey" action="'+options.url+'"></form>' +
        '</div>')
        .appendTo(options.target);

      $surveyForm = $('form.svp-survey', $container);

      initStreetView($('.svp-streetview', $container));
      initSurvey($surveyForm);

      bindEvents();

      if (panorama.getPosition()) {
        self.show();
      } else {
        self.hide();
      }
    };

    // Public functions
    self.setPosition = function(latLng) {
      panorama.setPosition(latLng);
      self.show();

      self.reset();
    };

    self.setPov = function(heading, pitch, zoom) {
      panorama.setPov({
        heading: heading,
        pitch: pitch,
        zoom: zoom
      });
    };

    self.setZoom = function(zoom) {
      var pov = panorama.getPov();
      self.setPov(pov.heading, pov.pitch, zoom);
    };

    self.setSurvey = function(survey) {
      options.survey = survey;
      initSurvey($surveyForm);
    };

    self.hide = function() {
      $container.hide();
      panorama.setVisible(false);
    };

    self.show = function() {
      $container.show();
      panorama.setVisible(true);
    };

    self.submit = function() {
      $surveyForm.submit();
    };

    self.reset = function() {
      $surveyForm.get(0).reset();
    };

    init();

    return self;
  };
})(jQuery);