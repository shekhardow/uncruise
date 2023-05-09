var Events = function () {
	this.__construct = function () {
		this.loader();
		this.commonForm();
		this.submitForm();
		this.modalForm();
		this.changeStatus();
		this.logout();
		this.check();
		this.sendNotification();
		this.reload();
		this.deleteItem();
		this.deleteImages();
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
			$("#submit-btn").attr("disabled", true);
			var url = $(this).attr("action");
			var postdata = $(this).serialize();
			$.post(url, postdata, function (out) {
				if (out.result === 0) {
					// Remove existing error messages
					$('.form-group').find('.text-red-500').empty();
					// Append new error messages
					for (var i in out.errors) {
						$("#" + i).parents(".form-group").append('<span class="text-sm text-red-500">' + out.errors[i] + "</span>");
						$("#" + i).focus();
					}
					$("#submit-btn").attr("disabled", false);
					return false;
				}
				if (out.result === 1) {
					toastr.remove();
					toastr.success(out.msg);
					window.location.href = out.url;
				}
				if (out.result === -1) {
					toastr.remove();
					toastr.error(out.msg);
					$("#submit-btn").attr("disabled", false);
					return false;
				}
			});
		});
	};

	this.submitForm = function () {
		$(document).on("submit", "#submit-form", function (e) {
			e.preventDefault();
			$("#submit-btn").attr("disabled", true);
            $(".loader-admin").fadeIn("slow");
			$.ajax({
				url: $(this).attr("action"),
				type: "post",
				data: new FormData(this),
				processData: false,
				contentType: false,
				success: function (out) {
                    $(".loader-admin").fadeOut("slow");
					if (out.result === 0) {
						// Remove existing error messages
						$('.form-group').find('.text-red-500').empty();
						// Append new error messages
						for (var i in out.errors) {
							$("#" + i).parents(".form-group").append('<span class="text-sm text-red-500">' + out.errors[i] + "</span>");
							$("#" + i).focus();
						}
						$("#submit-btn").attr("disabled", false);
						return false;
					}
					if (out.result === 1) {
						toastr.remove();
						toastr.success(out.msg);
						window.location.href = out.url;
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
	$(document).on('click', '.closeModal', function () {
		reloadDataTable();
	});

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
						toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
						height: 300
					});
				}
			})
		});
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

	this.logout = function () {
		$(document).on('click', '.logout', function (evt) {
			evt.preventDefault();
			var url = $(this).attr('href');
			// Show the confirmation popup
			Swal.fire({
				title: 'Are you sure you want to logout?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				reverseButtons: true,
				focusCancel: true
			}).then((result) => {
				if (result.isConfirmed) {
					// User confirmed the logout, send logout request to server
					$.post(url, '', function (out) {
						if (out.result === 1) {
							window.location.href = out.url;
						}
					});
				}
			});
		});
	}

	this.check = function () {
		$(document).on("click", ".check", function () {
			if ($(this).prop("checked") === true) {
				$(".users_id").prop("checked", true);
			} else if ($(this).prop("checked") === false) {
				$(".users_id").prop("checked", false);
			}
		});
	};

	this.sendNotification = function () {
		$(document).on('click', '.send_notification', function (e) {
			e.preventDefault();
			var url = $(this).data('url');
			var user_id = []
			$.each($("input[name='user_id']:checked"), function () {
				user_id.push($(this).val());
			});
			if(user_id == ""){
				toastr.remove()
				toastr.error('Please select user(s)');
				return false;
			}else{
				$.post(url, { user_id: user_id }, function (out) {
					if (out.result === 1) {
						// Destroy DataTable instance before reinitializing
						$('#myTable').DataTable().destroy();
						$('#model_wrapper').modal('show');
						$(".modelWrapper").html(out.htmlwrapper);
						// Destroy previous instance of TinyMCE if it exists
						if (tinymce.get('message')) {
							tinymce.get('message').remove();
						}
						// Initialize TinyMCE in modal
						tinymce.init({
							selector: 'textarea', // Replace this CSS selector to match the placeholder element for TinyMCE
							plugins: 'code table lists',
							toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
							height: 300
						});
					}
					if(out.result === -1){
						toastr.remove()
						toastr.error(out.msg);
					}
				});
			}
		});
	};

	this.reload = function () {
		$(document).on('click', '.reload', function (e) {
			location.reload();
		});
	}

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

	this.deleteImages = function () {
        $(document).on('click', '.delete-image', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.post(url, function (out) {
                if (out.result === 1) {
                    toastr.remove();
                    toastr.success(out.msg);
                    $(this).prev('img').remove(); // remove the image element
                    $(this).remove(); // remove the delete button element
                }
                if (out.result === -1) {
                    toastr.remove();
                    toastr.error(out.msg);
                    return false;
                }
            }.bind(this));
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
