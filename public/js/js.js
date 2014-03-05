$(document).ready(initialiser);

function initialiser() {

    d3.selectAll(".gradientElement")
    .each(
      function(){
        var thiss = d3.select(this);
        var note = parseFloat(thiss.attr("data-note"));
        thiss
        .attr('offset','100%')
        .transition().ease("elastic").delay(500).duration(2000)
        .attr('offset',(100-(note*20)+2)+"%");
      }
    );

   $(".partage>a").on('mouseover',function(){
    this.className = "";
    this.className= "bgcolor0";
   });

    $(".partage>a").on('mouseout',function(){
      stop();
      this.className = "";
      this.className= "bgcolor2";
    });

    $('input').each(function() {

    $(this).on('focus', function() {
      $(this).parent('.champs>div').addClass('active');
    });

    $(this).on('blur', function() {
      if ($(this).val().length == 0) {
        $(this).parent('.champs>div').removeClass('active');
      }
    });

    if ($(this).val() != '') $(this).parent('.champs>div').addClass('active');

  });
    

    var box = $('.formPop');
    var log = $('#formLog');
    var sub = $('#formProject');

    $('.openPop').on('click', function (event) {
      event.preventDefault();
      box.removeClass('hidden');
      setTimeout(function () {
        box.removeClass('visuallyhidden');
      }, 20);
    });

    $('.formPop>div>img').on('click',function(){
        box.addClass('visuallyhidden');        
        box.one('transitionend', function(e) {
          box.addClass('hidden');
        });
    });

    $('#formRate input').on('input',function(){
      var avg = 0;
      if($('#formRate input[type="text"]:valid').length==3)
      {
        $('#formRate input[type="text"]:valid')
        .each(function(){
          avg += parseFloat(this.value);
          console.log(avg+"!!!");
        });
        if(!isNaN(avg)){
          $('#averageNoting>p').text(Math.round(avg/3*10)/10);
        }else{
          $('#averageNoting>p').text('_');
        }
      }else{
        $('#averageNoting>p').text('_')
      }
    });

    $('#openLogin').on('click', function (event) {
      event.preventDefault();
      log.removeClass('hidden');
      setTimeout(function () {
        log.removeClass('visuallyhidden');
      }, 20);
    });

    $('#formLog>div>img').on('click',function(){
        log.addClass('visuallyhidden');        
        log.one('transitionend', function(e) {
          log.addClass('hidden');
          });
    });
    $('#addProject').on('click', function (event) {
      event.preventDefault();
      sub.removeClass('hidden');
      setTimeout(function () {
        sub.removeClass('visuallyhidden');
      }, 20);
    });

    $('#formProject>div>img').on('click',function(){
        sub.addClass('visuallyhidden');        
        sub.one('transitionend', function(e) {
          sub.addClass('hidden');
          });
    });        
}
