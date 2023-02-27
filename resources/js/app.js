import './bootstrap'

import $ from 'jquery'
window.$ = $

// Maybe next time @see https://ace.c9.io/
import Code from "codemirror"
import "codemirror/mode/php/php"
import "codemirror/lib/codemirror.css"
import "codemirror/theme/abbott.css"

const bootCodeEditor = (el) => {

    // Early return if CodeMirror is already booted
    if (el.nextSibling.CodeMirror) {
        return
    }

    // Boot a new instance of CodeMirror and return it
    return Code.fromTextArea(el, {
        mode: "php",
        theme: "abbott",
        lineNumbers: false,
        autoCloseTags: true,
        smartIndent: true
    })
}

const bootCodeEditorForSelector = () => {
    document.querySelectorAll('.codemirror_php').forEach((el) => {
        bootCodeEditor(el)
    })
}

window.addEventListener('DOMContentLoaded', () => {
    bootCodeEditorForSelector()
})
