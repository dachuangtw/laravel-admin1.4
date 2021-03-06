(function ($) {
    "use strict";

	function clockTimesUp() {
		alert("clock times up");
	}

    function getTimeRemaining(endtime) {
      var t = Date.parse(endtime) - Date.parse(new Date());
      var seconds = Math.floor((t / 1000) % 60);
      var minutes = Math.floor((t / 1000 / 60) % 60);
      var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
      var days = Math.floor(t / (1000 * 60 * 60 * 24));
      return {
        'total': t,
        'days': days,
        'hours': hours,
        'minutes': minutes,
        'seconds': seconds
      };
    }

    function initializeClock(id, endtime) {
      var daysSpan = $('.days');
      var hoursSpan = $('.hours');
      var minutesSpan = $('.minutes');
      var secondsSpan = $('.seconds');

      function updateClock() {
        var t = getTimeRemaining(endtime);

        daysSpan.html(t.days);
        hoursSpan.html(('0' + t.hours).slice(-2));
        minutesSpan.html(('0' + t.minutes).slice(-2));
        secondsSpan.html(('0' + t.seconds).slice(-2))

        if (t.total <= 0) {
          clearInterval(timeinterval);
		  clockTimesUp();
        }
      }

      updateClock();
      var timeinterval = setInterval(updateClock, 1000);
    }

	var setDays = parseInt($('.days').text()) * 24 * 60 * 60 * 1000;
	var setHours = parseInt($('.hours').text()) * 60 * 60 * 1000;
	var setMinutes = parseInt($('.minutes').text()) * 60 * 1000;
	var setSeconds = parseInt($('.seconds').text()) * 1000;
    var deadline = new Date(Date.parse(new Date()) + setDays + setHours + setMinutes + setSeconds);
    initializeClock('clockdiv', deadline);

})(jQuery);
