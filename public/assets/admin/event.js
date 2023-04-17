var Events = function () {
	this.__construct = function () {
		this.loader();
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
		jQuery(function () {
			$(".loader-admin").fadeOut("slow");
		});
	};

	this.commonForm = function () {
		$(document).on("submit", "#common-form", function (e) {
			e.preventDefault();
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
		$(document).on("submit", "#submit-form", function (e) {
			e.preventDefault();
			$("#submit-btn").attr("disabled", true);
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
							$("#" + i).parents(".form-group").append('<span class="text-red-500">' + out.errors[i] + "</span>");
							$("#" + i).focus();
						}
						$("#submit-btn").attr("disabled", false);
						return false;
					}
					if (out.result === 1) {
						toastr.remove();
						toastr.success(out.msg);
						window.setTimeout(function () {
							window.location.href = out.url;
						}, 1000);
					}
					if (out.result === -1) {
						toastr.remove();
						toastr.error(out.msg);
						$("#submit-btn").attr("disabled", false);
						return false;
					}
				},
			});
		});

		$(document).on("submit", "#submitProfileForm", function (e) {
			e.preventDefault();
			// alert()
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

		$(document).on("submit", "#submit-setting-form", function (e) {
			e.preventDefault();
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
		$(document).on('click', '.openModel', function (e) {
			e.preventDefault();
			var url = $(this).data("url");
			var data_id = $(this).data("id");
			$.post(url, { data_id: data_id }, function (out) {
				if (out.result == 1) {
					// Destroy DataTable instance before reinitializing
					$('#myTable').DataTable().destroy();
					$(".modelWrapper").html(out.htmlwrapper);
					$('#model_wrapper').modal('show');
					// Destroy previous instance of TinyMCE if it exists
					if (tinymce.get('answer')) {
						tinymce.get('answer').remove();
					}
					// Initialize TinyMCE in modal
					tinymce.init({
						selector: 'textarea', // Replace this CSS selector to match the placeholder element for TinyMCE
						plugins: 'code table lists',
						toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
					});
				}
			})
		});

		function reloadDataTable() {
			// Reinitialize DataTable
			$("#data-table, .data-table").DataTable({
				dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
				paging: true,
				ordering: true,
				info: false,
				searching: true,
				lengthChange: true,
				lengthMenu: [10, 25, 50, 100],
				language: {
					lengthMenu: "Show _MENU_ entries",
					paginate: {
						previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
						next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
					},
					search: "Search:"
				}
			});
		}

		$('#myTable').on('hidden.bs.modal', function () {
			reloadDataTable();
		})
	};

	this.changeStatus = function () {
		$(document).on("click", ".status", function (e) {
			e.preventDefault();
			var url = $(this).attr("href");
			var status_type = $(this).attr("status-type");
			var text = '';
			if (status_type == 'Active') {
				text = 'Are you sure you want to change the status to ' + status_type.toLowerCase() + ' ?';
			}
			if (status_type == 'Inactive') {
				text = 'Are you sure you want to change the status to ' + status_type.toLowerCase() + ' ?';
			}
			if (status_type == 'Blocked') {
				text = 'Are you sure you want to change the status to ' + status_type.toLowerCase() + ' ?';
			}
			if (!status_type) {
				text = 'Are you sure you want to delete?';
			}
			Swal.fire({
				title: text,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: "<span><i class='la la-thumbs-up'></i><span>Yes</span></span>",
				cancelButtonText: "<span><i class='la la-thumbs-down'></i><span>No, thanks</span></span>",
				customClass: {
					confirmButton: "bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded inline-flex items-center",
					cancelButton: "bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded inline-flex items-center",
					popup: 'bg-white rounded-md shadow-lg p-6 sm:p-12 max-w-md mx-auto'
				}
			}).then((result) => {
				if (result.isConfirmed) {
					$.post(url, {status_type:status_type}, function (out) {
						if (out.result === 1) {
							location.reload();
							if (status_type == 'Active') {
								toastr.remove()
								toastr.success(out.msg);
							}
							if (status_type == 'Inactive') {
								toastr.remove()
								toastr.error(out.msg);
							}
							if (!status_type) {
								toastr.remove()
								toastr.error(out.msg);
							}
						}
					});
				}
			});
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