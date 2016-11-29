function addNoteToDom() {
    "use strict";
    var text = document.getElementById("input").value;
    if (!(typeof text === "undefined")) {
        document.getElementById("input").value = "";
        var parent = document.getElementById("notes"); // Datacontainer for notes
        var note = createNewNote(text);
        parent.appendChild(note);
    }
}

function createNewNote(text) {
    "use strict";
    var parent = document.createElement("div"); // this is the note
    var content = document.createElement("span");
    var text_node = document.createTextNode(text); // textual content
    var metadata = document.createElement("div");
    var button = createDeleteButton(parent);
    parent.setAttribute("class", "note");
    content.setAttribute("class", "content");
    content.appendChild(text_node);
    metadata.setAttribute("class", "metadata");
    metadata.appendChild(document.createTextNode(new Date()));
    parent.appendChild(button);
    parent.appendChild(content);
    parent.appendChild(metadata);
    return parent;
}

function createDeleteButton(node) {
    "use strict";
    var button = document.createElement("input");
    button.type = "button";
    button.value = "X";
    button.className = "delete_button";
    button.onclick = function () {
        console.log(event.target);
        node.parentElement.removeChild(node);
    }
    return button;
}