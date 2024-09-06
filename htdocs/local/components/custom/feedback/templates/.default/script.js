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
        if (response.status === 'success') {
            let result = response.data.result;
            if(result === false) {
                let errors = response.data.errors;
                for (field in errors) {
                    let errorContainer = BX(`${field}-error`);
                    let HtmlText = errors[field].map(function(error){
                        return `<p>${error}</p>`
                    }).join('');
                    // errorContainer.innerHtml = HtmlText;
                    BX.adjust(errorContainer, {html: HtmlText});
                }
            } else if(result === true) {
                form.reset();
                let message = response.data.message;
                let succ = BX.style(BX.create('p', {text: message, class: 'ui-ctl'}), 'color', 'green')
                BX.append(succ,form);
            } else {
                let result = response.data.result;
                let succ = BX.style(BX.create('p', {text: result, class: 'ui-ctl'}), 'color', 'red')
                BX.append(succ,form);
            }
        } else {
                let succ = BX.style(BX.create('p', {text: 'Ошибка отправки формы.', class: 'ui-ctl'}), 'color', 'red')
                BX.append(succ,form);
        }
        });
    });
})