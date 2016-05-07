// 复选款全选交互 - no
$(document).ready(function() {
   $(".modal-all-checkbox i").click(function() {
      var _this = $(this);
      if (_this.hasClass("fa fa-square-o")) {
          _this.removeClass("fa fa-square-o");
          _this.addClass("fa fa-check-square-o");
          $(".modal-checkbox").prop('checked',true);
      } else {
          _this.removeClass("fa fa-check-square-o");
          _this.addClass("fa fa-square-o");
          $(".modal-checkbox").prop('checked',false);
      }
   });

   $(".all-checkbox i").click(function() {
      var _this = $(this);
      if (_this.hasClass("fa fa-square-o")) {
          _this.removeClass("fa fa-square-o");
          _this.addClass("fa fa-check-square-o");
          $(".drop-checkbox").prop('checked',true);
      } else {
          _this.removeClass("fa fa-check-square-o");
          _this.addClass("fa fa-square-o");
          $(".drop-checkbox").prop('checked',false);
      }
  });
});
