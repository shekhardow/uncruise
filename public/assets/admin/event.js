var Events = function () {
	this.__construct = function () {
		this.loader();
		this.tooltip();
		this.commonForm();
		this.submitForm();
		this.modalForm();
		this.changeStatus();
		this.deleteItem();
		this.reload();
		this.deleteImages();
		this.logout();
		this.sendNotification();
		this.previewImage();
	};

	this.loader = function () {
		$(document).ready(function () {
			$(".loader-admin").fadeOut("slow");
		});
	};

	this.tooltip = function () {
		$(document).ready(function () {
			$('[data-toggle="tooltip"]').tooltip();
			$(document).ready(function () {
				$("#datatable").DataTable({
					responsive: true,
					destroy: true,
				});
			});
			$('.summernote').summernote({
				tabsize: 2,
				height: 300
			});
			$('.dropify').dropify();
			// select 2  dropdown 
			$(document).ready(function () {
				var $disabledResults = $(".select2Custom");
				$disabledResults.select2();
				$('b[role="presentation"]').hide();
			});
			// select 2  dropdown End
		});
	};

	this.commonForm = function () {
		$(document).on("submit", "#common-form", function (evt) {
			evt.preventDefault();
			$(".loader").fadeIn("slow");
			var url = $(this).attr("action");
			var postdata = $(this).serialize();
			$.post(url, postdata, function (out) {
				$(".loader").fadeOut("slow");
				$(".form-group > .text-danger").remove();
				if (out.result === 0) {
					for (var i in out.errors) {
						$("#" + i).parents(".form-group").append('<span class="text-danger">' + out.errors[i] + "</span>");
					}
				}
				if (out.result === 1) {
					var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
					$(".error_msg")
						.removeClass("alert-danger alert-danger")
						.addClass("alert alert-success alert-dismissable")
						.show();
					$(".error_msg").html(message + out.msg);
					$(".error_msg").fadeOut(1000);
					if (out.url !== undefined) {
						window.setTimeout(function () {
							window.location.href = out.url;
						}, 1000);
					}
				}
				if (out.result === -1) {
					var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
					$(".error_msg")
						.removeClass("alert-danger alert-success")
						.addClass("alert alert-danger alert-dismissable")
						.show();
					$(".error_msg").html(message + out.msg);
					$(".error_msg").fadeOut(2000);
				}
				if (out.result === 2) {
					toastr.remove();
					toastr.success(out.msg);
					window.setTimeout(function () {
						window.location.href = out.url;
					}, 1000);
				}
				if (out.result === -2) {
					var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
					$(".error_msg")
						.removeClass("alert-danger alert-success")
						.addClass("alert alert-danger alert-dismissable")
						.show();
					$(".error_msg").html(message + out.msg);
					$(".error_msg").fadeOut(2000);
					window.setTimeout(function () {
						window.location.href = out.url;
					}, 1000);
				}
			});
		});
	};

	this.submitForm = function () {
		$(document).on("submit", "#submit-form", function (evt) {
			evt.preventDefault();
			$.ajax({
				url: $(this).attr("action"),
				type: "post",
				data: new FormData(this),
				processData: false,
				contentType: false,
				success: function (out) {
					$(".form-group > .text-danger").remove();
					if (out.result === 0) {
						for (var i in out.errors) {
							$("#" + i).parents(".form-group").append('<span class="text-danger">' + out.errors[i] + "</span>");
							$("#" + i).focus();
						}
					}
					if (out.result === 1) {
						toastr.remove();
						toastr.success(out.msg);
						$("html,body").animate({ scrollTop: 0, }, 800);
						window.setTimeout(function () {
							window.location.href = out.url;
						}, 1000);
					}
					if (out.result === -1) {
						toastr.remove();
						toastr.error(out.msg);
						$("html,body").animate({ scrollTop: 0, }, 800);
						return false;
					}
				},
			});
		});

		$(document).on("submit", "#submitProfileForm", function (event) {
			event.preventDefault();
			// alert()
			$.ajax({
				url: $(this).attr("action"),
				type: "post",
				data: new FormData(this),
				processData: false,
				contentType: false,
				success: function(out) {
					$(".form-group > .text-danger").remove();
					if (out.result === 0) {
						for (var i in out.errors) {
							$("#" + i).parents(".form-group").append('<span class="text-danger">' + out.errors[i] + "</span>");
							$("#" + i).focus();
						}
					}
					if (out.result === 1) {
						toastr.remove();
						toastr.success(out.msg);
						return true;
					}
					if (out.result === -1) {
						toastr.remove();
						toastr.error(out.msg);
						return false;
					}
				},
			});
		});

		$(document).on("submit", "#submit-setting-form", function (evt) {
			evt.preventDefault();
			$.ajax({
				url: $(this).attr("action"),
				type: "post",
				data: new FormData(this),
				processData: false,
				contentType: false,
				success: function (out) {
					$(".form-group > .text-danger").remove();
					if (out.result === 0) {
						for (var i in out.errors) {
							$("#" + i).parents(".form-group").append('<span class="text-danger">' + out.errors[i] + "</span>");
							$("#" + i).focus();
						}
					}
					if (out.result === 1) {
						toastr.remove();
						toastr.success(out.msg);
						$("html,body").animate({ scrollTop: 0, }, 800);
						window.setTimeout(function () {
							window.location.href = out.url;
						}, 1000);
					}
					if (out.result === -1) {
						toastr.remove();
						toastr.error(out.msg);
						$("html,body").animate({ scrollTop: 0, }, 800);
						return false;
					}
				},
			});
		});
	};

	this.modalForm = function () {
		$(document).on('click','.faqbutton',function(e){
			e.preventDefault();
			var url = $(this).data("url");			
			$.post(url, { }, function (out){
				if (out.result == 1) {
					alert()
					$(".faqFormWrapper").html(out.htmlwrapper);
					$("#faq_modal").modal('show');
				}
			});
		});
	};

	this.changeStatus = function () {
		$(document).on("click", ".status", function (e) {
			e.preventDefault();
			var url = $(this).attr("href");
			var msg_type = $(this).attr("msg-type");
			if (msg_type == 'Active') {
				text = 'Are you sure you want to change the status to ' + msg_type.toLowerCase() + ' ?';
			}
			if (msg_type == 'Inactive') {
				text = 'Are you sure you want to change the status to ' + msg_type.toLowerCase() + ' ?';
			}
			if (msg_type == 'Blocked') {
				text = 'Are you sure you want to change the status to ' + msg_type.toLowerCase() + ' ?';
			}
			if (!msg_type) {
				text = 'Are you sure you want to delete?';
			}
			swal({
				title: text,
				// text: text,
				icon: "success",
				confirmButtonText: "<span><i class='la la-thumbs-up'></i><span>Yes</span></span>",
				confirmButtonClass: "btn btn-danger m-btn m-btn--pill m-btn--air m-btn--icon",
				showCancelButton: !0,
				cancelButtonText: "<span><i class='la la-thumbs-down'></i><span>No, thanks</span></span>",
				cancelButtonClass: "btn btn-secondary m-btn m-btn--pill m-btn--icon"
			}).then(function (e) {
				if (e.value) {
					$.post(url, {}, function (out) {
						if (out.result === 1) {
							window.setTimeout(function () {
								location.reload();
							}, 1000);
							if (msg_type == 'Active') {
								toastr.remove()
								toastr.success(out.msg);
							}
							if (msg_type == 'Inactive') {
								toastr.remove()
								toastr.error(out.msg);
							}
							if (!msg_type) {
								toastr.remove()
								toastr.error(out.msg);
							}
						}
					});
				}
			})
		});
	};

	this.deleteItem = function () {
		$(document).on('click', '.delete', function (evt) {
			evt.preventDefault();
			var url = $(this).attr('href');
			var token = $(this).data("csrf");
			var status_type = $(this).data("status_type");
			var type = $(this).data("type");
			swal({
				title: 'Are you sure you want to delete the ' + type + '?',
				icon: "warning",
				buttons: ["No", "Yes"],
				dangerMode: true,
				closeOnClickOutside: false,
			})
				.then((willDelete) => {
					if (willDelete) {
						$.post(url, { _token: token, status_type: status_type }, function (out) {
							if (out.result === 1) {
								toastr.remove();
								toastr.error(out.msg);
								setTimeout(function () {
									window.location.reload();
								}, 2000);
								return false;
							}
						});
					}
				});
		});
	}

	this.reload = function () {
		$(document).on('click', '.reload', function (e) {
			location.reload();
		});
	}

	this.deleteImages = function () {
		$(document).on('click', '.delete-image', function (e) {
			e.preventDefault();
			$(".loader-admin").fadeIn("slow");
			var url = $(this).attr('href');
			$.post(url, function (out) {
				$(".loader-admin").fadeOut("slow");
				if (out.result === 1) {
					$('#form-page').html(out.form);
					//-------------- Summer Note Start -------------------
					$('.summernote').summernote({
						tabsize: 2,
						height: 300
					});
					//-------------- Summer Note End -------------------

					//-------------- Date Picker Start -------------------
					var t;
					t = mUtil.isRTL() ? {
						leftArrow: '<i class="la la-angle-right"></i>',
						rightArrow: '<i class="la la-angle-left"></i>'
					} : {
						leftArrow: '<i class="la la-angle-left"></i>',
						rightArrow: '<i class="la la-angle-right"></i>'
					};
					$(".datepicker").datepicker({
						rtl: mUtil.isRTL(),
						todayBtn: "linked",
						clearBtn: !0,
						todayHighlight: !0,
						templates: t
					})
					//-------------- Date Picker End -------------------
				}
			});

		});
	};

	this.logout = function () {
		$(document).on('click', '.logout', function (evt) {
			evt.preventDefault();
			var url = $(this).attr('href');
			swal({
				title: "Are you sure you want to logout?",
				icon: "warning",
				buttons: ["No", "Yes"],
				dangerMode: true,
				closeOnClickOutside: false,
			})
				.then((willDelete) => {
					if (willDelete) {
						$.post(url, '', function (out) {
							if (out.result === 1) {
								window.location.href = out.url;
							}
						});
					}
				});
		});
	}

	this.sendNotification = function () {
		$(document).on('click', '.send_notification', function (e) {
			e.preventDefault();
			var url = $(this).attr('href');
			var user_id = []

			$.each($("input[name='user_id']:checked"), function () {
				user_id.push($(this).val());
			});
			if (user_id == "") {
				$(".loader-admin").fadeOut("slow");
				toastr.remove()
				toastr.error('Please Select User.');
				return false;
			} else {
				$(".loader-admin").fadeIn("slow");
				$.post(url, { user_id: user_id }, function (out) {
					$(".loader-admin").fadeOut("slow");
					if (out.result === 1) {
						$('#m_modal_4').modal('show');
						$('.model_wrapper_data').html(out.model_wrapper);

						//-------------- Summer Note Start -------------------
						$('.summernote').summernote({
							tabsize: 2,
							height: 300
						});
						//-------------- Summer Note End -------------------
					}
					if (out.result === -1) {
						toastr.remove()
						toastr.error(out.msg);
					}
				});
			}
		});
	};

	this.previewImage = function () {
		$(document).on('change', '.imagepreview', function (e) {
			var imgInp = $(this);
			const [file] = imgInp[0].files
			if (file) {
				append = "<img src=" + URL.createObjectURL(file) + " height='100px' width='100px'/>"
			}
			$('#image_append').html(append);
		});

	}

	this.__construct();
};
var obj = new Events();