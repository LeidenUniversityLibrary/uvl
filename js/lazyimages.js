
(function ($) {

Drupal.behaviors.lazyLoadImages = {
  attach: function (context) {
    $('BODY', context).once('lazy-load-images', function () {
      var images = document.querySelectorAll('IMG[data-src]');
      var config = {
        root: null,
        rootMargin: '0px 0px 0px 0px',
        threshold: 0
      };

      if ('IntersectionObserver' in window) {
        var isVisible = function(entry) {
          var result = entry.isIntersecting;
          if (result === undefined) {
            // isIntersecting not implemented, try via ratio.
            var ratio = entry.intersectionRatio;
            if (ratio !== undefined) {
              result = (ratio > 0.0);
            }
            else {
              // intersectionRatio not implemented, always show as visible, defeating the lazy loading of images.
              result = true;
            }
          }
          return result;
        }
        let observer = new IntersectionObserver(function (entries, self) {
          for (var i=0; i<entries.length; i++) {
            var entry = entries[i];
            if (isVisible(entry)) {
              var src = entry.target.getAttribute('data-src');
              if (src) {
                entry.target.src = src;
              }
              self.unobserve(entry.target);
            }
          }
        }, config);
  
        for (var i=0; i<images.length; i++) {
          var image = images[i];
          observer.observe(image);
        }
      }
      else {
        for (var i=0; i<images.length; i++) {
          var src = images[i].getAttribute('data-src');
          images[i].src = src;
        }
      }
    });
  }
};

})(jQuery);
