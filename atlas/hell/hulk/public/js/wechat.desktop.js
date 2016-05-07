/**
 * 微信后台交互
 *
 * @category yunke
 * @package atlas\hell\hulk\public\js
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

$(document).ready(function() {
	$('.wechat-setting li a').click(function () {
	  if ($(this).text() == '手动') {
	     $('.hand-setting').show();
	     $('.hand-setting-url-help').show();
	     $('.no-hand-a').attr('disabled', true);
	     $('.hand-submit').attr('disabled', false);
	  } else {
	     $('.hand-setting').hide();
	     $('.hand-setting-url-help').hide();
	     $('.hand-submit').attr('disabled', true);
	     $('.no-hand-a').attr('disabled', false);
	  }
	});

	$('.graphics-title a i').click(function () {
		var id = $(this).attr('graphics-title-id');

		if ($(this).hasClass('fa fa-sort-down')) {
			$(this).attr('class', 'fa fa-sort-up');
			$('.graphics-title-fid_' + id).show();
		} else {
			$(this).attr('class', 'fa fa-sort-down');
			$('.graphics-title-fid_' + id).hide();
		}
	});

	// $('.m_add').mouseenter(function() {
	// 	$('.m_select').show();
	//     $('.m_add').hide();
	// });
  
 //  	$('.m_select').mouseleave(function() {
 //    	$('.m_select').hide();
 //    	$('.m_add').show();
 //  	});
});
