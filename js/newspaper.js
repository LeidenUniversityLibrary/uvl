
(function ($) {

Drupal.behaviors.newspaperIssues = {
  attach: function (context) {
    $('.islandora-newspaper-content', context).once('newspaper-issues', function () {

      var selectYear = $('#islandora_newspaper_select_year');
      var selectedYear = selectYear.val();

      $(this).find('.newspaperissues').not('.year' + selectedYear).hide(0);

      selectYear.change(function(e) {
        var selectedYear = $('#islandora_newspaper_select_year').val();
        var issues = $('.islandora-newspaper-content .newspaperissues');

        issues.not('.year' + selectedYear).hide(0);
        issues.filter('.year' + selectedYear).show(0);
        $('.islandora-newspaper-content .fieldset-wrapper').scrollTop(0);
      });
    });
  }
};

})(jQuery);
