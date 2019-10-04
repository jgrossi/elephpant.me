import {Controller} from "stimulus"

export default class extends Controller {
    static targets = ["message"];

    send() {
        if (this.message) {
            const controller = this;
            axios.post(`/message`, {
                receiver_id: this.data.get('id'),
                message: this.message
            })
                .then(function (response) {
                    controller.message = '';
                    jQuery(controller.element).find('.form-box').hide();
                    jQuery(controller.element).find('.message-box').show();
                })
                .catch(function (error) {
                    console.log(error);
                });

        }
    }

    get message() {
        return this.messageTarget.value;
    }

    set message(value) {
        this.messageTarget.value = value;
    }
}
