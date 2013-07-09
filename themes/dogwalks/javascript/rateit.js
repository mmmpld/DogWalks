jQuery("#Form_ratingsaveratings").children().hide();
jQuery('.rateit').bind('rated', function (e) {
  var ri = jQuery(this);
  var value = ri.rateit('value');

  ri.rateit('readonly', true); // lock rating once voted

  var pid = jQuery("#Form_ratingsaveratings_pageid").val();
  jQuery.post("rating/saveratings", {rate: value, pageid: pid}, function(data)
  {
    var obj = jQuery.parseJSON(data);
    // -- Update Ranking
    var statusmsg = "Rating " + ((obj.status == 0) ? "received" : "updated") + ", thanks!";
    jQuery("#Form_ranking_info").html(statusmsg).fadeIn(0);
    
    setTimeout(function(){
    	jQuery("#Form_ranking_info").fadeOut(1000);
    	ri.rateit('readonly', false);
	  }, 2000);
  });
});