import {Controller} from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["tab", "panel"]

    // switch between tabs
    // add to your buttons: data-action="click->tabs#select"
    select(event) {
        let currentTab = event.currentTarget;

        // remove aria-selected from all tabs
        this.tabTargets.map(tab => tab.ariaSelected = false);

        // add aria-selected to current tab
        currentTab.ariaSelected = true;

        // hide all panels
        this.panelTargets.map(panel => panel.hidden = true);

        // find matching panel and unhide
        this.panelTargets.find(panel => panel.id === currentTab.getAttribute('aria-controls')).hidden = false;
    }
}
