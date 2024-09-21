
let content ='<div id="modalFormContainer">'+
                '<form id="modalFormPhone" method="post">' +
                    '<div class="ui-ctl ui-ctl-textbox ui-ctl-w75 ui-ctl-lg content-center" style="padding:20px 0;">' +
                        '<input type="text" class="ui-ctl-element " placeholder="Номер телефона" name="phone" id="phone">' +
                    '</div>' +
                    '<div class="ui-ctl">' +
                        '<input type="submit" name="submit"  value="Отправить" class="ui-btn content-center" id="saveOrderButton">' +
                    '</div>' +
                '</form>'+
            '</div>';

BX.ready(function(){
    let buyButton = document.querySelector('#buyOneClickBtn');

    BX.bind(buyButton, 'click', (e) => {
        BX.PreventDefault(e);
        let productId = buyButton.getAttribute('data-id');
        let parentComponent = buyButton.getAttribute('data-parent');

        buyButton.setAttribute('data-offerId', "<?=$arParams['OFFER_ID'][$arResult['OFFERS_SELECTED']]['ID']?>");
        let popup = new BX.PopupWindow('buyOneClickForm', null, {
            content: content,
            titleBar: 'Введите номер телефона',
            autoHide: true,
            overlay: true,
            buttons: [
                new BX.PopupWindowButton({
                    text: 'Закрыть',
                    className: 'popup-window-button-link',
                    events: {
                        click: function () {
                            popup.close();
                        }
                    }
                })
            ]
        });
        popup.show();
        form = document.querySelector('#modalFormPhone');
        BX.bind(form, 'submit', (e)=>{
            BX.PreventDefault(e);
            let phoneErr = document.querySelector('#phoneErr');
            if (phoneErr) {
                phoneErr.remove();
            }
            let phone = document.querySelector('#phone').value;
            
            if (parentComponent === 'bitrix:sale.basket.basket') {

                BX.ajax.runComponentAction('trainee:buyOneClick',
                    "saveOrder", {
                        mode: 'class',
                        data: {
                            'phone': phone, 
                            'parentComponent': parentComponent,
                        }
                    }).then(function(response) {
                        if (response.data.status === true) {
                            $('#modalFormPhone').remove();
                            popup.setTitleBar('');
                            $('#modalFormContainer').append(`<p style='color:green;text-align:center;' id='phoneErr'>${response.data.mess}</p>`);
                            // $('#phone:first').parent().after(`<p style='color:green;text-align:center;' id='phoneErr'>${response.data.mess}</p>`);
                        } else {
                            message = response.data.error;
                            $('#phone:first').parent().after(`<p style='color:red;text-align:center;' id='phoneErr'>${message}</p>`);
                        }
                    })

            } else if (parentComponent === 'bitrix:catalog.element') {

                let productCount = document.querySelector('.product-item-amount-field').value;
                let productSizeId = document.querySelector('.product-item-scu-item-text-container.selected').getAttribute('data-onevalue');
                let productTypeSize = document.querySelector('.product-item-scu-item-text-container.selected').getAttribute('data-treevalue').split('_')[0];
                let productColorId =  document.querySelector('.product-item-scu-item-color-container.selected').getAttribute('data-onevalue');

                BX.ajax.runComponentAction('trainee:buyOneClick',
                    "saveOrder", {
                        mode: 'class',
                        data: {
                            'phone': phone, 
                            'productId': productId,
                            'parentComponent': parentComponent,
                            'productCount': productCount,
                            'productColorId': productColorId,
                            'productTypeSize': productTypeSize,
                            'productSizeId': productSizeId
                        }
                    }).then(function(response) {
                        console.log(response);
                        if (response.data.status === true) {
                            $('#modalFormPhone').remove();
                            popup.setTitleBar('');
                            $('#modalFormContainer').append(`<p style='color:green;text-align:center;' id='phoneErr'>${response.data.mess}</p>`);
                            // $('#phone:first').parent().after(`<p style='color:green;text-align:center;' id='phoneErr'>${response.data.mess}</p>`);
                        } else if (response.data.status === false) {
                            message = response.data.error;
                            $('#phone:first').parent().after(`<p style='color:red;text-align:center;' id='phoneErr'>${message}</p>`);
                        }
                       
                    }, function(response) {
                        message = response.errors[0].message;
                        $('#phone:first').parent().after(`<p style='color:red;text-align:center;' id='phoneErr'>${message}</p>`);
                    })
            }
            
        })

    })

})
