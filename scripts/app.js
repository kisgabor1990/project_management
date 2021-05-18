$(function () {



    $(".textarea-summernote").summernote();

    //  Form validátor Bootstrap 5

    'use strict'
    let forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })




    // Tooltip Bootstrap 5
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })


    // Confirm Modal
    var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));

    $("#mainContent").on("click", ".table .delete", function (e) {
        e.preventDefault();
        $("#confirmModal a.delete")
            .data("href", $(this).data("href"))
            .data("id", $(this).data("id"))
            .data("header", $(this).data("header"));
        $("#confirmModal .modal_header").html($(this).data("header"));
        confirmModal.show();
    });
    $("#confirmModal a.delete").click(function (e) {
        e.preventDefault();
        let this_r = $(this);

        $.get(this_r.data("href"), function (data) {
            confirmModal.hide();
            $(".row-" + this_r.data("id")).fadeOut(400, "swing", function () {
                $(this).remove();
            });
            successModal.show();
            $("#successModal .modal_content").html(data);
            setTimeout(function () {
                successModal.hide();
            }, 1000)
        });
    });


    // AJAX
    $(document).on("click", "a.is-ajax", function (e) {
        e.preventDefault();
        let this_r = $(this);


        $.get(this_r.attr("href"), function (data) {

            $(".menu a").removeClass("active");
            let url = window.location.protocol + "//" + window.location.host + "/" + this_r.attr("href");
            let params = (new URL(url)).searchParams;
            $("a.nav-" + params.get('module')).addClass("active");

            $("#mainContent").html(data);
            window.history.pushState("", "", this_r.attr("href"));
        });
    });

})