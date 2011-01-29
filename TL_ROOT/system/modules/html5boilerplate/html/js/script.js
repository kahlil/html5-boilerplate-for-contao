/* Author: 

*/

/* Use this syntax for your jQuery code if you use two js libs using the $ placeholder
$.noConflict();
jQuery(document).ready(function($) {
  // Code that uses jQuery's $ can follow here.
	$(function(){
		$("a[rel*=lightbox]").fancybox();
	});
}); */

// This activates fancybox for Contao's lightbox links
$(function(){
	$("a[rel*=lightbox]").fancybox();
});




















