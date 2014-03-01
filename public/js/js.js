(function() {
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
   
})();

$(document).ready(initialiser);

function initialiser() {

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
}
