BX.ready(function(){
    const form = BX('feedback');

    BX.bind(form, 'submit', (e) => {
        BX.PreventDefault(e);
        
        $('#feedback p').last().remove();
        const formData = new FormData(form);
        let arrErrors=BX.findChildren(
            document.body,
            {
                className: 'fieldError'
            }, true
        )
        arrErrors.forEach(function(error){
            BX.adjust(error, {html: ''});
        })
        BX.ajax.runComponentAction('custom:feedback',
        "sendMessage", {
            mode: 'class',
            data: formData
        }).then(function(response) {
            if(response.data.result === true){
                $("#formResult").append(`<p>${response.data.message}</p>`);
                form.reset();      
            } else {
                $("#formResult").append(`<p style='color:red;'>Произошла ошибка!</p>`);
            }
        }, function(response) {
            console.log(response.errors);
            errors = response.errors;
            errors.forEach(function(error){
                // console.log(error.customData);
                fieldname = error.customData.field;
                errorMess = error.message;
                $("#" + fieldname).next().append(`<p>${errorMess}</p>`);
            })
        })
    });
})