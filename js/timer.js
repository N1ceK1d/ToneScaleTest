$(document).ready(function(){
  // Проверяем, есть ли значение таймера в localStorage 
  // 1800 = 30 минут
  var counter = localStorage.getItem('counter') || 1800;

  var interval = setInterval(function(){
      counter--;
      if (counter < 0) {
          clearInterval(interval);
          $('.end_test_btn').trigger('click');
          return;
      }

      var minutes = Math.floor(counter / 60);
      var seconds = counter % 60;
      $('#timer').text((minutes >= 10 ? minutes : '0' + minutes) + ':' + (seconds >= 10 ? seconds : '0' + seconds));
  }, 1000);

  // Сохраняем значение таймера в localStorage каждую секунду
  setInterval(function() {
      localStorage.setItem('counter', counter);
  }, 1000);
});
