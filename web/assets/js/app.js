$(document).ready(function() {
    var count = localStorage['count'] != undefined ? localStorage['count'] : 3600;
    var step = localStorage['step'] != undefined ? localStorage['step'] : 1;

    var counter = setInterval(timer, 100);

    if (count > 0) {
        printForm(step, []);
        initAjaxForm();
    }

    $('.clearStorage').on('click', function (e) {
        localStorage.clear();
        clearInterval(counter);
        location.reload();
    });

    function timer()
    {
        if (count <= 0)
        {
            alert('time out!');
            clearInterval(counter);
            $('#formBlock').remove();
            return;
        }
        count--;
        localStorage['count'] = count;
        document.getElementById("timer").innerHTML=(count / 10).toFixed(1);
    }

    function printForm(step, data)
    {
        var idParam = '';

        if(data.id  != undefined ) {
            localStorage['id'] =  data.id;
        }

        if(step == 2) {
            data.id = localStorage['id'];
            idParam = data.id != undefined ? '/id/'+data.id : '';
        }

        if(step==3) {
            clearInterval(counter);
        }

        $.get('/step/'+step+idParam, function(data)
        {
            $('#formBlock').html(data.form);
        });
    }



    function initAjaxForm()
    {
        $('body').on('submit', '.ajaxForm', function (e) {

            e.preventDefault();

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize()
            })
            .done(function (data) {
                if (typeof data.message !== 'undefined' && typeof data.step !== 'undefined') {
                    step = data.step;
                    printForm(step, data);
                    localStorage['step'] = step;
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (typeof jqXHR.responseJSON !== 'undefined') {
                    if (jqXHR.responseJSON.hasOwnProperty('form')) {
                        $('#form_body').html(jqXHR.responseJSON.form);
                    }
                } else {
                    alert(errorThrown);
                }

            });
        });
    }
});