import {Controller} from "stimulus"

export default class extends Controller {
    static targets = ["exchanges", "buttonbox"];

    showExchanges() {
        this.exchangesTarget.style.display = "block";
        this.buttonboxTarget.style.display = "none";
    }
}
