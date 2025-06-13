import { Controller } from '@hotwired/stimulus';
import { setTheme, initTheme } from "../mazer/js/components/dark.js";

export default class extends Controller {
    connect() {
        const THEME_KEY = "theme"
        const toggler = document.getElementById("toggle-dark")
        const theme = localStorage.getItem(THEME_KEY)

        if(toggler) {
            toggler.checked = theme === "dark"

            toggler.addEventListener("input", (e) => {
                setTheme(e.target.checked ? "dark" : "light", true)
            })
        }

        initTheme();
    }
}
