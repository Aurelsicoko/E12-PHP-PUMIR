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