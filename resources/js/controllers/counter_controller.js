import {Controller} from "stimulus"

export default class extends Controller {
    static targets = ["quantity"];

    connect() {
        this.timeout = null;
    }

    get quantity() {
        return parseInt(this.quantityTarget.value);
    }

    set quantity(value) {
        this.quantityTarget.value = value;
    }

    increment() {
        this.quantity = parseInt(this.quantity) + 1;
        this.element.classList.add("has-elephpants");
        this.save();
    }

    decrement() {
        if (this.quantity > 0) {
            this.quantity = parseInt(this.quantity) - 1;
            if (this.quantity === 0) {
                this.element.classList.remove("has-elephpants");
            }
            this.save();
        }
    }

    save() {
        clearTimeout(this.timeout);
        let counter = this;
        this.timeout = setTimeout(function () {
            axios.put(`/adoption/${counter.data.get('id')}`, {
                quantity: counter.quantity
            })
                .then(function (response) {
                    jQuery('#stats').load('/my-herd #stats-content');
                })
                .catch(function (error) {
                    console.log(error);
                });
        }, 500);
    }
}
